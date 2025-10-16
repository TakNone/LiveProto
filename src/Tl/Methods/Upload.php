<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Crypto\Aes;

use function Amp\async;

use function Amp\File\openFile;

use function Amp\File\getSize;

use function Amp\File\isFile;

use ArrayIterator;

use InfiniteIterator;

trait Upload {
	public function upload_file(
		string $path,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : mixed {
		if(isFile($path)):
			$stream = openFile($path,'rb');
			$size = getSize($path);
			$partSizeKB = ($size < 0x3200000 ? 128 : ($size < 0xc800000 ? 256 : 512));
			$partSize = intval($partSizeKB * 1024);
			$partCount = intdiv($size + $partSize - 1,$partSize);
			$fileid = Helper::generateRandomLong();
			$isBig = ($size > 0xa00000);
			$progress = 0;
			$md5 = hash_init('md5');
			$connections = $this->getMediaConnections(dc_id : $this->load->dc,count : match(true){
				boolval($size >= 10 * 1024 * 1024 and $size < 25 * 1024 * 1024) => 2,
				boolval($size >= 25 * 1024 * 1024 and $size < 50 * 1024 * 1024) => 3,
				boolval($size >= 50 * 1024 * 1024 and $size < 100 * 1024 * 1024) => 4,
				default => null
			});
			Logging::log('Upload','A total of '.count($connections).' connections were received');
			$clients = new InfiniteIterator(new ArrayIterator($connections));
			Logging::log('Upload','Start uploading the '.basename($path).' file ...');
			for($requests = array(),$partIndex = 0;$partIndex < $partCount;$partIndex++):
				$part = $stream->read(length : $partSize);
				$isLastPart = boolval($partIndex === $partCount - 1);
				if(strlen($part) !== $partSize and $isLastPart === false):
					throw new \Exception('Read method isn\'t correct !');
				endif;
				if(is_null($key) === false and is_null($iv) === false):
					$part = Aes::encrypt($part,$key,$iv);
				endif;
				if($isBig):
					$requests []= ['file_id'=>$fileid,'file_part'=>$partIndex,'file_total_parts'=>$partCount,'bytes'=>$part];
				else:
					hash_update($md5,$part);
					$requests []= ['file_id'=>$fileid,'file_part'=>$partIndex,'bytes'=>$part];
				endif;
				if(count($requests) === $this->settings->getParallelUploads() || $isLastPart === true):
					$clients->next();
					if($isBig):
						$areSaved = $clients->current()->upload->saveBigFilePartMultiple(...$requests,responses : true);
					else:
						$areSaved = $clients->current()->upload->saveFilePartMultiple(...$requests,responses : true);
					endif;
					$requests = array();
					# if(false !== ($index = array_search(false,$areSaved,true))):
					if(in_array(false,$areSaved,true) === false):
						$percent = ($partIndex / $partCount) * 100;
						if(is_null($progresscallback) === false):
							if(async($progresscallback(...),$percent)->await() === false):
								Logging::log('Upload','Canceled !',E_WARNING);
								throw new \RuntimeException('Upload canceled !');
							endif;
						else:
							Logging::log('Upload',$percent.'%');
						endif;
					else:
						throw new \RuntimeException('Failed to upload file parts !');
					endif;
				endif;
			endfor;
			$stream->close();
			Logging::log('Upload','Finish uploading the '.basename($path).' file ...');
			if(is_null($key) === false and is_null($iv) === false):
				$hash = new Binary();
				$hash->write(md5($key.$iv,true));
				$fingerprint = $hash->readInt() ^ $hash->readInt();
				return $isBig ? $this->inputEncryptedFileBigUploaded(id : $fileid,parts : $partCount,key_fingerprint : $fingerprint) : $this->inputEncryptedFileUploaded(id : $fileid,parts : $partCount,md5_checksum : hash_final($md5,true),key_fingerprint : $fingerprint);
			else:
				return $isBig ? $this->inputFileBig(id : $fileid,parts : $partCount,name : $path) : $this->inputFile(id : $fileid,parts : $partCount,name : $path,md5_checksum : hash_final($md5,true));
			endif;
		else:
			throw new \Exception('File '.$path.' not found !');
		endif;
	}
	public function upload_secret_file(string $path,mixed ...$arguments) : array {
		$arguments += ['key'=>random_bytes(32),'iv'=>random_bytes(32)];
		return array($this->upload_file($path,...$arguments),$arguments['key'],$arguments['iv']);
	}
}

?>