<?php

declare(strict_types = 1);

error_reporting(E_ALL);

if(file_exists('vendor/autoload.php')){
	require_once 'vendor/autoload.php';
} elseif(file_exists('liveproto.php') === false){
	copy('https://installer.liveproto.dev/liveproto.php','liveproto.php');
	require_once 'liveproto.php';
} else {
	require_once 'liveproto.phar';
}

use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

use Tak\Liveproto\Filters\Filter;
use Tak\Liveproto\Filters\Filter\Command;

use Tak\Liveproto\Filters\Events\NewMessage;

use Tak\Liveproto\Filters\Interfaces\Incoming;
use Tak\Liveproto\Filters\Interfaces\IsPrivate;

use Tak\Liveproto\Enums\CommandType;

use Revolt\EventLoop;

$settings = new Settings();
$settings->setApiId(29784714);
$settings->setApiHash('143dfc3c92049c32fbc553de2e5fb8e4');
$settings->setDeviceModel('PC 64bit');
$settings->setSystemVersion('4.14.186');
$settings->setAppVersion('1.28.5');
$settings->setIPv6(false);
$settings->setHideLog(true);
$settings->setReceiveUpdates(false);

$client = new Client('gift_bot','sqlite',$settings);

function ArrToStr(array $array,mixed ...$values) : string {
	return sprintf(implode(chr(10),$array),...$values);
}

final class Handler {
	/*
	 * Optional , Place your ID or better yet your username in the array below
	 * Either an empty array , or containing an ID and username
	 */
	public array $peers = array('@TakNone');

	#[Filter(new NewMessage(new Command(start : [CommandType::SLASH,CommandType::DOT,CommandType::EXCLAMATION])))]
	public function start(Incoming & IsPrivate $update) : void {
		list($message,$entities) = $update->html('👋 Hello , <b>welcome</b> to the new <u>gift notification</u> bot 🤖 <spoiler>the bot developed with</spoiler> <a href = "https://t.me/LiveProto">LiveProto 🌱</a> !');
		$update->reply(message : $message,entities : $entities);
	}

	#[Filter(new NewMessage(new Command(me : CommandType::AT)))]
	public function addPeer(Incoming & IsPrivate $update) : void {
		$peerId = $update->getPeerId();
		if(in_array($peerId,$this->peers,true)){
			list($message,$entities) = $update->html('<italic>❗️ You are already on the list</italic>');
			$update->reply(message : $message,entities : $entities);
		} else {
			$this->peers []= $peerId;
			list($message,$entities) = $update->html('<quote>🎉 You have been added to the list of users I need to notify</quote>');
			$update->reply(message : $message,entities : $entities);
		}
	}
}

$handler = new Handler;

$client->addHandler($handler);

EventLoop::unreference(EventLoop::repeat(1.00,function() use($client,$handler) : void {
	static $hash = 0;
	static $hour = '<start>';
	if($client->isAuthorized() and $client->connected){
		if($hour !== date('H')){
			$hour = date('H');
			$message = '💡 I am online ! Time : '.date('H:i:s').' & Date : '.date('Y/n/j');
			$requests = array_map(fn(mixed $peer) : array => ['peer'=>$client->get_input_peer($peer),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX)],$handler->peers);
			$client->messages->sendMessageMultiple(...$requests,responses : false);
		}
		try {
			$starGifts = $client->payments->getStarGifts(hash : $hash);
			if($starGifts->getClass() === 'payments.starGifts'){
				if($hash === 0){
					$hash = $starGifts->hash;
				} else {
					$hash = $starGifts->hash;
					foreach($starGifts->gifts as $gift){
						if($gift->getClass() === 'starGift' and $gift->sold_out === false and $gift->limited === true){
							$message = ArrToStr(array(
								'📝 Gift title : %s',
								'🆔 Identifier of the gift : %d',
								'🌟 Price of the gift in Telegram Stars : %d',
								'❕ Require premium : %s',
								'♻️ Can upgrade gift : %s',
							),strval($gift->title ?: 'NONE'),$gift->id,$gift->stars,$gift->require_premium ? '✅' : '❌',$gift->can_upgrade ? '✅' : '❌');
							$requests = array_map(fn(mixed $peer) : array => ['peer'=>$client->get_input_peer($peer),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX)],$handler->peers);
							$client->messages->sendMessageMultiple(...$requests,responses : false);
						}
					}
				}
			}
		} catch(Throwable $e){
			error_log(strval($e));
		}
	}
}));

$client->start();

?>