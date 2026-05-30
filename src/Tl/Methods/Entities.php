<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Utils\StringTools;

use DOMDocument;

use DOMNode;

use DOMText;

trait Entities {
	public function parse_diff(string $oldText,string $newText) : array {
		$diff = StringTools::diff($oldText,$newText);
		$entities = [];
		foreach($diff as $item):
			$entities []= match($item['type']){
				'replace' => $this->messageEntityDiffReplace(offset : $item['offset'],length : $item['length'],old_text : $item['old']),
				'delete' => $this->messageEntityDiffDelete(offset : $item['offset'],length : $item['length']),
				'insert' => $this->messageEntityDiffInsert(offset : $item['offset'],length : $item['length'])
			};
		endforeach;
		return $entities;
	}
	public function markdown(string $text) : array {
		$markdown = str_split($text);
		$signs = [
			chr(42) => [1,2],
			chr(95) => [1,2],
			chr(96) => [1,3],
			chr(34) => [1],
			chr(39) => [2],
			chr(124) => [2],
			chr(126) => [2],
		];
		$counter = 0;
		$entities = [];
		while(false !== ($message = current($markdown))):
			if(key_exists($message,$signs)):
				foreach(array_reverse($signs[$message]) as $possible):
					for($i = 1;$i < $possible;$i++):
						if(next($markdown) !== $message):
							for($j = 0;$j < $i;$j++) prev($markdown);
							continue 2;
						endif;
					endfor;
					$pos = stripos(implode($markdown),str_repeat($message,$possible),$counter + $possible);
					if($pos === false):
						for($j = 1;$j < $possible;$j++) prev($markdown);
					else:
						$length = $pos - $counter - $possible;
						$slice = array_slice($markdown,$counter,$length + ($possible * 2),true);
						for($first = 0;$first < $possible;$first++):
							$firstkey = array_key_first($slice);
							unset($slice[$firstkey]);
							unset($markdown[$firstkey]);
						endfor;
						for($last = 0;$last < $possible;$last++):
							$lastkey = array_key_last($slice);
							unset($slice[$lastkey]);
							unset($markdown[$lastkey]);
						endfor;
						$additional = array();
						if($message === chr(96) and $possible === 3):
							$newline = stripos(implode($slice),chr(10));
							if(intval($newline)):
								$language = array_slice($slice,0,$newline + 1,true);
								if($language !== $slice):
									foreach(array_keys($language) as $lankey):
										unset($slice[$lankey]);
										unset($markdown[$lankey]);
									endforeach;
									array_pop($language);
									$additional['language'] = implode($language);
								else:
									$additional['language'] = strval(null);
								endif;
							else:
								$additional['language'] = strval(null);
							endif;
						endif;
						$entities[] = [str_repeat($message,$possible) => ['offset'=>StringTools::offset($markdown,$counter),'length'=>StringTools::length($slice),'i'=>key($markdown),...$additional]];
						continue 2;
					endif;
				endforeach;
			endif;
			if($message === chr(91)):
				$pos = stripos(implode($markdown),chr(93).chr(40),$counter + 1);
				if($pos !== false):
					$url = stripos(implode($markdown),chr(41),$pos);
					if($url !== false):
						$lengthtext = $pos - $counter - 1;
						$slicetext = array_slice($markdown,$counter,$lengthtext + 2,true);
						$firstkey = array_key_first($slicetext);
						unset($slicetext[$firstkey]);
						unset($markdown[$firstkey]);
						$lastkey = array_key_last($slicetext);
						unset($slicetext[$lastkey]);
						unset($markdown[$lastkey]);
						$lengthurl = $url - $pos;
						$sliceurl = array_slice($markdown,$counter + $lengthtext,$lengthurl,true);
						$firstkey = array_key_first($sliceurl);
						unset($sliceurl[$firstkey]);
						unset($markdown[$firstkey]);
						$lastkey = array_key_last($sliceurl);
						unset($sliceurl[$lastkey]);
						unset($markdown[$lastkey]);
						$entities[] = [chr(91).chr(93).chr(40).chr(41) => ['offset'=>StringTools::offset($markdown,$counter),'length'=>StringTools::length($slicetext),'url'=>implode($sliceurl),'i'=>key($markdown)]];
						foreach(array_keys($sliceurl) as $del):
							unset($markdown[$del]);
						endforeach;
					endif;
				endif;
			endif;
			if($message === chr(92)):
				$key = key($markdown);
				$next = next($markdown);
				if(key_exists($next,$signs + array_fill_keys(array(chr(91)),null))):
					$entities[] = [$message => ['offset'=>StringTools::offset($markdown,$counter),'length'=>INF,'i'=>key($markdown)]];
					unset($markdown[$key]);
				else:
					prev($markdown);
				endif;
			endif;
			next($markdown);
			$counter++;
		endwhile;
		foreach($entities as $indexone => $entityone):
			foreach($entityone as $keyone => $valueone):
				foreach($entities as $indextwo => $entitytwo):
					foreach($entitytwo as $keytwo => $valuetwo):
						if($entityone !== $entitytwo):
							if($valueone['offset'] <= $valuetwo['offset'] and ($valueone['offset'] + $valueone['length']) > $valuetwo['offset'] and $valueone['i'] < $valuetwo['i']):
								$valueone['length'] = $entities[$indexone][$keyone]['length'] -= strlen($keytwo) - (isset($valuetwo['url']) ? 3 : 0);
							endif;
							/* I will add `and $valuetwo['end'] === true` to this condition for characters that have no ending character and are single like > quote */
							if($valueone['offset'] < ($valuetwo['offset'] + $valuetwo['length']) and ($valueone['offset'] + $valueone['length']) > ($valuetwo['offset'] + $valuetwo['length']) and $valueone['i'] < $valuetwo['i']):
								$entities[$indexone][$keyone]['length'] -= isset($valuetwo['url']) ? strlen($valuetwo['url']) + 3 : (isset($valuetwo['language']) ? strlen($valuetwo['language']) + 3 : strlen($keytwo));
							endif;
						endif;
					endforeach;
				endforeach;
			endforeach;
		endforeach;
		foreach($entities as $index => $entity):
			foreach($entity as $key => $value):
				if($value['length'] > 0 and $key !== chr(92)):
					$entity = match($key){
						'~~' => $this->messageEntityStrike(...),
						'__' =>  $this->messageEntityUnderline(...),
						'\'\'' , '"' => $this->messageEntityBlockquote(...),
						'*' , '**' => $this->messageEntityBold(...),
						'_' => $this->messageEntityItalic(...),
						'`' => $this->messageEntityCode(...),
						'||' => $this->messageEntitySpoiler(...),
						'```' => fn(int $offset,int $length) => $this->messageEntityPre(offset : $offset,length : $length,language : $value['language']),
						'[]()' => fn(int $offset,int $length) => $this->href($offset,$length,$value['url'])
					};
					$entities[$index] = $entity($value['offset'],$value['length']);
				else:
					unset($entities[$index]);
				endif;
			endforeach;
		endforeach;
		return array(implode($markdown),$entities);
	}
	public function markdown_escape(string $text) : string {
		$signs = [
			chr(42),
			chr(95),
			chr(96),
			chr(34),
			chr(39),
			chr(124),
			chr(126),
			chr(91)
		];
		$escaped = array_map(fn(string $character) : string => strval(chr(92).$character),$signs);
		return str_replace($signs,$escaped,$text);
	}
	public function html(string $text) : array {
		$dom = new DOMDocument();
		$text = preg_replace('/\<br(\s*)?\/?\>/i',chr(10),$text);
		$text = preg_replace('/<(q|quote|blockquote)\s+expandable>/','<$1 collapsed=\'true\'>',$text);
		$load = $dom->loadxml('<body>'.trim($text).'</body>');
		if($load):
			list($length,$message,$entities) = $this->dom($dom->getElementsByTagName('body')->item(0));
			return array($message,$entities);
		else:
			throw new \ParseError('Opening and ending tag mismatch : '.$text);
		endif;
	}
	private function dom(
		DOMNode | DOMText $node,
		int $offset = 0,
		? string $message = null,
		array $entities = array()
	) : array {
		if($node instanceof DOMText):
			$text = str_replace(['&lt;','&gt;','&amp;'],['<','>','&'],$node->wholeText);
			$message .= $text;
			return array(StringTools::length($text),$message,$entities);
		endif;
		$length = 0;
		$entity = match($node->nodeName){
			's' , 'strike' , 'del' => $this->messageEntityStrike(...),
			'u' , 'underline' , 'ins' =>  $this->messageEntityUnderline(...),
			'b' , 'bold' , 'strong' => $this->messageEntityBold(...),
			'i' , 'italic' , 'em' => $this->messageEntityItalic(...),
			'code' => $this->messageEntityCode(...),
			'spoiler' , 'tg-spoiler' => $this->messageEntitySpoiler(...),
			'span' => boolval($node->hasAttribute('class') and $node->getAttribute('class') === 'tg-spoiler') ? $this->messageEntitySpoiler(...) : null,
			'q' , 'quote' , 'blockquote' => fn(int $offset,int $length) => $this->messageEntityBlockquote(offset : $offset,length : $length,collapsed : $node->hasAttribute('collapsed') ? true : null),
			'pre' => fn(int $offset,int $length) => $this->messageEntityPre(offset : $offset,length : $length,language : ($code = $node->getElementsByTagName('code')->item(0) and $code->hasAttribute('class')) ? preg_replace('/^language-/',strval(null),$code->getAttribute('class')) : $node->getAttribute('language')),
			'tg-emoji' , 'emoji' => fn(int $offset,int $length) => $this->messageEntityCustomEmoji(offset : $offset,length : $length,document_id : intval($node->hasAttribute('emoji-id') ? $node->getAttribute('emoji-id') : $node->getAttribute('id'))),
			'tg-time' , 'time' => fn(int $offset,int $length) => boolval($node->hasAttribute('format') and preg_match('~^(?<format>r|w?[dD]?[tT]?)$~',$node->getAttribute('format'),$match)) ? $this->messageEntityFormattedDate(offset : $offset,length : $length,date : intval($node->hasAttribute('unix') ? $node->getAttribute('unix') : $node->getAttribute('date')),relative : str_contains($match['format'],'r'),day_of_week : str_contains($match['format'],'w'),short_date : str_contains($match['format'],'d'),long_date : str_contains($match['format'],'D'),short_time : str_contains($match['format'],'t'),long_time : str_contains($match['format'],'T')) : $this->messageEntityFormattedDate(offset : $offset,length : $length,date : intval($node->hasAttribute('unix') ? $node->getAttribute('unix') : $node->getAttribute('date'))),
			'a' => fn(int $offset,int $length) => $this->href($offset,$length,$node->getAttribute('href')),
			default => null
		};
		foreach($node->childNodes as $sub):
			list($len,$message,$entities) = $this->dom($sub,$offset + $length,$message,$entities);
			$length += $len;
		endforeach;
		if($entity !== null):
			$realLength = $length;
			for($x = strlen($message) - 1;$x >= 0;$x--):
				if(in_array($message[$x],array(chr(32),chr(13),chr(10)))):
					$realLength--;
				else:
					break;
				endif;
			endfor;
			if($realLength > 0):
				$entities []= $entity($offset,$realLength);
			endif;
		endif;
		return array($length,$message,$entities);
	}
	public function html_escape(string $text) : string {
		return str_replace(['&','<','>'],['&amp;','&lt;','&gt;'],$text);
	}
	private function href(int $offset,int $length,string $href) : object {
		if(preg_match('~^mention:(?<id>.+)~',$href,$matches) || preg_match('~^tg://user\\?id=(?<id>\d+)~',$href,$matches)):
			return $this->inputMessageEntityMentionName(offset : $offset,length : $length,user_id : $this->get_input_user(filter_var($matches['id'],FILTER_VALIDATE_INT) ? intval($matches['id']) : $matches['id']));
		elseif(preg_match('~^emoji:(?<id>\d+)$~',$href,$matches) || preg_match('~^tg://emoji\\?id=(?<id>\d+)~',$href,$matches)):
			return $this->messageEntityCustomEmoji(offset : $offset,length : $length,document_id : intval($matches['id']));
		elseif(preg_match('~^time:(?<unix>\d+)(?:&format:(?<format>r|w?[dD]?[tT]?))?$~',$href,$matches) || preg_match('~^tg://time\?unix=(?<unix>\d+)(?:&format=(?<format>r|w?[dD]?[tT]?))?$~',$href,$matches)):
			$format = strval(isset($matches['format']) ? $matches['format'] : null);
			return $this->messageEntityFormattedDate(offset : $offset,length : $length,date : intval($matches['unix']),relative : str_contains($format,'r'),day_of_week : str_contains($format,'w'),short_date : str_contains($format,'d'),long_date : str_contains($format,'D'),short_time : str_contains($format,'t'),long_time : str_contains($format,'T'));
		else:
			return $this->messageEntityTextUrl(offset : $offset,length : $length,url : $href);
		endif;
	}
	public function format_entities(string $text,array $entities) : array {
		foreach($entities as $i => $object):
			$entity = $entities[$i] = clone $object;
			$entity->text = StringTools::substr($text,$entity->offset,$entity->length);
			if(isset($entity->url)):
				$entity->open = static fn(mixed ...$args) : string | false => @file_get_contents($entity->url,...$args);
			endif;
		endforeach;
		return $entities;
	}
}

?>