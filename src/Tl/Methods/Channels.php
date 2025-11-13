<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Attributes\Type;

trait Channels {
	public function get_input_channel(string | int | null | object $channel) : mixed {
		$entity = $this->get_input_peer($channel);
		$class = $entity->getClass();
		return match($class){
			'inputPeerEmpty' => $this->inputChannelEmpty(),
			'inputPeerChannel' => $this->inputChannel(channel_id : $entity->channel_id,access_hash : $entity->access_hash),
			default => throw new \InvalidArgumentException('This entity('.$class.') does not belong to a channel !')
		};
	}
	protected function get_input_channel_from_message(#[Type('Message')] object $message) : object {
		if(isset($message->from_id->channel_id)):
			$inputPeer = $this->get_input_peer(peer : $message->peer_id);
			return $this->inputChannelFromMessage(peer : $inputPeer,msg_id : $message->id,channel_id : $message->from_id->channel_id);
		else:
			throw new \InvalidArgumentException('Field `from_id` is missing or invalid in the message');
		endif;
	}
}

?>