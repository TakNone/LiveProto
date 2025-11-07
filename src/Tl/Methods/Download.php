<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Crypto\Aes;

use Tak\Liveproto\Errors\Security;

use Tak\Liveproto\Errors\RpcError;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Attributes\Type;

use ArrayIterator;

use InfiniteIterator;

use Throwable;

use Closure;

use function Amp\async;

use function Amp\File\openFile;

use function Amp\File\isDirectory;

use function Amp\File\move;

trait Download {
	protected function download_file(
		string | Closure $path,
		int $size,
		int $dc_id,
		#[Type('InputFileLocation')] $location,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		$partSizeKB = ($size < 0x80000 ? 128 : ($size < 0x100000 ? 256 : 512));
		$limit = intval($size > 0 ? $partSizeKB : 1024) * 1024;
		$offset = 0;
		$client = $this->switchDC(dc_id : $dc_id,media : true,renew : true);
		try {
			$getFile = $client->upload->getFile(location : $location,offset : $offset,limit : $limit,cdn_supported : true,timeout : 10);
		} catch(RpcError $error){
			if($error->getCode() == 303):
				$dc_id = $error->getValue();
				return $this->download_file($path,$size,$dc_id,$location,$progresscallback,$key,$iv);
			else:
				throw $error;
			endif;
		}
		$stream = openFile($path,'wb');
		$percent = 0;
		Logging::log('Download','Start downloading the '.basename($path).' file ...');
		if($getFile instanceof \Tak\Liveproto\Tl\Types\Upload\FileCdnRedirect):
			$client = $this->switchDC(dc_id : $getFile->dc_id,cdn : true,media : true,renew : true);
			while($size > $offset or $size <= 0):
				$cdnFile = $client->upload->getCdnFile(file_token : $getFile->file_token,offset : $offset,limit : $limit,timeout : 10);
				if($cdnFile instanceof \Tak\Liveproto\Tl\Types\Upload\CdnFileReuploadNeeded):
					try {
						$client->upload->reuploadCdnFile(file_token : $getFile->file_token,request_token : $cdnFile->request_token);
						continue;
					} catch(Throwable $error){
						Logging::log('Download Cdn',$error->getMessage(),E_ERROR);
						break;
					}
				endif;
				$key = $getFile->encryption_key;
				$iv = substr($getFile->encryption_iv,0,-4).pack('N',$offset >> 4);
				$bytes = openssl_decrypt($cdnFile->bytes,'AES-256-CTR',$key,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,$iv);
				$hashes = $client->upload->getCdnFileHashes(file_token : $getFile->file_token,offset : $offset);
				foreach($hashes as $i => $value):
					$hash = substr($bytes,$value->limit * $i,$value->limit);
					assert($value->hash === hash('sha256',$hash,true),new Security('File validation failed !'));
				endforeach;
				$offset += strlen($cdnFile->bytes);
				$stream->write($bytes);
				if($size > 0):
					$percent = min(100,($offset / $size) * 100);
					if(is_null($progresscallback) === false):
						if(async($progresscallback(...),$percent)->await() === false):
							Logging::log('Download Cdn','Canceled !',E_WARNING);
							throw new \RuntimeException('Download from Cdn canceled !');
						endif;
					else:
						Logging::log('Download Cdn',$percent.'%');
					endif;
				endif;
				if($limit > strlen($cdnFile->bytes)) break;
			endwhile;
		elseif($getFile instanceof \Tak\Liveproto\Tl\Types\Upload\File):
			while($size > $offset or $size <= 0):
				$requests = array();
				for($i = 0;$i < $this->settings->getParallelDownloads();$i++):
					$requests []= ['location'=>$location,'offset'=>$offset,'limit'=>$limit,'timeout'=>10];
					$offset += $limit;
				endfor;
				$files = $client->upload->getFileMultiple(...$requests,responses : true);
				foreach($files as $file):
					if(is_null($key) === false and is_null($iv) === false):
						$file->bytes = Aes::decrypt($file->bytes,$key,$iv);
					endif;
					$stream->write($file->bytes);
					if($limit > strlen($file->bytes)) break 2;
				endforeach;
				if($size > 0):
					$percent = min(100,($offset / $size) * 100);
					if(is_null($progresscallback) === false):
						if(async($progresscallback(...),$percent)->await() === false):
							Logging::log('Download','Canceled !',E_WARNING);
							throw new \RuntimeException('Download canceled !');
						endif;
					else:
						Logging::log('Download',$percent.'%');
					endif;
				endif;
			endwhile;
		endif;
		if(is_null($progresscallback) === false and $percent != 100):
			if(async($progresscallback(...),floatval(100))->await() === false):
				Logging::log('Download','Canceled !',E_WARNING);
				throw new \RuntimeException('Download canceled !');
			endif;
		endif;
		$stream->close();
		try {
			if(empty(pathinfo($path,PATHINFO_EXTENSION))):
				$extension = ($this->getFileExtension($getFile->type) ?: $this->getFileExtension(strval(mime_content_type($path))));
				if(empty($extension) === false):
					$newpath = $path.chr(46).$extension;
					move($path,$newpath);
				endif;
			endif;
		} catch(Throwable $error){
			Logging::log('Download','I could not change the '.basename($path).' file extension ...');
		}
		Logging::log('Download','Finish downloading the '.basename($path).' file ...');
		return isset($newpath) ? $newpath : $path;
	}
	public function download_photo(
		string $path,
		object $file,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto):
			$file = $file->photo ? $file->photo : throw new \InvalidArgumentException('The message does not contain the photo property !');
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\Photo):
			$dc_id = $file->dc_id;
			$photoSize = end($file->sizes);
			if($photoSize instanceof \Tak\Liveproto\Tl\Types\Other\PhotoStrippedSize or $photoSize instanceof \Tak\Liveproto\Tl\Types\Other\PhotoPathSize or $photoSize instanceof \Tak\Liveproto\Tl\Types\Other\PhotoCachedSize):
				return $this->fetchCachedPhoto($path,$photoSize);
			endif;
			$type = $photoSize->type;
			$size = $this->getPhotoSize($photoSize);
			$location = $this->inputPhotoFileLocation(id : $file->id,access_hash : $file->access_hash,file_reference : $file->file_reference,thumb_size : $type);
			if(isDirectory($path)):
				$path = $path.DIRECTORY_SEPARATOR.strval($file->id);
			endif;
		else:
			throw new \InvalidArgumentException('Your media does not contain photo !');
		endif;
		return $this->download_file($path,$size,$dc_id,$location,$progresscallback,$key,$iv);
	}
	public function download_profile_photo(
		string $path,
		object $file,
		bool $big = true,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\User or $file instanceof \Tak\Liveproto\Tl\Types\Other\Chat or $file instanceof \Tak\Liveproto\Tl\Types\Other\Channel):
			$size = PHP_INT_MAX;
			$peer = $this->get_input_peer($file->id);
			$photo = $file->photo ? $file->photo : throw new \InvalidArgumentException('The user does not contain the photo property !');
			$dc_id = $photo->dc_id;
			$location = $this->inputPeerPhotoFileLocation(peer : $peer,photo_id : $photo->photo_id,big : ($big ? true : null));
			if(isDirectory($path)):
				$path = $path.DIRECTORY_SEPARATOR.strval($photo->photo_id);
			endif;
			return $this->download_file($path,$size,$dc_id,$location,$progresscallback,$key,$iv);
		elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\UserFull):
			$photo = $file->profile_photo ? $file->profile_photo : throw new \InvalidArgumentException('The user does not contain the profile photo property !');
			if($big) $photo = $this->photoCachedIgnore($photo);
			return $this->download_photo($path,$photo,$progresscallback,$key,$iv);
		elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\ChatFull or $file instanceof \Tak\Liveproto\Tl\Types\Other\ChannelFull):
			$photo = $file->chat_photo ? $file->chat_photo : throw new \InvalidArgumentException('The user does not contain the chat photo property !');
			if($big) $photo = $this->photoCachedIgnore($photo);
			return $this->download_photo($path,$photo,$progresscallback,$key,$iv);
		else:
			return $this->download_photo($path,$file,$progresscallback,$key,$iv);
		endif;
	}
	public function download_document(
		string $path,
		object $file,
		bool $thumb = false,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument):
			$file = $file->document ? $file->document : throw new \InvalidArgumentException('The message does not contain the document property !');
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\Document):
			$dc_id = $file->dc_id;
			$size = $file->size;
			if($file->thumbs === null or $thumb === false):
				$type = strval(null);
			else:
				$file->mime_type = 'image/png';
				$thumb = end($file->thumbs);
				if($thumb instanceof \Tak\Liveproto\Tl\Types\Other\PhotoStrippedSize or $thumb instanceof \Tak\Liveproto\Tl\Types\Other\PhotoPathSize or $thumb instanceof \Tak\Liveproto\Tl\Types\Other\PhotoCachedSize):
					return $this->fetchCachedPhoto($path,$thumb);
				endif;
				$type = $thumb->type;
				$size = $this->getPhotoSize($thumb);
			endif;
			$location = $this->inputDocumentFileLocation(id : $file->id,access_hash : $file->access_hash,file_reference : $file->file_reference,thumb_size : $type);
			if(isDirectory($path)):
				$path = $path.DIRECTORY_SEPARATOR.strval($file->id);
			endif;
			if(empty(pathinfo($path,PATHINFO_EXTENSION))):
				$extension = $this->getFileExtension($file->mime_type);
				if(empty($extension) === false):
					$path = $path.chr(46).$extension;
				endif;
			endif;
		else:
			throw new \InvalidArgumentException('Your media does not contain document !');
		endif;
		return $this->download_file($path,$size,$dc_id,$location,$progresscallback,$key,$iv);
	}
	public function download_web_document(string $path,object $file) : string {
		if(isset($file->photo)):
			$file = $file->photo ? $file->photo : $file;
		endif;
		if(isset($file->content)):
			$file = $file->content ? $file->content : $file;
		endif;
		if(isset($file->thumb)):
			$file = $file->thumb ? $file->thumb : $file;
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\WebDocument or $file instanceof \Tak\Liveproto\Tl\Types\Other\WebDocumentNoProxy or $file instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageMediaWebPage or $file instanceof \Tak\Liveproto\Tl\Types\Other\InputWebDocument):
			$url = isset($file->url) ? $file->url : throw new \InvalidArgumentException('The web document does not contain the url property !');
			$mimeType = isset($file->mime_type) ? $file->mime_type : null;
			$headers = get_headers($url,true);
			if($headers !== false):
				$headers = array_change_key_case($headers,CASE_LOWER);
				if(isset($headers['content-type'])):
					$mimeType = $headers['content-type'];
				endif;
			endif;
			if(isDirectory($path)):
				$path = $path.DIRECTORY_SEPARATOR.md5($url);
			endif;
			if(empty(pathinfo($path,PATHINFO_EXTENSION)) and empty($mimeType) === false):
				$extension = $this->getFileExtension($mimeType);
				if(empty($extension) === false):
					$path = $path.chr(46).$extension;
				endif;
			endif;
			$buffer = @file_get_contents($url);
			if(is_string($buffer)):
				$stream = openFile($path,'wb');
				$stream->write($buffer);
				$stream->close();
				return $path;
			else:
				throw new \RuntimeException('Error retrieving buffer : '.$url);
			endif;
		else:
			throw new \InvalidArgumentException('Your media does not contain web document !');
		endif;
	}
	public function download_contact(string $path,object $file) : string {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\InputMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\BotInlineMessageMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\InputBotInlineMessageMediaContact):
			$vcard = isset($file->vcard) ? $file->vcard : throw new \InvalidArgumentException('The contact does not contain the vcard property !');
			if(isDirectory($path)):
				$path = $path.DIRECTORY_SEPARATOR.strval($file->user_id);
			endif;
			if(empty(pathinfo($path,PATHINFO_EXTENSION))):
				$path = $path.chr(46).'vcard';
			endif;
			$stream = openFile($path,'wb');
			$stream->write($vcard);
			$stream->close();
			return $path;
		else:
			throw new \InvalidArgumentException('Your object is not message media contact !');
		endif;
	}
	public function download_secret_file(
		string $path,
		object $file,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\UpdateNewEncryptedMessage):
			$file = $file->decrypted;
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessage):
			if(is_object($file->media)):
				$key = $file->media->key;
				$iv = $file->media->iv;
				$file = $file->file;
			endif;
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedFile):
			if(is_null($key) === false and is_null($iv) === false):
				$hash = new Binary();
				$hash->write(md5($key.$iv,true));
				$fingerprint = $hash->readInt() ^ $hash->readInt();
				if($fingerprint !== $file->key_fingerprint):
					throw new \LogicException('Invalid key fingerprint !');
				endif;
				$size = $file->size;
				$dc_id = $file->dc_id;
				$location = $this->inputEncryptedFileLocation(id : $file->id,access_hash : $file->access_hash);
				return $this->download_file($path,$size,$dc_id,$location,$progresscallback,$key,$iv);
			else:
				throw new \InvalidArgumentException('The value of key and iv arguments should not be null !');
			endif;
		else:
			throw new \InvalidArgumentException('File object is not instance of EncryptedFile !');
		endif;
	}
	public function download_media(
		string $path,
		object $file,
		bool $thumb = false,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		try {
			if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\InputMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\BotInlineMessageMediaContact or $file instanceof \Tak\Liveproto\Tl\Types\Other\InputBotInlineMessageMediaContact):
				return $this->download_contact($path,$file);
			elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\WebDocument or $file instanceof \Tak\Liveproto\Tl\Types\Other\WebDocumentNoProxy):
				return $this->download_web_document($path,$file);
			elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument or $file instanceof \Tak\Liveproto\Tl\Types\Other\Document):
				return $this->download_document($path,$thumb,$file,$progresscallback,$key,$iv);
			elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto or $file instanceof \Tak\Liveproto\Tl\Types\Other\Photo):
				return $this->download_photo($path,$file,$progresscallback,$key,$iv);
			elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\UpdateNewEncryptedMessage or $file instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessage or $file instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedFile):
				return $this->download_secret_file($path,$file,$progresscallback,$key,$iv);
			else:
				return $this->download_profile_photo($path,$file,$progresscallback,$key,$iv);
			endif;
		} catch(Throwable $e){
			error_log($e->getMessage());
			throw new \InvalidArgumentException('Invalid input media !',$e->getCode(),$e);
		}
	}
	protected function getPhotoSize(#[Type('PhotoSize')] object $photoSize) : int {
		return match($photoSize->getClass()){
			'photoSizeEmpty' => 0,
			'photoSize' => $photoSize->size,
			'photoSizeProgressive' => max($photoSize->sizes),
			'photoCachedSize' => strlen($photoSize->bytes),
			'photoPathSize' => strlen($this->decode_vector_thumbnail($photoSize->bytes)),
			'photoStrippedSize' => strlen($this->get_stripped_thumbnail($photoSize->bytes)),
			default => throw new \InvalidArgumentException('Unknown photoSize !')
		};
	}
	protected function fetchCachedPhoto(string $path,#[Type('PhotoSize')] object $photoSize) : string {
		$bytes = match($photoSize->getClass()){
			'photoStrippedSize' => $this->get_stripped_thumbnail($photoSize->bytes),
			'photoPathSize' => $this->decode_vector_thumbnail($photoSize->bytes),
			'photoCachedSize' => $photoSize->bytes,
			default => throw new \InvalidArgumentException('Invalid photoSize for get cache of it !')
		};
		if(isDirectory($path)):
			$path = $path.DIRECTORY_SEPARATOR.md5($bytes);
		endif;
		if(empty(pathinfo($path,PATHINFO_EXTENSION))):
			$path = $path.chr(46).'jpg';
		endif;
		$stream = openFile($path,'wb');
		$stream->write($bytes);
		$stream->close();
		return $path;
	}
	protected function photoCachedIgnore(#[Type('Photo')] object $photo) : object {
		while(isset($photo->sizes) and is_array($photo->sizes) and count($photo->sizes) > 1):
			$photoSize = end($photo->sizes);
			if(in_array($photoSize->getClass(),array('photoCachedSize','photoStrippedSize'))):
				array_pop($photo->sizes);
			else:
				break;
			endif;
		endwhile;
		return $photo;
	}
	private function getFileExtension(object | string $type) : ? string {
		if(is_object($type)):
			return match(true){
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileJpeg => 'jpeg',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileGif => 'gif',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FilePng => 'png',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FilePdf => 'pdf',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileMp3 => 'mp3',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileMov => 'mov',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileMp4 => 'mp4',
				$type instanceof \Tak\Liveproto\Tl\Types\Storage\FileWebp => 'webp',
				default => null
			};
		else:
			return match(strtolower($type)){
				'text/h323' => '323',
				'application/internet-property-stream' => 'acx',
				'application/postscript' => 'ps',
				'audio/x-aiff' => 'aiff',
				'video/x-ms-asf' => 'asx',
				'audio/basic' => 'snd',
				'video/x-msvideo' => 'avi',
				'application/olescript' => 'axs',
				'text/plain' => 'txt',
				'application/x-bcpio' => 'bcpio',
				'image/bmp' => 'bmp',
				'application/vnd.ms-pkiseccat' => 'cat',
				'application/x-netcdf' => 'nc',
				'application/x-x509-ca-cert' => 'der',
				'application/x-msclip' => 'clp',
				'image/x-cmx' => 'cmx',
				'image/cis-cod' => 'cod',
				'application/x-cpio' => 'cpio',
				'application/x-mscardfile' => 'crd',
				'application/pkix-crl' => 'crl',
				'application/x-csh' => 'csh',
				'text/css' => 'css',
				'application/x-director' => 'dir',
				'application/x-msdownload' => 'dll',
				'application/msword' => 'dot',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
				'application/x-dvi' => 'dvi',
				'text/x-setext' => 'etx',
				'application/envoy' => 'evy',
				'application/fractals' => 'fif',
				'model/vrml' => 'vrml',
				'image/gif' => 'gif',
				'application/x-gtar' => 'gtar',
				'application/gzip' , 'application/x-gzip' => 'gz',
				'application/x-hdf' => 'hdf',
				'application/winhlp' => 'hlp',
				'application/mac-binhex40' => 'hqx',
				'application/hta' => 'hta',
				'text/x-component' => 'htc',
				'text/html' => 'html',
				'text/webviewhtml' => 'htt',
				'image/x-icon' => 'ico',
				'image/ief' => 'ief',
				'application/x-iphone' => 'iii',
				'application/x-internet-signup' => 'isp',
				'image/pipeg' => 'jfif',
				'image/jpeg' => 'jpeg',
				'image/png' => 'png',
				'application/x-javascript' => 'js',
				'application/x-latex' => 'latex',
				'video/x-la-asf' => 'lsx',
				'application/x-msmediaview' => 'mvb',
				'audio/x-mpegurl' => 'm3u',
				'application/x-troff-man' => 'man',
				'application/x-msaccess' => 'mdb',
				'application/x-troff-me' => 'me',
				'message/rfc822' => 'nws',
				'audio/mid' => 'rmi',
				'application/x-msmoney' => 'mny',
				'video/quicktime' => 'mov',
				'video/x-sgi-movie' => 'movie',
				'video/mpeg' => 'mpv2',
				'audio/mpeg' => 'mp3',
				'audio/ogg' => 'ogg',
				'application/vnd.ms-project' => 'mpp',
				'application/x-troff-ms' => 'ms',
				'application/vnd.ms-outlook' => 'msg',
				'application/oda' => 'oda',
				'application/pkcs10' => 'p10',
				'application/x-pkcs12' => 'pfx',
				'application/x-pkcs7-certificates' => 'spc',
				'application/x-pkcs7-mime' => 'p7m',
				'application/x-pkcs7-certreqresp' => 'p7r',
				'application/x-pkcs7-signature' => 'p7s',
				'image/x-portable-bitmap' => 'pbm',
				'application/pdf' => 'pdf',
				'image/x-portable-graymap' => 'pgm',
				'application/ynd.ms-pkipko' => 'pko',
				'application/x-perfmon' => 'pmw',
				'image/x-portable-anymap' => 'pnm',
				'application/vnd.ms-powerpoint' => 'ppt',
				'image/x-portable-pixmap' => 'ppm',
				'application/pics-rules' => 'prf',
				'application/x-mspublisher' => 'pub',
				'audio/x-pn-realaudio' => 'ram',
				'image/x-cmu-raster' => 'ras',
				'image/x-rgb' => 'rgb',
				'application/x-troff' => 'tr',
				'application/rtf' => 'rtf',
				'text/richtext' => 'rtx',
				'application/x-msschedule' => 'scd',
				'text/scriptlet' => 'sct',
				'application/set-payment-initiation' => 'setpay',
				'application/set-registration-initiation' => 'setreg',
				'application/x-sh' => 'sh',
				'application/x-shar' => 'shar',
				'application/x-stuffit' => 'sit',
				'application/futuresplash' => 'spl',
				'application/x-wais-source' => 'src',
				'application/vnd.ms-pkicertstore' => 'sst',
				'application/vnd.ms-pkistl' => 'stl',
				'application/x-sv4cpio' => 'sv4cpio',
				'application/x-sv4crc' => 'sv4crc',
				'image/svg+xml' => 'svg',
				'application/x-shockwave-flash' => 'swf',
				'application/x-tar' => 'tar',
				'application/x-tcl' => 'tcl',
				'application/x-tex' => 'tex',
				'application/x-texinfo' => 'texinfo',
				'application/x-compressed' => 'tgz',
				'image/tiff' => 'tiff',
				'application/x-msterminal' => 'trm',
				'text/tab-separated-values' => 'tsv',
				'text/iuls' => 'uls',
				'application/x-ustar' => 'ustar',
				'text/x-vcard' => 'vcf',
				'text/vcard' => 'vcard',
				'audio/x-wav' => 'wav',
				'application/vnd.ms-works' => 'wps',
				'application/x-msmetafile' => 'wmf',
				'application/x-mswrite' => 'wri',
				'wri' => 'application/x-mswrite',
				'image/x-xbitmap' => 'xbm',
				'image/x-xpixmap' => 'xpm',
				'image/x-xwindowdump' => 'xwd',
				'application/x-compress' => 'z',
				'image/webp' => 'webp',
				'application/zip' , 'application/x-zip-compressed' => 'zip',
				'video/mp4' => 'mp4',
				default => null
			};
		endif;
	}
}

?>