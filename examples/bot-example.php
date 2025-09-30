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
use Tak\Liveproto\Filters\Filter\Regex;
use Tak\Liveproto\Filters\Filter\Command;

use Tak\Liveproto\Filters\Events\NewMessage;
use Tak\Liveproto\Filters\Events\CallbackQuery;
use Tak\Liveproto\Filters\Events\InlineQuery;
use Tak\Liveproto\Filters\Events\ChosenInlineResult;
use Tak\Liveproto\Filters\Events\NewJoinRequest;

use Tak\Liveproto\Filters\Interfaces\Incoming;
use Tak\Liveproto\Filters\Interfaces\NotMessage;
use Tak\Liveproto\Filters\Interfaces\IsPrivate;
use Tak\Liveproto\Filters\Interfaces\IsSelf;
use Tak\Liveproto\Filters\Interfaces\Inline;

use Tak\Liveproto\Enums\CommandType;

use function Amp\delay;

$settings = new Settings();
$settings->setApiId(21724);
$settings->setApiHash('3e0cb5efcd52300aec5994fdfc5bdc16');
$settings->setHideLog(false);
$settings->setReceiveUpdates(false);

#[Filter(new NewMessage(new Command('start')))]
function start(Incoming & IsPrivate $update) : void {
	list($message,$entities) = $update->markdown('👋 **__Hello__** , _welcome to ||the bot developed with||_ [LiveProto](https://t.me/LiveProtoChat) !');

	$replymarkup = $update->replyInlineMarkup(rows : array(
		$update->keyboardButtonRow(buttons : array(
			$update->keyboardButtonCallback(text : 'Callback button',data : 'test callback'),$update->keyboardButtonUrl(text : 'Url button',url : 'https://telegram.org')
		)),
		$update->keyboardButtonRow(buttons : array(
			$update->keyboardButtonSwitchInline(text : 'Switch button',query : 'switch query')
		)),
	));

	$update->reply(message : $message,entities : $entities,reply_markup : $replymarkup);
}

#[Filter(new NewMessage(new Command(react : CommandType::EXCLAMATION,reaction : ['@','/','.'])))]
function react(Incoming & IsPrivate $update) : void {
	$update->reaction('❤️');
}

#[Filter(new ChosenInlineResult())]
function choseninlines(Inline $update) : void {
	delay(3);
	$update->edit(invert_media : true);
}

#[Filter(new NewJoinRequest)]
function approver(object $update) : void {
	$update->hideRequest(approved : true);
}

#[Filter(new InlineQuery(new Regex('~^switch\s(?<sth>.+)$~')))]
function inlines(IsSelf | IsPrivate $update) : void {
	$sth = $update->regex->matched['sth'];
	$me = $update->get_me();
	/*
	Or you can do that...
	$me = $update->getClient()->get_me();
	*/
	list($message,$entities) = $update->html('😉 Your input : <q>'.htmlspecialchars($sth,ENT_HTML5).'</q>');

	$replymarkup = $update->replyInlineMarkup(rows : array(
		$update->keyboardButtonRow(buttons : array(
			$update->keyboardButtonCallback(text : 'Hi',data : '/Hello World'),$update->keyboardButtonUrl(text : 'Go to bot',url : 'https://t.me/'.$me->username)
		)),
		$update->keyboardButtonRow(buttons : array(
			$update->keyboardButtonSwitchInline(text : 'Switch button',query : 'switch query',same_peer : true)
		)),
	));

	$results = array(
		$update->inputBotInlineResult(
			id : 'first',
			type : 'article',
			title : 'Test One',
			description : 'Hello World !',
			send_message : $update->inputBotInlineMessageText(message : $message,entities : $entities,reply_markup : $replymarkup)
		),
		$update->inputBotInlineResult(
			id : 'second',
			type : 'article',
			title : 'Test Two',
			description : 'Bye World !',
			send_message : $update->inputBotInlineMessageText(message : $message,entities : $entities)
		)
	);

	$update->answerInline(results : $results,cache : 0,switch_text : 'Start Bot');
}

#[Filter(new CallbackQuery(new Command('Hello')))]
#[Filter(new CallbackQuery(new Regex('~(.+)\scallback$~')))]
function callbacks(IsPrivate | Inline $update) : void {
	if(array_key_exists('command',$update->regex->matched) and $update->regex->matched['command'] === 'Hello' and $update->regex->matched['parameter'] === 'World'){
		$update->answerCallback(cache : 10,message : 'Hello buddy 🙃',alert : true);
	} else {
		$me = $update->get_me();
		$update->answerCallback(cache : 0,url : 't.me/'.$me->username.'?start=xxx');
	}
}

#[Filter]
function vardump(Incoming | NotMessage $update) : void {
	var_dump($update);
}

$client = new Client('test-bot','string',$settings);

$client->connect();

try {
	if($client->isAuthorized() === false){
		$client->sign_in(bot_token : '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
	}
	var_dump($client->get_me());
} catch(Throwable $e){
	var_dump($e);
}

$client->start();

?>
