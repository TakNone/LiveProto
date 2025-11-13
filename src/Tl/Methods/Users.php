<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Attributes\Type;

trait Users {
	public function get_input_user(string | int | null | object $user) : mixed {
		$entity = $this->get_input_peer($user);
		$class = $entity->getClass();
		return match($class){
			'inputPeerEmpty' => $this->inputUserEmpty(),
			'inputPeerSelf' => $this->inputUserSelf(),
			'inputPeerUser' => $this->inputUser(user_id : $entity->user_id,access_hash : $entity->access_hash),
			default => throw new \InvalidArgumentException('This entity('.$class.') does not belong to a user !')
		};
	}
	protected function get_input_user_from_message(#[Type('Message')] object $message) : object {
		if(isset($message->from_id->user_id)):
			$inputPeer = $this->get_input_peer(peer : $message->peer_id);
			return $this->inputUserFromMessage(peer : $inputPeer,msg_id : $message->id,user_id : $message->from_id->user_id);
		else:
			throw new \InvalidArgumentException('Field `from_id` is missing or invalid in the message');
		endif;
	}
	public function get_me() : object {
		return $this->get_peer('me');
	}
	public function is_bot() : bool {
		return boolval($this->get_me()->bot);
	}
}

?>