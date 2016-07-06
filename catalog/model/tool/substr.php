<?php
class ModelToolSubstr extends Model {
	public function substr($str, $length, $minword = 3){
		$sub = '';
		$len = 0;
		foreach (explode(' ', $str) as $word)
		{
			$part = (($sub != '') ? ' ' : '') . $word;
			$sub .= $part;
			$len += strlen($part);
			if (strlen($word) > $minword && strlen($sub) >= $length)
			{
			  break;
			}
		 }
		return str_replace('&nbsp;',' ',$sub . (($len < strlen($str)) ? '...' : ''));
	}
}
?>