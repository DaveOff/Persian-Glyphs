<?php
class Persian_Glyphs
{
    private $_glyphs;
	
    public function __construct()
    {
		$this->_glyphs = [
			'آ'	=> ['&#x0622', null, null, '&#xFE82'],
			'ا'	=> ['&#x0627', null, null, '&#xFE8E'],
			'ب'	=> ['&#x0628', '&#xFE91', '&#xFE92', '&#xFE90'],
			'ت'	=> ['&#x062A', '&#xFE97', '&#xFE98', '&#xFE96'],
			'ث'	=> ['&#x062B', '&#xFE9B', '&#xFE9C', '&#xFE9A'],
			'ج'	=> ['&#x062C', '&#xFE9F', '&#xFEA0', '&#xFE9E'],
			'ح'	=> ['&#x062D', '&#xFEA3', '&#xFEA4', '&#xFEA2'],
			'چ'	=> ['&#x0686', '&#xFB7C', '&#xFB7D', '&#xFB7B'],
			'خ'	=> ['&#x062E', '&#xFEA7', '&#xFEA8', '&#xFEA6'],
			'د'	=> ['&#x062F', null, null, '&#xFEAA'],
			'ذ'	=> ['&#x0630', null, null, '&#xFEAC'],
			'ر'	=> ['&#x0631', null, null, '&#xFEAE'],
			'ز'	=> ['&#x0632', null, null, '&#xFEB0'],
			'س'	=> ['&#x0633', '&#xFEB3', '&#xFEB4', '&#xFEB2'],
			'ش'	=> ['&#x0634', '&#xFEB7', '&#xFEB8', '&#xFEB6'],
			'ص'	=> ['&#x0635', '&#xFEBB', '&#xFEBC', '&#xFEBA'],
			'ض'	=> ['&#x0636', '&#xFEBF', '&#xFEC0', '&#xFEBE'],
			'ط'	=> ['&#x0637', '&#xFEC3', '&#xFEC4', '&#xFEC2'],
			'ظ'	=> ['&#x0638', '&#xFEC7', '&#xFEC8', '&#xFEC6'],
			'ع'	=> ['&#x0639', '&#xFECB', '&#xFECC', '&#xFECA'],
			'غ'	=> ['&#x063A', '&#xFECF', '&#xFED0', '&#xFECE'],
			'ک'	=> ['&#x06A9', '&#xFEDB', '&#xFEDC', '&#xFEDA'],
			'گ'	=> ['&#x06AF', '&#xFB94', '&#xFB95', '&#xFB93'],
			'ل'	=> ['&#x0644', '&#xFEDF', '&#xFEE0', '&#xFEDE'],
			'ن'	=> ['&#x0646', '&#xFEE7', '&#xFEE8', '&#xFEE6'],
			'ه'	=> ['&#x0647', '&#xFEEB', '&#xFEEC', '&#xFEEA'],
			'و'	=> ['&#x0648', null, null, '&#xFEEE'],
			'ف'	=> ['&#x0641', '&#xFED3', '&#xFED4', '&#xFED2'],
			'ق'	=> ['&#x0642', '&#xFED7', '&#xFED8', '&#xFED6'],
			'ی'	=> ['&#x06CC', '&#xFEF3', '&#xFEF4', '&#xFEF2'],
			'م'	=> ['&#x0645', '&#xFEE3', '&#xFEE4', '&#xFEE2'],
			'پ'	=> ['&#xFB56', '&#xFB58', '&#xFB59', '&#xFB57']
		];
	}
	
	protected function decode($text, $exclude = [])
    	{
		$table = array_flip(get_html_translation_table(HTML_ENTITIES));
		$table = array_map('utf8_encode', $table);
		$table['&apos;'] = "'";
		$newtable = array_diff($table, $exclude);
		$pieces = explode('&', $text);
		$text   = array_shift($pieces);
		foreach ($pieces as $piece) {
		    if ($piece[0] == '#') {
			if ($piece[1] == 'x') {
			    $one = '#x';
			} else {
			    $one = '#';
			}
		    } else {
			$one = '';
		    }
		    $end   = strpos($piece, ';');
		    $start = strlen($one);

		    $two   = substr($piece, $start, $end - $start);
		    $zero  = '&'.$one.$two.';';

		    $text .= $this->entitiesHelper($one, $two, $zero, $newtable, $exclude). mb_substr($piece, $end+1);
		}
		 return $text;
	}

