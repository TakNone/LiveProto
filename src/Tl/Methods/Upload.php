<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Crypto\Aes;

use function Tak\Asyncio\async;

use function Tak\Asyncio\File\openFile;

use function Tak\Asyncio\File\getSize;

use function Tak\Asyncio\File\isFile;

use Generator;

use ArrayIterator;

use InfiniteIterator;

trait Upload {
	protected function perform_upload(
		mixed $source,
		int $size = -1,
		? int $dc_id = null,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		TransferKind $transfer_kind = TransferKind::FILE
	) : mixed {
		switch($transfer_kind):
			case TransferKind::FILE:
				return $this->upload_file($source,$dc_id,$progresscallback,$key,$iv);
			case TransferKind::STREAM:
				return $this->upload_chunks($size,$dc_id,$progresscallback,$key,$iv);
			case TransferKind::CALLBACK:
				return $this->upload_callback($source,$size,$dc_id,$progresscallback,$key,$iv);
			default:
				throw new \InvalidArgumentException('Transfer kind '.$transfer_kind->value.' is not yet supported !');
		endswitch;
	}
	public function upload_chunks(
		int $size = -1,
		? int $dc_id = null,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null,
		int $offset = 0,
		int $limit = 512
	) : Generator {
		$partSizeKB = ($size < 0xa00000 ? 128 : ($size < 0x6400000 ? 256 : 512));
		$limit = intval($size > 0 ? $partSizeKB : $limit) * 1024;
		$partCount = intval($size > 0 ? intdiv($size + $limit - 1,$limit) : -1);
		$fileId = Helper::generateRandomLong();
		$isBig = boolval($size <= 0 || $size > 0xa00000);
		$dc_id ??= $this->load->dc;
		$requests = array();
		$md5 = hash_init('md5');
		$pause = false;
		$connections = $this->getMediaConnections(dc_id : $dc_id,pfs : false,count : match(true){
			boolval($size < 10 * 1024 * 1024) => 1,
			boolval($size >= 10 * 1024 * 1024 and $size < 25 * 1024 * 1024) => 2,
			boolval($size >= 25 * 1024 * 1024 and $size < 50 * 1024 * 1024) => 3,
			boolval($size >= 50 * 1024 * 1024 and $size < 100 * 1024 * 1024) => 4,
			default => null
		});
		Logging::log('Upload','A total of '.count($connections).' connections were received');
		$clients = new InfiniteIterator(new ArrayIterator($connections));
		$start = microtime(true);
		while($pause === false):
			$part = yield $limit => $offset;
			if(is_null($part)):
				$pause = true;
				goto finished;
			endif;
			if(is_null($key) === false and is_null($iv) === false):
				$part = Aes::encrypt($part,$key,$iv);
			endif;
			if($isBig):
				$requests []= ['file_id'=>$fileId,'file_part'=>intval($offset / $limit),'file_total_parts'=>$partCount,'bytes'=>$partCount < 0 ? str_pad($part,$limit,chr(0),STR_PAD_RIGHT) : $part];
			else:
				hash_update($md5,$part);
				$requests []= ['file_id'=>$fileId,'file_part'=>intval($offset / $limit),'bytes'=>$part];
			endif;
			$offset += $limit;
			if(strlen($part) !== $limit || boolval($size <= $offset and $size > 0)):
				$pause = true;
			endif;
			if(count($requests) === $this->settings->getParallelUploads() || $pause):
				finished:
				$clients->next();
				if($isBig):
					$areSaved = $clients->current()->upload->saveBigFilePartMultiple(...$requests,responses : true);
				else:
					$areSaved = $clients->current()->upload->saveFilePartMultiple(...$requests,responses : true);
				endif;
				$requests = array();
				if(in_array(false,$areSaved,true) === false):
					if($size > 0 and empty($areSaved) === false):
						$percent = min(100,($offset / $size) * 100);
						if(is_null($progresscallback) === false):
							if(async($progresscallback(...),$percent)->await() === false):
								Logging::log('Upload','Canceled !',E_WARNING);
								throw new \RuntimeException('Upload canceled !');
							endif;
						else:
							Logging::log('Upload',$percent.'%');
						endif;
					endif;
				else:
					throw new \RuntimeException('Failed to upload file parts !');
				endif;
			endif;
		endwhile;
		$finish = microtime(true);
		Logging::log('Upload','Average upload speed : '.round(floatval($offset / ($finish - $start)) / 1024 / 1024,2).' Mb/s');
		$parts = intval($offset / $limit);
		$filename = strval('DC_'.$dc_id.'_size_'.$size.'_FILE_ID_'.$fileId);
		if(is_null($key) === false and is_null($iv) === false):
			$hash = new Binary();
			$hash->write(md5($key.$iv,true));
			$fingerprint = $hash->readInt() ^ $hash->readInt();
			return $isBig ? $this->inputEncryptedFileBigUploaded(id : $fileId,parts : $parts,key_fingerprint : $fingerprint) : $this->inputEncryptedFileUploaded(id : $fileId,parts : $parts,md5_checksum : hash_final($md5,true),key_fingerprint : $fingerprint);
		else:
			return $isBig ? $this->inputFileBig(id : $fileId,parts : $parts,name : $filename) : $this->inputFile(id : $fileId,parts : $parts,name : $filename,md5_checksum : hash_final($md5,true));
		endif;
	}
	public function upload_callback(
		callable $lambda,
		int $size = -1,
		? int $dc_id = null,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : object {
		$generator = $this->upload_chunks($size,$dc_id,$progresscallback,$key,$iv);
		while($generator->valid()):
			$buffer = call_user_func($lambda,$generator->value(),$generator->key());
			$generator->send($buffer);
		endwhile;
		return $generator->getReturn();
	}
	public function upload_file(
		string $path,
		? int $dc_id = null,
		? callable $progresscallback = null,
		? string $key = null,
		? string $iv = null
	) : object {
		if(isFile($path)):
			$size = getSize($path);
			$generator = $this->upload_chunks($size,$dc_id,$progresscallback,$key,$iv);
			$stream = openFile($path,'rb');
			Logging::log('Upload','Start uploading the '.basename($path).' file ...');
			while($generator->valid()):
				$buffer = $stream->read($generator->key());
				$generator->send($buffer);
			endwhile;
			$stream->close();
			Logging::log('Upload','Finish uploading the '.basename($path).' file ...');
			return $generator->getReturn();
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