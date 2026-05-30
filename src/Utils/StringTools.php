<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

abstract class StringTools {
	static public function diff(string $oldText,string $newText) : array {
		$old = self::splitGraphemes($oldText);
		$new = self::splitGraphemes($newText);
		$m = count($old);
		$n = count($new);
		$dp = array_fill(0,$m + 1,array_fill(0,$n + 1,0));
		for($i = $m - 1;$i >= 0;$i--):
			for($j = $n - 1;$j >= 0;$j--):
				if($old[$i] === $new[$j]):
					$dp[$i][$j] = $dp[$i + 1][$j + 1] + 1;
				else:
					$dp[$i][$j] = max($dp[$i + 1][$j],$dp[$i][$j + 1]);
				endif;
			endfor;
		endfor;
		$ops = array();
		$i = 0;
		$j = 0;
		while($i < $m or $j < $n):
			if($i < $m and $j < $n and $old[$i] === $new[$j]):
				$i++;
				$j++;
				continue;
			endif;
			$startNew = $j;
			$deleted = array();
			$inserted = array();
			while($i < $m or $j < $n):
				if($i < $m and $j < $n and $old[$i] === $new[$j]):
					break;
				elseif($i < $m and boolval($j === $n or $dp[$i + 1][$j] >= $dp[$i][$j + 1])):
					$deleted[] = $old[$i];
					$i++;
				elseif($j < $n):
					$inserted[] = $new[$j];
					$j++;
				else:
					break;
				endif;
			endwhile;
			$deletedText = implode($deleted);
			$insertedText = implode($inserted);
			$newPrefix = implode(array_slice($new,0,$startNew));
			$offset = self::offset($newText,strlen($newPrefix));
			$oldLength = self::length($deletedText);
			$newLength = self::length($insertedText);
			if($oldLength > 0 and $newLength > 0):
				$ops[] = [
					'type' => 'replace',
					'offset' => $offset,
					'length' => $newLength,
					'old' => $deletedText,
					'new' => $insertedText
				];
			elseif($oldLength > 0):
				$ops[] = [
					'type' => 'delete',
					'offset' => $offset,
					'length' => $oldLength,
					'old' => $deletedText
				];
			elseif($newLength > 0):
				$ops[] = [
					'type' => 'insert',
					'offset' => $offset,
					'length' => $newLength,
					'new' => $insertedText
				];
			endif;
		endwhile;
		return $ops;
	}
	static private function splitGraphemes(string $text) : array {
		if(empty($text)):
			return array();
		elseif(function_exists('grapheme_strlen') and function_exists('grapheme_substr')):
			return array_map(fn(int $i) : string => grapheme_substr($text,$i,0x1),range(0x0,grapheme_strlen($text) - 0x1));
		else:
			if(preg_match_all('/\X/u',$text,$matches)):
				return reset($matches);
			else:
				throw new \InvalidArgumentException('Invalid UTF-8 input');
			endif;
		endif;
	}
	static public function offset(string | array $text,int $byteOffset) : int {
		$text = is_array($text) ? array_values($text) : $text;
		$length = is_array($text) ? count($text) : strlen($text);
		$offset = 0;
		for($i = 0;$i < $length;$i++):
			$byte = ord($text[$i]);
			if(($byte & 0xc0) != 0x80):
				if($i >= $byteOffset):
					return $offset;
				else:
					$offset += intval($byte >= 0xf0) + 0x1;
				endif;
			endif;
		endfor;
		return $offset;
	}
	static public function length(string | array $text) : int {
		$text = is_array($text) ? array_values($text) : $text;
		$length = is_array($text) ? count($text) : strlen($text);
		$byteLength = 0;
		for($i = 0;$i < $length;$i++):
			$byte = ord($text[$i]);
			if(($byte & 0xc0) != 0x80):
				$byteLength += intval($byte >= 0xf0) + 0x1;
			endif;
		endfor;
		return $byteLength;
	}
	static public function substr(string | array $text,int $offset,? int $length = null) : string {
		$text = is_array($text) ? implode($text) : $text;
		return mb_convert_encoding(substr(mb_convert_encoding($text,'UTF-16'),$offset << 0x1,is_int($length) ? ($length << 0x1) : null),'UTF-8','UTF-16');
	}
	static public function strsplice(string | array $text,string | array $replace,int $offset,? int $length = null) : string {
		$text = is_array($text) ? implode($text) : $text;
		$replace = is_array($replace) ? implode($replace) : $replace;
		return mb_convert_encoding(substr_replace(mb_convert_encoding($text,'UTF-16'),mb_convert_encoding($replace,'UTF-16'),$offset << 0x1,is_int($length) ? ($length << 0x1) : null),'UTF-8','UTF-16');
	}
	static public function strsplit(string | array $text,int $length) : array {
		$text = is_array($text) ? implode($text) : $text;
		return array_map(fn(string $chunk) : string => mb_convert_encoding($chunk,'UTF-8','UTF-16'),str_split(mb_convert_encoding($text,'UTF-16'),$length << 0x1));
	}
}

?>