	protected function entitiesHelper($prefix, $codepoint, $original, &$table, &$exclude)
	{
		if (!$prefix) {
		    if (isset($table[$original])) {
			return $table[$original];
		    } else {
			return $original;
		    }
		}
		if ($prefix == '#x') {
		    $codepoint = base_convert($codepoint, 16, 10);
		}
		if ($codepoint < 0x80) {
		    $str = chr($codepoint);
		} elseif ($codepoint < 0x800) {
		    $str = chr(0xC0 | ($codepoint >> 6)) . 
			   chr(0x80 | ($codepoint & 0x3F));
		} elseif ($codepoint < 0x10000) {
		    $str = chr(0xE0 | ($codepoint >> 12)) . 
			   chr(0x80 | (($codepoint >> 6) & 0x3F)) . 
			   chr(0x80 | ($codepoint & 0x3F));
		} elseif ($codepoint < 0x200000) {
		    $str = chr(0xF0 | ($codepoint >> 18)) . 
			   chr(0x80 | (($codepoint >> 12) & 0x3F)) . 
			   chr(0x80 | (($codepoint >> 6) & 0x3F)) . 
			   chr(0x80 | ($codepoint & 0x3F));
		}
		if (in_array($str, $exclude)) {
		    return $original;
		} else {
		    return $str;
		}
	    }

	protected function charCheck($char, $pos)
	{
		switch ($pos) {
			case 'begin':
				$pos = 1;
				break;
			case 'middle':
				$pos = 2;
				break;
			case 'end':
				$pos = 3;
				break;
		}
		return (isset($char[$pos])) ? true : false;
	}

	public function utf8Glyphs($str)
	{
		$output = [];
		$lastType= '';
		$textPara = explode(' ', $str);
		foreach($textPara as $text){
			$textArray = preg_split('//u', $text, null, PREG_SPLIT_NO_EMPTY);
			foreach($textArray as $k=>$v){
				if(isset($this->_glyphs[$v])) {
					//Begin
					if(!isset($textArray[$k-1])){
						if($this->charCheck($this->_glyphs[$v], 'begin') == false) {
							$output[]= $this->_glyphs[$v][0] . ';';
							$lastType = 'zero';
							continue;
						} else {
							$output[]= $this->_glyphs[$v][1] . ';';
							$lastType = 'begin';
							continue;
						}
					}
					//Middle
					else if(isset($textArray[$k-1]) && isset($textArray[$k+1])) {
						if($lastType == 'end' || $lastType == 'zero') {
							$output[]= isset($this->_glyphs[$v][1]) ? $this->_glyphs[$v][1] . ';' : $this->_glyphs[$v][0] . ';';
							$lastType = isset($this->_glyphs[$v][1]) ? 'begin' : 'zero';
							continue;
						}
						if($this->charCheck($this->_glyphs[$v], 'middle') == false) {
							if($this->_glyphs[$v][3]) {
								$output[]= $this->_glyphs[$v][3] . ';';
								$lastType = 'end';
								continue;
							} else {
								$output[]= $this->_glyphs[$v][2] . ';';
								$lastType = 'zero';
								continue;
							}

						} else {
							$output[]= $this->_glyphs[$v][2] . ';';
							$lastType = 'middle';
							continue;
						}
					}
					//End
					else if(isset($textArray[$k-1]) && !isset($textArray[$k+1])){
						if($lastType == 'middle' || $lastType == 'begin'){
							$output[]= $this->_glyphs[$v][3] . ';';
							$lastType = 'end';
							continue;
						} else {
							$output[]= $this->_glyphs[$v][0] . ';';
							$lastType = 'zero';
							continue;
						}
					}
				} else {
					$output[] = '&#32;';
					$typeEx = false;
				}
			}

			$output[] = '&#32;';
		}
		array_pop($output);
		return $this->decode(implode('', array_reverse($output)));
	}
}
