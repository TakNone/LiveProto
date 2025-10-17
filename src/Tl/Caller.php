<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Tl\Methods\Account;

use Tak\Liveproto\Tl\Methods\Auth;

use Tak\Liveproto\Tl\Methods\Buttons;

use Tak\Liveproto\Tl\Methods\Dialog;

use Tak\Liveproto\Tl\Methods\Download;

use Tak\Liveproto\Tl\Methods\Entities;

use Tak\Liveproto\Tl\Methods\FileId;

use Tak\Liveproto\Tl\Methods\Inline;

use Tak\Liveproto\Tl\Methods\Media;

use Tak\Liveproto\Tl\Methods\Messages;

use Tak\Liveproto\Tl\Methods\Peers;

use Tak\Liveproto\Tl\Methods\Users;

use Tak\Liveproto\Tl\Methods\Channels;

use Tak\Liveproto\Tl\Methods\SecretChat;

use Tak\Liveproto\Tl\Methods\Upload;

use Tak\Liveproto\Tl\Methods\Utilities;

use Tak\Liveproto\Errors\RpcError;

use Tak\Attributes\AttributesEngine;

use function Amp\async;

use function Amp\delay;

use function Amp\Future\await;

abstract class Caller {
	use AttributesEngine {
		__set as attrSet;
		__get as attrGet;
		__call as attrCall;
	}

	protected ? object $dhConfig = null;
	protected array $peersType = array();
	protected array $peersId = array();
	protected array $secretChats = array();
	protected array $connections = array();

	use Account;
	use Auth;
	use Buttons;
	use Dialog;
	use Download;
	use Entities;
	use FileId;
	use Inline;
	use Media;
	use Messages;
	use Peers;
	use Users;
	use Channels;
	use SecretChat;
	use Upload;
	use Utilities;

	public function __set(string $property,mixed $value) : void {
		if(property_exists($this,$property)):
			$this->attrSet($property,$value);
		else:
			throw new \Error('Property '.$property.' does not exist');
		endif;
	}
	public function __get(string $property) : mixed {
		if(property_exists($this,$property)):
			return $this->attrGet($property);
		else:
			return new Properties($this,$property);
		endif;
	}
	public function __call(string $name,array $arguments) : mixed {
		if(method_exists($this,$name)):
			return $this->attrCall($name,$arguments);
		else:
			$other = new Properties($this);
			return $other->$name(...$arguments);
		endif;
	}
	public function __invoke(string $request,array $arguments) : mixed {
		$split = explode(str_contains($request,chr(46)) ? chr(46) : chr(47),$request);
		if(count($split) === 2):
			$name = $split[true];
			$space = new Properties($this,$split[false]);
		elseif(count($split) === 1):
			$name = $split[false];
			$space = new Properties($this);
		else:
			throw new \Exception('Namespace ('.$request.') not found !');
		endif;
		return $space->$name(...$arguments);
	}
}

