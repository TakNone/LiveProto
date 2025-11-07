<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Tl\Caller;

use Tak\Liveproto\Tl\DocBuilder;

use Tak\Liveproto\Utils\Tools;

use Tak\Liveproto\Utils\Settings;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Crypto\Rsa;

use Tak\Liveproto\Ipc\SessionLocker;

use Tak\Liveproto\Ipc\SignalHandler;

use Tak\Liveproto\Database\Session;

use Tak\Liveproto\Database\Content;

use Tak\Liveproto\Handlers\Updates;

use Tak\Liveproto\Enums\Authentication;

use Tak\Liveproto\Enums\MTProtoKeepAlive;

use Tak\Liveproto\Filters\Filter;

use Revolt\EventLoop;

use Amp\Sync\LocalMutex;

use Stringable;

final class Client extends Caller implements Stringable {
	protected Session $session;
	protected Content $load;
	protected TcpTransport $transport;
	protected Sender $sender;
	protected LocalMutex $mutex;
	public readonly Updates $handler;
	protected ? object $locker;
	public ? object $mtproxy;
	public object $config;
	public array $dcOptions;
	public bool $connected = false;
	public int $takeoutid = 0;

	public function __construct(string | null $resourceName,string | null $storageDriver,public Settings $settings){
		if(is_null($resourceName) === false and empty($resourceName)):
			throw new \InvalidArgumentException('ResourceName cannot be an empty string value');
		endif;
		if(is_null($resourceName) !== is_null($storageDriver)):
			throw new \InvalidArgumentException('The `resourceName` and `storageDriver` parameters must both be null or both have string values');
		endif;
		new Logging($settings);
		$this->locker = in_array($storageDriver,['text',null]) ? null : ($settings->getHotReload() ? new SignalHandler($resourceName,$this) : new SessionLocker($resourceName));
		$this->session = new Session($resourceName,$storageDriver,$settings);
		$this->load = $this->session->load();
		$this->handler = new Updates($this,$this->session);
		if(is_int($this->load->api_id) and $this->load->api_id > 0):
			Logging::log('Client','I used the saved Api Id !');
		else:
			$this->load->api_id = $settings->getApiId();
		endif;
		if(is_string($this->load->api_hash) and empty($this->load->api_hash) === false):
			Logging::log('Client','I used the saved Api Hash !');
		else:
			$this->load->api_hash = $settings->getApiHash();
		endif;
		$this->mutex = new LocalMutex;
		$this->dcOptions = array(new \Tak\Liveproto\Tl\Types\Other\DcOption(['id'=>$this->load->dc,'ip_address'=>$this->load->ip,'port'=>$this->load->port,'client'=>$this,'expires_at'=>$this->load->expires_at]));
		$proxy = $this->settings->getProxy();
		$this->mtproxy = (is_null($proxy) === false and strtoupper($proxy['type']) === 'MTPROXY') ? $this->inputClientProxy(address : parse_url($proxy['address'],PHP_URL_HOST),port : parse_url($proxy['address'],PHP_URL_PORT)) : null;
	}
	public function connect(bool $reconnect = false,bool $reset = false,bool $origin = true) : void {
		if($reconnect):
			$this->disconnect();
		endif;
		Logging::log('Client','Connect to IP : '.$this->load->ip);
		$this->transport = new TcpTransport($this->load->ip,$this->load->port,$this->load->dc,$this->settings->protocol,$this->settings->proxy,$this->session->testmode,isset($this->load->media_only));
		if($this->load->auth_key instanceof \stdClass or $reset):
			$expires_in = intval($this->load->expires_at - time());
			$expires_in = intval($expires_in > 0 ? $expires_in : 0);
			$connect = new Handshake($this->transport,$this->session);
			list($this->load->auth_key,$this->load->time_offset,$this->load->salt,$this->load->salt_valid_until) = $connect->authentication($this->load->dc,$this->session->testmode,isset($this->load->media_only),$expires_in);
		endif;
		$this->sender = new Sender($this->transport,$this->session,$this->handler,$this->transport->protocol instanceof \Tak\Liveproto\Network\Protocols\Http ? MTProtoKeepAlive::HTTP_LONG_POLL : ($origin ? MTProtoKeepAlive::PING_PONG : MTProtoKeepAlive::NONE));
		$this->connected = true;
		if($origin) $this->init();
	}
	public function init() : void {
		if($this->connected):
			$getConfig = $this->help->getConfig(raw : true);
			$query = $this->initConnection(api_id : $this->load->api_id,device_model : $this->settings->getDeviceModel(),system_version : $this->settings->getSystemVersion(),app_version : $this->settings->getAppVersion(),system_lang_code : $this->settings->getSystemLangCode(),lang_pack : $this->settings->getLangPack(),lang_code : $this->settings->getLangCode(),proxy : $this->mtproxy,query : $getConfig,params : $this->settings->getParams(),raw : true);
			if($this->settings->receiveupdates === false):
				$query = $this->invokeWithoutUpdates(query : $query,raw : true);
			endif;
			$this->config = $this->invokeWithLayer(layer : $this->layer(),query : $query);
		else:
			throw new \RuntimeException('You are not connected yet to init connection !');
		endif;
	}
	public function setDC(string $ip,int $port,int $id) : void {
		list($this->load->ip,$this->load->port,$this->load->dc) = func_get_args();
		$this->dcOptions = array(new \Tak\Liveproto\Tl\Types\Other\DcOption(['id'=>$id,'ip_address'=>$ip,'port'=>$port,'client'=>$this,'expires_at'=>$this->load->expires_at]));
	}
	public function changeDC(int $dc_id) : void {
		Logging::log('Client','Try change dc ...');
		if(isset($this->config) === false):
			throw new \RuntimeException('To change the datacenter , `init` needs to be called and executed first !');
		endif;
		$lock = $this->mutex->acquire();
		try {
			foreach($this->config->dc_options as $dc):
				if($dc->ipv6 === $this->settings->ipv6 and $dc->id === $dc_id and $dc->media_only === false and $dc->tcpo_only === false and $dc->cdn === false):
					# $this->sender->destroyAuthKey(); #
					$this->removeDC($this->load->ip);
					$this->setDC($dc->ip_address,$dc->port,$dc->id);
					Logging::log('Client','New IP : '.$dc->ip_address);
					$this->connect(reconnect : true,reset : true);
					break;
				endif;
			endforeach;
		} catch(\Throwable $error){
			Logging::log('Client',$error->getMessage(),E_ERROR);
		} finally {
			EventLoop::queue($lock->release(...));
		}
	}
	public function switchDC(? int $dc_id = null,bool $cdn = false,bool $media = false,bool $tcpo = false,bool $next = false,bool $renew = false,int $expires_in = 0) : self {
		/*
		 * I needed to get new connections
		if($this->load->dc === $dc_id and $cdn === false and $next === false and $expires_in === 0):
			Logging::log('Client','There is no need to switch the data center , we use the same current data center');
			return $this;
		endif;
		 * So I didn't need this part
		 */
		Logging::log('Client','Try switch dc ...');
		if(isset($this->config) === false):
			throw new \RuntimeException('To switch the datacenter , `init` needs to be called and executed first !');
		endif;
		$lock = $this->mutex->acquire();
		try {
			foreach($this->config->dc_options as $dc):
				if($dc->ipv6 === $this->settings->ipv6 and (is_null($dc_id) or $dc->id === $dc_id) and ($media === true or $dc->media_only === $media) and ($tcpo === true or $dc->tcpo_only === $tcpo) and $dc->cdn === $cdn):
					if($next === false or ($next === true and in_array($dc->ip_address,array_column($this->dcOptions,'ip_address')) === false)):
						Logging::log('Client','Switch IP : '.$dc->ip_address);
						$is_authorized = boolval($expires_in > 0 or $dc->cdn === true) ?: $this->checkAuthorization($dc->id);
						if($is_authorized === false):
							$authorization = $this->auth->exportAuthorization(dc_id : $dc->id);
						endif;
						if($dc->cdn === true):
							Rsa::addCdn($this->help->getCdnConfig());
						endif;
						$expires_at = intval($expires_in > 0 ? time() + $expires_in : 0);
						$availableClients = $this->getAuthorizations(ip_address : $dc->ip_address,expires_at : $expires_at);
						if(empty($availableClients)):
							$this->dcOptions []= $dc;
							$dc->expires_at = $expires_at;
							$client = clone $this;
							$dc->client = $client;
						else:
							Logging::log('Client','I used old built clients ...');
							$dcOption = current($availableClients);
							$client = $dcOption->client;
							if($renew):
								$client->dcOptions []= $dcOption;
								$client = clone $client;
							endif;
						endif;
						if($is_authorized === false):
							$importAuthorization = $client->auth->importAuthorization(id : $authorization->id,bytes : $authorization->bytes,raw : true);
							$query = $client->initConnection(api_id : $client->load->api_id,device_model : $client->settings->devicemodel,system_version : $client->settings->systemversion,app_version : $client->settings->appversion,system_lang_code : $client->settings->systemlangcode,lang_pack : $client->settings->langpack,lang_code : $client->settings->langcode,proxy : $this->mtproxy,query : $importAuthorization,params : $this->settings->params,raw : true);
							if($client->receiveupdates === false):
								$query = $client->invokeWithoutUpdates(query : $query,raw : true);
							endif;
							$client->invokeWithLayer(layer : $client->layer(),query : $query);
							$client->connected = true;
						endif;
						return $client;
					endif;
				endif;
			endforeach;
		} catch(\Throwable $error){
			Logging::log('Client',$error->getMessage(),E_NOTICE);
		} finally {
			EventLoop::queue($lock->release(...));
		}
		throw new \Exception('There is a problem in creating the client for DC id '.$dc_id.' !');
	}
	public function getTemp(int $dc_id,int $expires_in) : self {
		$this->dcOptions = array_filter($this->dcOptions,fn(object $dcOption) : bool => $dcOption->expires_at === 0 || $dcOption->expires_at > time());
		$availableClients = array_filter($this->dcOptions,fn(object $dcOption) : bool => $dcOption->expires_at > 0 and $dcOption->id === $dc_id);
		if($expires_in > 0 || empty($availableClients)):
			Logging::log('Client','Try get temp ...');
			if($this->load->dc === $dc_id):
				$client = $this->switchDC(dc_id : $dc_id,expires_in : $expires_in);
				$this->sender->bindTempAuthKey(sender : $client->sender);
				$client->init();
				return $client;
			else:
				return $this->switchDC(dc_id : $dc_id)->getTemp($dc_id,$expires_in);
			endif;
		else:
			Logging::log('Client','I used the same old temp');
			return current($availableClients)->client;
		endif;
	}
	public function checkAuthorization(int $dc_id) : bool {
		return in_array($dc_id,array_column($this->dcOptions,'id'));
	}
	public function getAuthorizations(mixed ...$filters) : array {
		return array_filter($this->dcOptions,fn(object $dcOption) : bool => array_intersect_assoc($dcOption->toArray(),$filters) == $filters);
	}
	public function isAuthorized() : bool {
		return boolval($this->load->step === Authentication::LOGIN);
	}
	public function getStep() : Authentication {
		return $this->load->step;
	}
	public function removeDC(string $ip) : void {
		$this->dcOptions = array_filter($this->dcOptions,fn(object $dcOption) : bool => $dcOption->ip_address !== $ip);
	}
	public function layer(bool $secret = false) : int {
		return DocBuilder::layer($secret);
	}
	public function registerFilteredFunctions() : void {
		$functions = get_defined_functions();
		foreach($functions['user'] as $function):
			$reflection = new \ReflectionFunction($function);
			$attributes = $reflection->getAttributes(Filter::class); # flag : \ReflectionAttribute::IS_INSTANCEOF
			if(empty($attributes) === false):
				$this->addHandler($function);
			endif;
		endforeach;
	}
	public function addHandler(object | callable $callback,? string $unique = null,Filter ...$filters) : void {
		$this->handler->addEventHandler($callback,$unique,...$filters);
	}
	public function removeHandler(object | callable $callback,? string $unique = null) : void {
		$this->handler->removeEventHandler($callback,$unique);
	}
	public function fetchUpdate(array $updates,? callable $callback = null,float $timeout = 0) : object {
		return $this->handler->fetchOneUpdate($updates,$callback,$timeout);
	}
	public function start(bool $run_until_disconnected = true) : void {
		if($this->connected === true):
			$this->disconnect();
		endif;
		$lock = is_null($this->locker) ? null : $this->locker->tryLock();
		if($this->connected === false):
			$this->connect();
		endif;
		if(Tools::isCli()):
			if($this->load->step === Authentication::NEED_AUTHENTICATION):
				$input = Tools::readLine('Please enter your phone number ( Or your bot token , you can give it from @BotFather ) : ');
				if(str_contains($input,chr(58))):
					$this->sign_in(bot_token : $input);
				else:
					$this->send_code(phone_number : preg_replace('/[^\d]/',strval(null),$input));
				endif;
			endif;
			if($this->load->step === Authentication::NEED_CODE):
				$input = Tools::readLine('Please enter that code you received : ');
				try {
					$this->sign_in(code : $input);
				} catch(\Throwable $error){
					if($error->getMessage() === 'SESSION_PASSWORD_NEEDED'):
						Logging::echo('Your account has a password !');
					elseif($error->getMessage() === 'PHONE_CODE_INVALID'):
						Logging::echo('The phone code is invalid !');
					endif;
				}
			endif;
			if($this->load->step === Authentication::NEED_PASSWORD):
				$input = Tools::readLine('Please enter your account password : ');
				$this->sign_in(password : $input);
			endif;
			if($this->load->step === Authentication::LOGIN):
				Logging::echo('Your bot is now running...');
			else:
				$this->stop();
				Logging::echo('Cli login does not support the stage your account is logged into !');
				exit();
			endif;
		else:
			include(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'login.php');
		endif;
		$this->runInBackground();
		if(is_callable($lock)):
			$this->disconnect();
			Logging::log('Client','The start request has been queued for execution !',E_NOTICE);
			$lock();
			Logging::log('Client','Executing a request that was in the queue...',E_NOTICE);
			$this->connect();
		endif;
		$this->registerFilteredFunctions();
		if($this->settings->takeout):
			$this->takeoutid = $this->account->initTakeoutSession(...$this->settings->takeout)->id;
		endif;
		$bot = $this->is_bot();
		$this->handler->state(reset : $bot);
		if($this->settings->autoCachePeers):
			iterator_to_array($bot ? $this->get_difference() : $this->get_dialogs());
		endif;
		gc_collect_cycles();
		if($run_until_disconnected):
			EventLoop::run();
		endif;
	}
	public function stop() : void {
		if($this->takeoutid):
			$this->account->finishTakeoutSession();
		endif;
		if($this->connected):
			$this->disconnect();
		endif;
		if($this->locker):
			$this->locker->unlock();
		endif;
	}
	private function runInBackground() : void {
		if(Tools::isCli() === false):
			headers_sent() || http_response_code(200);
			if(is_callable('litespeed_finish_request')):
				litespeed_finish_request();
			elseif(is_callable('fastcgi_finish_request')):
				fastcgi_finish_request();
			else:
				ignore_user_abort(true);
				if(headers_sent() === false):
					header('Content-Type: text/html');
					header('Connection: close');
				endif;
				@ob_end_flush();
				@flush();
			endif;
		endif;
	}
	public function disconnect() : void {
		if($this->connected):
			Logging::log('Client','Disconnect !',E_WARNING);
			if(isset($this->sender,$this->transport)):
				$this->sender->close();
				$this->transport->close();
			endif;
			$this->connected = false;
			$this->load->save();
		else:
			Logging::log('Client','You are not connected yet to disconnect !',E_ERROR);
		endif;
	}
	public function __debugInfo() : array {
		return array(
			'config'=>isset($this->config) ? $this->config : new \stdClass,
			'dcOptions'=>$this->dcOptions,
			'MTProxy'=>$this->mtproxy,
			'deviceModel'=>$this->settings->deviceModel,
			'systemVersion'=>$this->settings->systemVersion,
			'appVersion'=>$this->settings->appVersion,
			'systemLangCode'=>$this->settings->systemLangCode,
			'langPack'=>$this->settings->langPack,
			'langCode'=>$this->settings->langCode,
			'hotReload'=>$this->settings->hotReload,
			'floodSleepThreshold'=>$this->settings->floodSleepThreshold,
			'receiveUpdates'=>$this->settings->receiveUpdates,
			'ipType'=>$this->settings->ipv6 ? 'ipv6' : 'ipv4',
			'takeout'=>$this->settings->takeout,
			'params'=>$this->settings->params,
			'connected'=>$this->connected
		);
	}
	private function __clone() : void {
		$this->session = clone $this->session;
		$this->load = $this->session->load();
		$dc = end($this->dcOptions);
		$reAuthentication = boolval($this->load->dc !== $dc->id || boolval($dc->expires_at > time()));
		$this->load->media_only = $dc->media_only ? true : null;
		$this->load->expires_at = $dc->expires_at;
		$this->setDC($dc->ip_address,$dc->port,$dc->id);
		$this->connect(reset : $reAuthentication,origin : false);
	}
	public function __destruct(){
		$this->disconnect();
	}
	public function __toString() : string {
		return $this->session->getStringSession();
	}
}

?>