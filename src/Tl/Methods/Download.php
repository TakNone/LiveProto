<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Crypto\Aes;

use Tak\Liveproto\Errors\Security;

use Tak\Liveproto\Errors\RpcError;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Enums\TransferKind;

use Tak\Liveproto\Attributes\Type;

use ArrayIterator;

use InfiniteIterator;

use Throwable;

use Generator;

use function Tak\Asyncio\async;

use function Tak\Asyncio\File\openFile;

use function Tak\Asyncio\File\isDirectory;

use function Tak\Asyncio\File\move;

trait Download {
	private const ONE_MB = (1 << 20);
	private const ALIGNMENT_DEFAULT = (1 << 12);
	private const ALIGNMENT_PRECISE = (1 << 10);

	private function align_range(int $offset,int $limit,bool $precise) : array {
		$chunkStart = intdiv($offset,self::ONE_MB) * self::ONE_MB;
		$chunkEnd = $chunkStart + self::ONE_MB - 1;
		$align = intval($precise ? self::ALIGNMENT_PRECISE : self::ALIGNMENT_DEFAULT);
		$offset = intdiv($offset,$align) * $align;
		$offset = max($offset,$chunkStart);
		$maxLimitInChunk = intval($chunkEnd - $offset + 1);
		if($precise):
			$limit = intdiv($limit + $align - 1,$align) * $align;
			$limit = min($limit,self::ONE_MB);
			if($limit > $maxLimitInChunk):
				$limit = intdiv($maxLimitInChunk,$align) * $align;
			endif;
		else:
			$allowed = array(0x100000,0x80000,0x40000,0x20000,0x10000,0x8000,0x4000,0x2000,0x1000);
			if($filtered = array_filter($allowed,fn(int $v) : bool => boolval($v <= $maxLimitInChunk))):
				$limit = max($filtered);
			else:
				throw new \InvalidArgumentException('No valid limit !');
			endif;
		endif;
		/*
		 * Next line is not documented anywhere
		 * So I'm not sure if it's even necessary for the offset to be divisible by the limit
		 */
		$offset = intdiv($offset,$limit) * $limit;
		if(intdiv($offset,self::ONE_MB) !== intdiv($offset + $limit - 1,self::ONE_MB)):
			throw new \InvalidArgumentException('Crosses 1MB chunk !');
		endif;
		return array($offset,$limit);
	}
	public function inputify_file_location(object $media,? string $thumb_size = null) : object {
		$className = $media->getClass();
		return match($className){
			'photo' => $this->inputPhotoFileLocation(id : $media->id,access_hash : $media->access_hash,file_reference : $media->file_reference,thumb_size : strval($thumb_size)),
			'document' => $this->inputDocumentFileLocation(id : $media->id,access_hash : $media->access_hash,file_reference : $media->file_reference,thumb_size : strval($thumb_size)),
			'encryptedFile' => $this->inputEncryptedFileLocation(id : $media->id,access_hash : $media->access_hash),
			'user' , 'chat' , 'channel' => $this->inputPeerPhotoFileLocation(peer : $this->get_input_peer($media->id),photo_id : $media->photo->photo_id,big : true),
			'stickerSet' => $this->inputStickerSetThumb(stickerset : $this->inputStickerSetID(id : $media->id,access_hash : $media->access_hash),thumb_version : intval($media->thumb_version)),
			default => throw new \InvalidArgumentException('The media ( '.$className.' ) is invalid or not supported !')
		};
	}
	protected function perform_download(
		mixed $destination,
		int $size,
		int $dc_id,
		#[Type('InputFileLocation')] object $location,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE,
		? string $mime_type = null
	) : mixed {
		switch($transfer_kind):
			case TransferKind::FILE:
				return $this->download_file(strval($destination),strval($mime_type),$size,$dc_id,$location,$progresscallback,$key,$iv);
			case TransferKind::STREAM:
				return $this->download_chunks($size,$dc_id,$location,$progresscallback,$key,$iv);
			case TransferKind::BROWSER:
				return $this->download_browser(strval($destination),strval($mime_type),$size,$dc_id,$location,$progresscallback,$key,$iv);
			default:
				throw new \InvalidArgumentException('Transfer kind '.$transfer_kind->value.' is not yet supported !');
		endswitch;
	}
	protected function apply_raw_buffer(
		mixed $destination,
		string $bytes,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE,
		? string $mime_type = null
	) : mixed {
		if(is_null($key) === false and is_null($iv) === false):
			$bytes = Aes::decrypt($bytes,$key,$iv);
		endif;
		if(is_null($progresscallback) === false):
			if(async($progresscallback(...),floatval(100))->await() === false):
				Logging::log('Download','Canceled !',E_WARNING);
				throw new \RuntimeException('Download canceled !');
			endif;
		else:
			Logging::log('Download','100%');
		endif;
		switch($transfer_kind):
			case TransferKind::FILE:
				if(isDirectory($destination)):
					$destination = $destination.DIRECTORY_SEPARATOR.md5($bytes);
				endif;
				$stream = openFile($destination,'wb');
				$stream->write($bytes);
				$stream->close();
				if(empty(pathinfo($destination,PATHINFO_EXTENSION))):
					$extension = $this->getFileExtension($mime_type ?: strval(mime_content_type($destination)));
					if(empty($extension) === false):
						$newpath = $destination.chr(46).$extension;
						move($destination,$newpath);
						return $newpath;
					endif;
				endif;
				return $destination;
			case TransferKind::STREAM:
				yield $bytes;
			case TransferKind::BROWSER:
				$extension = $this->getFileExtension($mime_type);
				$filename = md5($bytes).(empty($extension) ? null : chr(46).$extension);
				set_time_limit(0);
				ignore_user_abort(false);
				header('HTTP/1.1 200 OK');
				header('Content-Type: application/octet-stream');
				header('Content-Length: '.strlen($bytes));
				header('Content-Disposition: attachment; filename='.rawurlencode($filename));
				header('Cache-Control: private, must-revalidate');
				header('Pragma: public');
				if($_SERVER['REQUEST_METHOD'] === 'HEAD'):
					return null;
				endif;
				if(connection_aborted()):
					Logging::log('Download','Connection aborted : client disconnected !',E_WARNING);
					return null;
				else:
					echo($bytes);
					flush();
				endif;
				return 200;
			default:
				throw new \InvalidArgumentException('Transfer kind '.$transfer_kind->value.' is not yet supported !');
		endswitch;
	}
	protected function download_chunks(
		int $size,
		int $dc_id,
		#[Type('InputFileLocation')] object $location,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		int $offset = 0,
		int $limit = 1024
	) : Generator {
		$partSizeKB = ($size < 0x80000 ? 128 : ($size < 0x100000 ? 256 : 512));
		$limit = intval($size > 0 ? $partSizeKB : $limit) * 1024;
		list($offset,$limit) = $this->align_range($offset,$limit,false);
		$client = $this->switchDC(dc_id : $dc_id,media : true,renew : true);
		try {
			$getFile = $client->upload->getFile(location : $location,offset : $offset,limit : $limit,cdn_supported : true,timeout : 10);
		} catch(RpcError $error){
			if($error->getCode() == 303):
				$dc_id = $error->getValue();
				return $this->download_chunks($size,$dc_id,$location,$progresscallback,$key,$iv,$offset,$limit);
			else:
				throw $error;
			endif;
		}
		$processes = array();
		$pause = false;
		$connections = boolval($size < 10 * 1024 * 1024) ? array($client) : $this->getMediaConnections(dc_id : $dc_id,pfs : true,count : match(true){
			boolval($size >= 10 * 1024 * 1024 and $size < 30 * 1024 * 1024) => 1,
			boolval($size >= 30 * 1024 * 1024 and $size < 50 * 1024 * 1024) => 2,
			boolval($size >= 50 * 1024 * 1024 and $size < 200 * 1024 * 1024) => 3,
			default => null
		});
		Logging::log('Download','A total of '.count($connections).' connections were received');
		$clients = new InfiniteIterator(new ArrayIterator($connections));
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
				$cdnFile->bytes = openssl_decrypt($cdnFile->bytes,'AES-256-CTR',$key,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,$iv);
				$hashes = $client->upload->getCdnFileHashes(file_token : $getFile->file_token,offset : $offset);
				foreach($hashes as $i => $value):
					$hash = substr($cdnFile->bytes,$value->limit * $i,$value->limit);
					assert($value->hash === hash('sha256',$hash,true),new Security('File validation failed !'));
				endforeach;
				$offset += strlen($cdnFile->bytes);
				$pointer = yield $offset => $cdnFile->bytes;
				if(is_int($pointer)):
					list($offset,$limit) = $this->align_range($pointer,$limit,false);
					Logging::log('Download Cdn','New status offset = '.$offset.' & limit = '.$limit);
					continue;
				endif;
				if(is_null($cdnFile->bytes) || $limit > strlen($cdnFile->bytes)) break;
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
			endwhile;
		elseif($getFile instanceof \Tak\Liveproto\Tl\Types\Upload\File):
			$start = microtime(true);
			while($pause === false):
				$requests = array();
				for($i = 0;$i < $this->settings->getParallelDownloads();$i++):
					$requests []= ['location'=>$location,'offset'=>$offset,'limit'=>$limit,'precise'=>true,'timeout'=>10];
					$offset += $limit;
					if($size <= $offset and $size > 0):
						$pause = true;
						break;
					endif;
				endfor;
				$clients->next();
				$dispatcher = $clients->current()->upload->getFileMultiple(...);
				$processes[$offset] = async(function() use($dispatcher,$key,$iv,$requests) : array {
					$files = $dispatcher(...$requests,cooldown : 0.1,responses : true);
					foreach($files as $index => $file):
						if(is_null($key) === false and is_null($iv) === false):
							$file->bytes = Aes::decrypt($file->bytes,$key,$iv);
						endif;
						$file->offset = $requests[$index]['offset'];
					endforeach;
					return $files;
				});
				foreach($processes as $parts => $future):
					if($pause || $future->isComplete() || count($processes) >= $this->settings->getMediaWorkers()):
						$results = $future->await();
						unset($processes[$parts]);
						foreach($results as $result):
							$pointer = yield $result->offset => $result->bytes;
							if(is_int($pointer)):
								list($offset,$limit) = $this->align_range($pointer,$limit,false);
								Logging::log('Download','New status offset = '.$offset.' & limit = '.$limit);
								if($size <= $offset and $size > 0):
									$pause = true;
								else:
									$processes = array();
									$pause = false;
								endif;
								break 2;
							endif;
							if(is_null($result->bytes) || $limit > strlen($result->bytes)) break;
						endforeach;
						if($size > 0):
							$percent = min(100,($parts / $size) * 100);
							if(is_null($progresscallback) === false):
								if(async($progresscallback(...),$percent)->await() === false):
									Logging::log('Download','Canceled !',E_WARNING);
									throw new \RuntimeException('Download canceled !');
								endif;
							else:
								Logging::log('Download',$percent.'%');
							endif;
						endif;
					else:
						break;
					endif;
				endforeach;
			endwhile;
			$finish = microtime(true);
			Logging::log('Download','Average download speed : '.round(floatval($offset / ($finish - $start)) / 1024 / 1024,2).' Mb/s');
		endif;
	}
	protected function download_browser(
		string $path,
		string $mime_type,
		int $size,
		int $dc_id,
		#[Type('InputFileLocation')] object $location,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : ? int {
		if(headers_sent()):
			throw new \RuntimeException('Headers already sent');
		endif;
		$start = 0;
		$end = $size - 1;
		$statusCode = 200;
		$extension = $this->getFileExtension($mime_type);
		$filename = empty($path) ? md5(serialize($location)).(empty($extension) ? null : chr(46).$extension) : $path;
		if(empty($_SERVER['HTTP_RANGE']) === false):
			if(preg_match('/bytes=(?<rstart>\d*)-(?<rend>\d*)/',trim($_SERVER['HTTP_RANGE']),$match)):
				$rstart = intval($match['rstart']);
				$rend = intval($match['rend']);
				switch(strval(null)):
					case $match['rstart']:
						$start = intval($rend >= $size ? 0 : $size - $rend);
						break;
					case $match['rend']:
						$start = $rstart;
						break;
					default:
						$start = $rstart;
						$end = min($end,$rend);
						break;
				endswitch;
				if($start > $end || $start < 0 || $end >= $size):
					header('HTTP/1.1 416 Range Not Satisfiable');
					header('Content-Range: bytes */'.$size);
					Logging::log('Download','Range not satisfiable !',E_WARNING);
					return null;
				endif;
				$statusCode = 206;
			endif;
		endif;
		$length = intval($end - $start + 1);
		set_time_limit(0);
		ignore_user_abort(false);
		if($statusCode === 206):
			header('HTTP/1.1 206 Partial Content');
		else:
			header('HTTP/1.1 200 OK');
		endif;
		header('Content-Type: application/octet-stream');
		header('Accept-Ranges: bytes');
		header('Content-Length: '.$length);
		if($statusCode === 206):
			header('Content-Range: bytes '.$start.'-'.$end.'/'.$size);
		endif;
		header('Content-Disposition: attachment; filename='.rawurlencode($filename));
		header('Cache-Control: private, must-revalidate');
		header('Pragma: public');
		if($_SERVER['REQUEST_METHOD'] === 'HEAD'):
			return null;
		endif;
		$isFirst = true;
		$isLast = false;
		try {
			$generator = $this->download_chunks($size,$dc_id,$location,$progresscallback,$key,$iv,$start);
			foreach($generator as $offset => $buffer):
				if(connection_aborted()):
					Logging::log('Download','Connection aborted : client disconnected !',E_WARNING);
					return null;
				else:
					if($isFirst):
						$buffer = substr($buffer,$start - $offset);
						$isFirst = false;
					endif;
					$length -= strlen($buffer);
					if($length < 0):
						$buffer = substr($buffer,0,$length);
						$isLast = true;
					endif;
					echo($buffer);
					flush();
					if($isLast) break;
				endif;
			endforeach;
		} catch(Throwable $error){
			header('HTTP/1.1 500 Server error');
			throw $error;
		}
		return $statusCode;
	}
	protected function download_file(
		string $path,
		string $mime_type,
		int $size,
		int $dc_id,
		#[Type('InputFileLocation')] object $location,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : string {
		$generator = $this->download_chunks($size,$dc_id,$location,$progresscallback,$key,$iv);
		if(isDirectory($path)):
			$path = $path.DIRECTORY_SEPARATOR.md5(serialize($location));
		endif;
		$stream = openFile($path,'wb');
		Logging::log('Download','Start downloading the '.basename($path).' file ...');
		foreach($generator as $chunk):
			$stream->write($chunk);
		endforeach;
		$stream->close();
		try {
			if(empty(pathinfo($path,PATHINFO_EXTENSION))):
				$extension = $this->getFileExtension($mime_type ?: strval(mime_content_type($path)));
				if(empty($extension) === false):
					$newpath = $path.chr(46).$extension;
					move($path,$newpath);
					return $newpath;
				endif;
			endif;
		} catch(Throwable $error){
			Logging::log('Download','Could not change the '.basename($path).' file extension ...');
		}
		Logging::log('Download','Finish downloading the '.basename($path).' file ...');
		return $path;
	}
	protected function download_photo(
		mixed $to,
		#[Type('MessageMediaPhoto','Photo')] object $file,
		bool $big = true,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto):
			$file = $file->photo ? $file->photo : throw new \InvalidArgumentException('The message does not contain the photo property !');
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\Photo):
			$dc_id = $file->dc_id;
			if($big):
				$file->sizes = $this->photoCachedIgnore($file->sizes);
			endif;
			$photoSize = end($file->sizes);
			if(in_array($photoSize->getClass(),array('photoCachedSize','photoPathSize','photoStrippedSize'))):
				return $this->apply_raw_buffer($to,$this->fetchCachedPhoto($photoSize),$progresscallback,$key,$iv,$transfer_kind);
			endif;
			$type = $photoSize->type;
			$size = $this->getPhotoSize($photoSize);
			$location = $this->inputPhotoFileLocation(id : $file->id,access_hash : $file->access_hash,file_reference : $file->file_reference,thumb_size : $type);
			return $this->perform_download($to,$size,$dc_id,$location,$progresscallback,$key,$iv,$transfer_kind);
		else:
			throw new \InvalidArgumentException('Your media does not contain photo !');
		endif;
	}
	protected function download_profile_photo(
		mixed $to,
		#[Type('User','Chat','Channel','UserFull','ChatFull','ChannelFull')] object $file,
		bool $big = true,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\User or $file instanceof \Tak\Liveproto\Tl\Types\Other\Chat or $file instanceof \Tak\Liveproto\Tl\Types\Other\Channel):
			$size = -1;
			$peer = $this->get_input_peer($file->id);
			$photo = $file->photo ? $file->photo : throw new \InvalidArgumentException('The user does not contain the photo property !');
			$dc_id = $photo->dc_id;
			$location = $this->inputPeerPhotoFileLocation(peer : $peer,photo_id : $photo->photo_id,big : $big);
			return $this->perform_download($to,$size,$dc_id,$location,$progresscallback,$key,$iv,$transfer_kind);
		elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\UserFull):
			$photo = $file->profile_photo ? $file->profile_photo : throw new \InvalidArgumentException('The user does not contain the profile photo property !');
			return $this->download_photo($to,$photo,$big,$progresscallback,$key,$iv,$transfer_kind);
		elseif($file instanceof \Tak\Liveproto\Tl\Types\Other\ChatFull or $file instanceof \Tak\Liveproto\Tl\Types\Other\ChannelFull):
			$photo = $file->chat_photo ? $file->chat_photo : throw new \InvalidArgumentException('The user does not contain the chat photo property !');
			return $this->download_photo($to,$photo,$big,$progresscallback,$key,$iv);
		else:
			return $this->download_photo($to,$file,$big,$progresscallback,$key,$iv);
		endif;
	}
	protected function download_document(
		mixed $to,
		#[Type('MessageMediaDocument','Document')] object $file,
		bool $thumb = false,
		bool $big = true,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument):
			$file = $file->document ? $file->document : throw new \InvalidArgumentException('The message does not contain the document property !');
		endif;
		if($file instanceof \Tak\Liveproto\Tl\Types\Other\Document):
			$dc_id = $file->dc_id;
			$size = $file->size;
			if($file->thumbs === null or $thumb === false):
				$type = strval(null);
			else:
				if($big):
					$file->thumbs = $this->photoCachedIgnore($file->thumbs);
				endif;
				$file->mime_type = 'image/png';
				$thumb = end($file->thumbs);
				if(in_array($thumb->getClass(),array('photoCachedSize','photoPathSize','photoStrippedSize'))):
					return $this->apply_raw_buffer($to,$this->fetchCachedPhoto($thumb),$progresscallback,$key,$iv,$transfer_kind);
				endif;
				$type = $thumb->type;
				$size = $this->getPhotoSize($thumb);
			endif;
			$location = $this->inputDocumentFileLocation(id : $file->id,access_hash : $file->access_hash,file_reference : $file->file_reference,thumb_size : $type);
			return $this->perform_download($to,$size,$dc_id,$location,$progresscallback,$key,$iv,$transfer_kind,$file->mime_type);
		else:
			throw new \InvalidArgumentException('Your media does not contain document !');
		endif;
	}
	public function download_web_document(
		mixed $to,
		#[Type('WebDocument','WebDocumentNoProxy','DecryptedMessageMediaWebPage','InputWebDocument')] object $file,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		$url = isset($file->url) ? $file->url : throw new \InvalidArgumentException('The web document does not contain the url property !');
		$mimeType = isset($file->mime_type) ? $file->mime_type : null;
		$headers = get_headers($url,true);
		if($headers !== false):
			$headers = array_change_key_case($headers,CASE_LOWER);
			if(isset($headers['content-type'])):
				$mimeType = $headers['content-type'];
			endif;
		endif;
		$buffer = @file_get_contents($url);
		if(is_string($buffer)):
			return $this->apply_raw_buffer($to,$buffer,$progresscallback,$key,$iv,$transfer_kind,$mimeType);
		else:
			throw new \RuntimeException('Error retrieving buffer : '.$url);
		endif;
	}
	protected function download_contact(
		mixed $to,
		#[Type('MessageMediaContact','InputMediaContact','BotInlineMessageMediaContact','InputBotInlineMessageMediaContact')] object $file,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		$vcard = isset($file->vcard) ? $file->vcard : throw new \InvalidArgumentException('The contact does not contain the vcard property !');
		return $this->apply_raw_buffer($to,$vcard,$progresscallback,$key,$iv,$transfer_kind,'text/vcard');
	}
	protected function download_secret_file(
		mixed $to,
		#[Type('UpdateNewEncryptedMessage','DecryptedMessage','EncryptedFile')] object $file,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
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
				return $this->perform_download($to,$size,$dc_id,$location,$progresscallback,$key,$iv,$transfer_kind);
			else:
				throw new \InvalidArgumentException('The value of key and iv arguments should not be null !');
			endif;
		else:
			throw new \InvalidArgumentException('File object is not instance of EncryptedFile !');
		endif;
	}
	public function download_media(
		mixed $destination,
		object $media,
		bool $thumb = false,
		bool $big = true,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		try {
			if($media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact or $media instanceof \Tak\Liveproto\Tl\Types\Other\InputMediaContact or $media instanceof \Tak\Liveproto\Tl\Types\Other\BotInlineMessageMediaContact or $media instanceof \Tak\Liveproto\Tl\Types\Other\InputBotInlineMessageMediaContact):
				return $this->download_contact($destination,$media,$progresscallback,$key,$iv,$transfer_kind);
			elseif($media instanceof \Tak\Liveproto\Tl\Types\Other\WebDocument or $media instanceof \Tak\Liveproto\Tl\Types\Other\WebDocumentNoProxy):
				return $this->download_web_document($destination,$media,$progresscallback,$key,$iv,$transfer_kind);
			elseif($media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument or $media instanceof \Tak\Liveproto\Tl\Types\Other\Document):
				return $this->download_document($destination,$media,$thumb,$big,$progresscallback,$key,$iv,$transfer_kind);
			elseif($media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto or $media instanceof \Tak\Liveproto\Tl\Types\Other\Photo):
				return $this->download_photo($destination,$media,$big,$progresscallback,$key,$iv,$transfer_kind);
			elseif($media instanceof \Tak\Liveproto\Tl\Types\Other\UpdateNewEncryptedMessage or $media instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessage or $media instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedFile):
				return $this->download_secret_file($destination,$media,$progresscallback,$key,$iv,$transfer_kind);
			else:
				return $this->download_profile_photo($destination,$media,$big,$progresscallback,$key,$iv,$transfer_kind);
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
	protected function fetchCachedPhoto(#[Type('PhotoSize')] object $photoSize) : string {
		return match($photoSize->getClass()){
			'photoStrippedSize' => $this->get_stripped_thumbnail($photoSize->bytes),
			'photoPathSize' => $this->decode_vector_thumbnail($photoSize->bytes),
			'photoCachedSize' => $photoSize->bytes,
			default => throw new \InvalidArgumentException('Invalid photoSize for get cache of it !')
		};
	}
	protected function photoCachedIgnore(#[Vector(new Type('PhotoSize'))] array $photoSizes) : object {
		$photoSizes = array_filter($photoSizes,fn(object $photoSize) : bool => in_array($photoSize->getClass(),array('photoCachedSize','photoStrippedSize')) === false);
		if(empty($photoSizes)):
			throw new \InvalidArgumentException('There is no PhotoSize that does not require caching');
		endif;
		return $photoSizes;
	}
	private function getFileExtension(object | string $type) : ? string {
		if(is_object($type)):
			/** @deprecated */
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