final class Properties {
	public function __construct(private readonly object $parent,private readonly string $property = 'other'){
	}
	public function __get(string $property) : mixed {
		if(property_exists($this->parent,$property)):
			$reflection = new \ReflectionClass($this->parent);
			$property = $reflection->getProperty($property);
			return $property->getValue($this->parent);
		else:
			# return new self($this->parent,$property);
			throw new \Exception('Undefined property: Client'.chr(58).chr(58).chr(36).$property);
		endif;
	}
	public function __call(string $name,array $arguments) : mixed {
		if(preg_match('~^(.+?)(?:_)?multiple$~i',$name,$match)):
			$name = $match[true];
			if(isset($arguments['responses'])):
				$responses = boolval($arguments['responses']);
				unset($arguments['responses']);
			else:
				$responses = false;
			endif;
			if(isset($arguments['queued'])):
				$queued = boolval($arguments['queued']);
				unset($arguments['queued']);
			else:
				$queued = false;
			endif;
			$processes = array();
			$lastMessageId = null;
			foreach($arguments as $i => $argument):
				if($queued === true):
					if(is_null($lastMessageId) === false):
						$argument += ['afterId'=>$lastMessageId];
					endif;
					$lastMessageId = $this->session->getNewMsgId();
					$argument += ['messageid'=>$lastMessageId];
				endif;
				if($responses === false):
					$argument += ['response'=>$responses];
				endif;
				$processes []= async(fn(string $method) : mixed => call_user_func($method,$name,$argument),__METHOD__);
			endforeach;
			$results = await($processes);
			ksort($results);
			return $results;
		endif;
		if($class = $this->createObject('Tak\\Liveproto\\Tl\\Functions\\'.ucfirst($this->property).'\\'.ucfirst($name))):
			$parameters = [
				'raw'=>[
					'func'=>is_bool(...),
					'default'=>false
				],
				'response'=>[
					'func'=>is_bool(...),
					'default'=>true
				],
				'timeout'=>[
					'func'=>is_int(...),
					'default'=>0
				],
				'floodwaitlimit'=>[
					'func'=>is_int(...),
					'default'=>0
				],
				'messageid'=>[
					'func'=>is_int(...),
					'default'=>null
				],
				'identifier'=>[
					'func'=>null,
					'default'=>null
				],
				'extra'=>[
					'func'=>null,
					'default'=>null
				]
			];
			$filtered = array();
			foreach($parameters as $key => $value):
				if(array_key_exists($key,$arguments)):
					$filtered[$key] = boolval(is_null($value['func']) or call_user_func($value['func'],$arguments[$key])) ? $arguments[$key] : $value['default'];
					unset($arguments[$key]);
				else:
					$filtered[$key] = $value['default'];
				endif;
			endforeach;
			extract($filtered);
			if(array_key_exists('takeout',$arguments)):
				if($arguments['takeout'] === true):
					unset($arguments['takeout']);
					$arguments['raw'] = true;
					$request = call_user_func(__METHOD__,$name,$arguments);
					return $this->parent->invokeWithTakeout($this->takeoutid,$request,...$filtered);
				else:
					unset($arguments['takeout']);
				endif;
			endif;
			if(array_key_exists('receiveUpdates',$arguments)):
				if($arguments['receiveUpdates'] === false):
					unset($arguments['receiveUpdates']);
					$arguments['raw'] = true;
					$request = call_user_func(__METHOD__,$name,$arguments);
					return $this->parent->invokeWithoutUpdates($request,...$filtered);
				else:
					unset($arguments['receiveUpdates']);
				endif;
			endif;
			if(array_key_exists('afterId',$arguments)):
				if(is_numeric($arguments['afterId'])):
					$afterId = intval($arguments['afterId']);
					unset($arguments['afterId']);
					$arguments['raw'] = true;
					$request = call_user_func(__METHOD__,$name,$arguments);
					return $this->parent->invokeAfterMsg($afterId,$request,...$filtered);
				else:
					unset($arguments['afterId']);
				endif;
			endif;
			if(array_key_exists('businessConnectionId',$arguments)):
				if(empty($arguments['businessConnectionId']) === false):
					$businessConnectionId = strval($arguments['businessConnectionId']);
					unset($arguments['businessConnectionId']);
					$arguments['raw'] = true;
					$request = call_user_func(__METHOD__,$name,$arguments);
					return $this->parent->invokeWithBusinessConnection($businessConnectionId,$request,...$filtered);
				else:
					unset($arguments['businessConnectionId']);
				endif;
			endif;
			$request = new $class($arguments);
			if($raw):
				return $request;
			else:
				$mtRequest = new MTRequest($request,messageId : $messageid,identifier : $identifier,timeout : $timeout);
				$this->sender->send($mtRequest);
				try {
					$result = $response ? $this->sender->receive($mtRequest) : new \stdClass;
				} catch(RpcError $error){
					$floodmax = max($this->settings->floodSleepThreshold,$floodwaitlimit);
					if($error->getCode() == 420 and $floodmax >= $error->getValue()):
						Logging::log('Caller','Waiting for '.$error->getValue().' seconds to resend the request due to a flood error',E_NOTICE);
						delay($error->getValue());
						$arguments['timeout'] = $timeout;
						$result = call_user_func(__METHOD__,$name,$arguments + $filtered);
					else:
						throw $error;
					endif;
				}
				if(is_null($extra) === false):
					$result->extra = $extra;
				endif;
				return $result;
			endif;
		elseif($class = $this->createObject('Tak\\Liveproto\\Tl\\Types\\'.ucfirst($this->property).'\\'.ucfirst($name))):
			return new $class($arguments);
		elseif(method_exists($this->parent,$name)):
			$reflection = new \ReflectionClass($this->parent);
			$method = $reflection->getMethod($name);
			return $method->invoke($this->parent,...$arguments);
		else:
			throw new \Exception('Call to undefined function '.$name.'()');
		endif;
	}
	private function createObject(string $class) : object | false {
		return class_exists($class) ? new $class : false;
	}
}

?>