<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Utils\Instance;

use Tak\Liveproto\Attributes\Type;

use Tak\Liveproto\Enums\FileType;

trait Media {
	public function inputify_media(Instance $media) : mixed {
		return match($media->getClass()){
			'photo' => $this->inputPhoto(id : $media->id,access_hash : $media->access_hash,file_reference : $media->file_reference),
			'photoEmpty' => $this->inputPhotoEmpty(),
			'geoPoint' => $this->inputGeoPoint(lat : $media->lat,long : $media->long,accuracy_radius : $media->accuracy_radius),
			'geoPointEmpty' => $this->inputGeoPointEmpty(),
			'document' => $this->inputDocument(id : $media->id,access_hash : $media->access_hash,file_reference : $media->file_reference),
			'documentEmpty' => $this->inputDocumentEmpty(),
			'webDocument' , 'webDocumentNoProxy' => $this->inputWebDocument(url : $media->url,size : $media->size,mime_type : $media->mime_type,attributes : $media->attributes),
			'game' => $this->inputGameID(id : $media->id,access_hash : $media->access_hash),
			default => throw new \RuntimeException('Unsupported Media type : '.$media->getClass())
		};
	}
	protected function get_input_media(#[Type('MessageMedia')] Instance $messageMedia) : Instance {
		return match($messageMedia->getClass()){
			# inputMediaPhoto#b3ba0635 flags:# spoiler:flags.1?true id:InputPhoto ttl_seconds:flags.0?int = InputMedia; #
			# messageMediaPhoto#695150d7 flags:# spoiler:flags.3?true photo:flags.0?Photo ttl_seconds:flags.2?int = MessageMedia; #
			'messageMediaPhoto' => is_null($messageMedia->photo) ? $this->inputMediaEmpty() : $this->inputMediaPhoto(
				id : $this->inputify_media($messageMedia->photo),
				spoiler : $messageMedia->spoiler,
				ttl_seconds : $messageMedia->ttl_seconds
			),
			# inputMediaGeoPoint#f9c44144 geo_point:InputGeoPoint = InputMedia; #
			# messageMediaGeo#56e0d474 geo:GeoPoint = MessageMedia; #
			'messageMediaGeo' => $this->inputMediaGeoPoint(
				geo_point : $this->inputify_media($messageMedia->geo)
			),
			# inputMediaContact#f8ab7dfb phone_number:string first_name:string last_name:string vcard:string = InputMedia; #
			# messageMediaContact#70322949 phone_number:string first_name:string last_name:string vcard:string user_id:long = MessageMedia; #
			'messageMediaContact' => $this->inputMediaContact(
				phone_number : $messageMedia->phone_number,
				first_name : $messageMedia->first_name,
				last_name : $messageMedia->last_name,
				vcard : $messageMedia->vcard
			),
			# inputMediaDocument#a8763ab5 flags:# spoiler:flags.2?true id:InputDocument video_cover:flags.3?InputPhoto video_timestamp:flags.4?int ttl_seconds:flags.0?int query:flags.1?string = InputMedia; #
			# messageMediaDocument#52d8ccd9 flags:# nopremium:flags.3?true spoiler:flags.4?true video:flags.6?true round:flags.7?true voice:flags.8?true document:flags.0?Document alt_documents:flags.5?Vector<Document> video_cover:flags.9?Photo video_timestamp:flags.10?int ttl_seconds:flags.2?int = MessageMedia; #
			'messageMediaDocument' => boolval(is_null($messageMedia->document) and is_null($messageMedia->alt_documents)) ? $this->inputMediaEmpty() : $this->inputMediaDocument(
				spoiler : $messageMedia->spoiler,
				id : isset($messageMedia->document) ? $this->inputify_media($messageMedia->document) : $this->inputify_media(current($messageMedia->alt_documents)),
				video_cover : isset($messageMedia->video_cover) ? $this->inputify_media($messageMedia->video_cover) : null,
				video_timestamp : $messageMedia->video_timestamp,
				ttl_seconds : $messageMedia->ttl_seconds,
				query : null
			),
			# inputMediaWebPage#c21b8849 flags:# force_large_media:flags.0?true force_small_media:flags.1?true optional:flags.2?true url:string = InputMedia; #
			# messageMediaWebPage#ddf10c3b flags:# force_large_media:flags.0?true force_small_media:flags.1?true manual:flags.3?true safe:flags.4?true webpage:WebPage = MessageMedia; #
			'messageMediaWebPage' => $this->inputMediaWebPage(
				force_large_media : $messageMedia->force_large_media,
				force_small_media : $messageMedia->force_small_media,
				optional : true,
				url : strval($messageMedia->webpage->url)
			),
			# inputMediaVenue#c13d1c11 geo_point:InputGeoPoint title:string address:string provider:string venue_id:string venue_type:string = InputMedia; #
			# messageMediaVenue#2ec0533f geo:GeoPoint title:string address:string provider:string venue_id:string venue_type:string = MessageMedia; #
			'messageMediaVenue' => $this->inputMediaVenue(
				geo_point : $this->inputify_media($messageMedia->geo),
				title : $messageMedia->title,
				address : $messageMedia->address,
				provider : $messageMedia->provider,
				venue_id : $messageMedia->venue_id,
				venue_type : $messageMedia->venue_type
			),
			# inputMediaGame#d33f43f3 id:InputGame = InputMedia; #
			# messageMediaGame#fdb19008 game:Game = MessageMedia; #
			'messageMediaGame' => $this->inputMediaGame(
				id : $this->inputify_media($messageMedia->game)
			),
			# inputMediaInvoice#405fef0d flags:# title:string description:string photo:flags.0?InputWebDocument invoice:Invoice payload:bytes provider:flags.3?string provider_data:DataJSON start_param:flags.1?string extended_media:flags.2?InputMedia = InputMedia; #
			# messageMediaInvoice#f6a548d3 flags:# shipping_address_requested:flags.1?true test:flags.3?true title:string description:string photo:flags.0?WebDocument receipt_msg_id:flags.2?int currency:string total_amount:long start_param:string extended_media:flags.4?MessageExtendedMedia = MessageMedia; #
			'messageMediaInvoice' => $this->inputMediaInvoice(
				title : $messageMedia->title,
				description : $messageMedia->description,
				photo : isset($messageMedia->photo) ? $this->inputify_media($messageMedia->photo) : null,
				invoice : $this->invoice(
					currency : $messageMedia->currency,
					prices : array(
						$this->labeledPrice(
							label : 'credits',
							amount : $messageMedia->total_amount,
						),
					),
				),
				payload : md5(serialize($messageMedia)),
				provider : null,
				provider_data : $this->dataJSON(
					data : json_encode(array()),
				),
				start_param : $messageMedia->start_param,
				extended_media : isset($messageMedia->extended_media->media) ? $this->get_input_media($messageMedia->extended_media->media) : null
			),
			# inputMediaGeoLive#971fa843 flags:# stopped:flags.0?true geo_point:InputGeoPoint heading:flags.2?int period:flags.1?int proximity_notification_radius:flags.3?int = InputMedia; #
			# messageMediaGeoLive#b940c666 flags:# geo:GeoPoint heading:flags.0?int period:int proximity_notification_radius:flags.1?int = MessageMedia; #
			'messageMediaGeoLive' => $this->inputMediaGeoLive(
				stopped : false,
				geo_point : $this->inputify_media($messageMedia->geo),
				heading : $messageMedia->heading,
				period : $messageMedia->period,
				proximity_notification_radius : $messageMedia->proximity_notification_radius
			),
			# inputMediaPoll#f94e5f1 flags:# poll:Poll correct_answers:flags.0?Vector<bytes> solution:flags.1?string solution_entities:flags.1?Vector<MessageEntity> = InputMedia; #
			# messageMediaPoll#4bd6e798 poll:Poll results:PollResults = MessageMedia; #
			'messageMediaPoll' => $this->inputMediaPoll(
				poll : $messageMedia->poll,
				correct_answers : array_column(array_filter($messageMedia->results->results,fn(object $pollAnswerVoters) : bool => boolval($pollAnswerVoters->correct)),'option'),
				solution : $messageMedia->results->solution,
				solution_entities : $messageMedia->results->solution_entities
			),
			# inputMediaDice#e66fbf7b emoticon:string = InputMedia; #
			# messageMediaDice#3f7ee58b value:int emoticon:string = MessageMedia; #
			'messageMediaDice' => $this->inputMediaDice(
				emoticon : $messageMedia->emoticon
			),
			# inputMediaStory#89fdd778 peer:InputPeer id:int = InputMedia; #
			# messageMediaStory#68cb6283 flags:# via_mention:flags.1?true peer:Peer id:int story:flags.0?StoryItem = MessageMedia; #
			'messageMediaStory' => $this->inputMediaStory(
				peer : $this->get_input_peer($messageMedia->peer),
				id : $messageMedia->id
			),
			# inputMediaPaidMedia#c4103386 flags:# stars_amount:long extended_media:Vector<InputMedia> payload:flags.0?string = InputMedia; #
			# messageMediaPaidMedia#a8852491 stars_amount:long extended_media:Vector<MessageExtendedMedia> = MessageMedia; #
			'messageMediaPaidMedia' => $this->inputMediaPaidMedia(
				stars_amount : $messageMedia->stars_amount,
				extended_media : array_map(fn(object $messageExtendedMedia) : mixed => $this->get_input_media($messageExtendedMedia->media),array_filter($messageMedia->extended_media,fn(object $messageExtendedMedia) : bool => isset($messageExtendedMedia->media))),
				payload : null
			),
			# inputMediaTodo#9fc55fde todo:TodoList = InputMedia; #
			# messageMediaToDo#8a53b014 flags:# todo:TodoList completions:flags.0?Vector<TodoCompletion> = MessageMedia; #
			'messageMediaToDo' => $this->inputMediaTodo(
				todo : $messageMedia->todo
			),
			# messageMediaUnsupported#9f84f49e = MessageMedia; #
			'messageMediaUnsupported' => $this->inputMediaEmpty(),
			# inputMediaDocumentExternal#779600f9 flags:# spoiler:flags.1?true url:string ttl_seconds:flags.0?int video_cover:flags.2?InputPhoto video_timestamp:flags.3?int = InputMedia; #
			# inputMediaPhotoExternal#e5bbfe1a flags:# spoiler:flags.1?true url:string ttl_seconds:flags.0?int = InputMedia; #
			default => throw new \RuntimeException('Unsupported MessageMedia type : '.$messageMedia->getClass())
		};
	}
	protected function get_message_media(#[Type('InputMedia')] Instance $inputMedia,string | int | null | object $peer = null,mixed ...$args) : Instance {
		$inputPeer = is_null($peer) ? ($this->is_bot() ? $this->inputPeerEmpty() : $this->inputPeerSelf()) : $this->get_input_peer($peer);
		return $this->messages->uploadMedia($inputPeer,$inputMedia,...$args);
	}
	public function get_input_media_uploaded(string $path,FileType $file_type,mixed ...$arguments) : mixed {
		$inputFile = $this->upload_file($path);
		return match($file_type){
			# inputMediaUploadedPhoto#1e287d04 flags:# spoiler:flags.2?true file:InputFile stickers:flags.0?Vector<InputDocument> ttl_seconds:flags.1?int = InputMedia; #
			FileType::PHOTO => $this->inputMediaUploadedPhoto($inputFile,...$arguments),
			# inputMediaUploadedDocument#37c9330 flags:# nosound_video:flags.3?true force_file:flags.4?true spoiler:flags.5?true file:InputFile thumb:flags.2?InputFile mime_type:string attributes:Vector<DocumentAttribute> stickers:flags.0?Vector<InputDocument> video_cover:flags.6?InputPhoto video_timestamp:flags.7?int ttl_seconds:flags.1?int = InputMedia; #
			default => $this->inputMediaUploadedDocument($inputFile,strval(mime_content_type($path)),...array_merge(['attributes'=>array($this->documentAttributeFilename(file_name : basename($path)))],$arguments))
		};
	}
	public function decode_vector_thumbnail(string $bytes) : string {
		$lookup = 'AACAAAAHAAALMAAAQASTAVAAAZaacaaaahaaalmaaaqastava.az0123456789-,';
		$path = 'M';
		$len = strlen($bytes);
		for($i = 0; $i < $len; $i++):
			$num = ord($bytes[$i]);
			if ($num >= 192):
				$idx = $num - 192;
				$path .= $lookup[$idx];
			else:
				if($num >= 128):
					$path .= ',';
				elseif($num >= 64):
					$path .= '-';
				endif;
				$path .= strval($num & 63);
			endif;
		endfor;
		$path .= 'z';
		$svg = <<<'SVG'
			<?xml version="1.0" encoding="utf-8"?>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
				<path d="%s"/>
			</svg>
		SVG;
		return sprintf($svg,$path);
	}
	public function get_stripped_thumbnail(string $stripped) : string {
		if(strlen($stripped) < 3 or substr($stripped,0,1) !== chr(1)):
			return $stripped;
		else:
			$header =
				"\xff\xd8\xff\xe0\x00\x10\x4a\x46\x49".
				"\x46\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00\xff\xdb\x00\x43\x00\x28\x1c".
				"\x1e\x23\x1e\x19\x28\x23\x21\x23\x2d\x2b\x28\x30\x3c\x64\x41\x3c\x37\x37".
				"\x3c\x7b\x58\x5d\x49\x64\x91\x80\x99\x96\x8f\x80\x8c\x8a\xa0\xb4\xe6\xc3".
				"\xa0\xaa\xda\xad\x8a\x8c\xc8\xff\xcb\xda\xee\xf5\xff\xff\xff\x9b\xc1\xff".
				"\xff\xff\xfa\xff\xe6\xfd\xff\xf8\xff\xdb\x00\x43\x01\x2b\x2d\x2d\x3c\x35".
				"\x3c\x76\x41\x41\x76\xf8\xa5\x8c\xa5\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8".
				"\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8".
				"\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8\xf8".
				"\xf8\xf8\xf8\xf8\xf8\xff\xc0\x00\x11\x08\x00\x00\x00\x00\x03\x01\x22\x00".
				"\x02\x11\x01\x03\x11\x01\xff\xc4\x00\x1f\x00\x00\x01\x05\x01\x01\x01\x01".
				"\x01\x01\x00\x00\x00\x00\x00\x00\x00\x00\x01\x02\x03\x04\x05\x06\x07\x08".
				"\x09\x0a\x0b\xff\xc4\x00\xb5\x10\x00\x02\x01\x03\x03\x02\x04\x03\x05\x05".
				"\x04\x04\x00\x00\x01\x7d\x01\x02\x03\x00\x04\x11\x05\x12\x21\x31\x41\x06".
				"\x13\x51\x61\x07\x22\x71\x14\x32\x81\x91\xa1\x08\x23\x42\xb1\xc1\x15\x52".
				"\xd1\xf0\x24\x33\x62\x72\x82\x09\x0a\x16\x17\x18\x19\x1a\x25\x26\x27\x28".
				"\x29\x2a\x34\x35\x36\x37\x38\x39\x3a\x43\x44\x45\x46\x47\x48\x49\x4a\x53".
				"\x54\x55\x56\x57\x58\x59\x5a\x63\x64\x65\x66\x67\x68\x69\x6a\x73\x74\x75".
				"\x76\x77\x78\x79\x7a\x83\x84\x85\x86\x87\x88\x89\x8a\x92\x93\x94\x95\x96".
				"\x97\x98\x99\x9a\xa2\xa3\xa4\xa5\xa6\xa7\xa8\xa9\xaa\xb2\xb3\xb4\xb5\xb6".
				"\xb7\xb8\xb9\xba\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xd2\xd3\xd4\xd5\xd6".
				"\xd7\xd8\xd9\xda\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xf1\xf2\xf3\xf4".
				"\xf5\xf6\xf7\xf8\xf9\xfa\xff\xc4\x00\x1f\x01\x00\x03\x01\x01\x01\x01\x01".
				"\x01\x01\x01\x01\x00\x00\x00\x00\x00\x00\x01\x02\x03\x04\x05\x06\x07\x08".
				"\x09\x0a\x0b\xff\xc4\x00\xb5\x11\x00\x02\x01\x02\x04\x04\x03\x04\x07\x05".
				"\x04\x04\x00\x01\x02\x77\x00\x01\x02\x03\x11\x04\x05\x21\x31\x06\x12\x41".
				"\x51\x07\x61\x71\x13\x22\x32\x81\x08\x14\x42\x91\xa1\xb1\xc1\x09\x23\x33".
				"\x52\xf0\x15\x62\x72\xd1\x0a\x16\x24\x34\xe1\x25\xf1\x17\x18\x19\x1a\x26".
				"\x27\x28\x29\x2a\x35\x36\x37\x38\x39\x3a\x43\x44\x45\x46\x47\x48\x49\x4a".
				"\x53\x54\x55\x56\x57\x58\x59\x5a\x63\x64\x65\x66\x67\x68\x69\x6a\x73\x74".
				"\x75\x76\x77\x78\x79\x7a\x82\x83\x84\x85\x86\x87\x88\x89\x8a\x92\x93\x94".
				"\x95\x96\x97\x98\x99\x9a\xa2\xa3\xa4\xa5\xa6\xa7\xa8\xa9\xaa\xb2\xb3\xb4".
				"\xb5\xb6\xb7\xb8\xb9\xba\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xd2\xd3\xd4".
				"\xd5\xd6\xd7\xd8\xd9\xda\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xf2\xf3\xf4".
				"\xf5\xf6\xf7\xf8\xf9\xfa\xff\xda\x00\x0c\x03\x01\x00\x02\x11\x03\x11\x00".
				"\x3f\x00";
			$footer = "\xff\xd9";
			$header[164] = $stripped[1];
			$header[166] = $stripped[2];
			return $header.substr($stripped,3).$footer;
		endif;
	}
}

?>