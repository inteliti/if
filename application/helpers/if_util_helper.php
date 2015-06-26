<?php
/*****************************************************
 * Funciones útiles para ser usadas por modelos, vistas 
 * y controladores. Para manipulación de arrays,
 * objects, dates, l10n, i18n, etc.
 * 
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/

/*****************************************************
 * Objects, Arrays funtions
 *****************************************************/
function objArray2SimpleArray($objArray, $keyIndex, $valueIndex)
{
	$arr = array();
	foreach($objArray as $o)
		$arr[$o->$keyIndex] = $o->$valueIndex;
	return $arr;
}

function appendObjs($obj1, $obj2)
{
	return (object) array_merge((array) $obj1, (array) $obj2);
}


/*****************************************************
 * Debug function
 *****************************************************/
function d($a)
{
	echo "<pre>";var_dump($a);echo"</pre>";
}

/*****************************************************
 * Date functions
 *****************************************************/

/** CONSTANTES DE FORMATOS DE FECHA **/
define('DATETIME_MYSQL_FORMAT',			'Y-m-d H:i:s');
define('DATE_MYSQL_FORMAT',				'Y-m-d');
define('DATE_FULLCALENDAR_FORMAT',		'M d Y H:i:s');
define('DATE_DEFAULT_FORMAT',			'd/m/Y H:i:s');
define('DATE_DEFAULT_FORMAT_NO_SECONDS','d/m/Y H:i');
define('DATE_DEFAULT_FORMAT_NO_TIME',	'd/m/Y');

define('STRFTIME_DEFAULT_FORMAT',		'%A %d de %B del %Y');
define('STRFTIME_DAY_NUM',				'%d');
define('STRFTIME_DAY_STR',				'%A');
define('STRFTIME_MONTH_NUM',			'%m');
define('STRFTIME_MONTH_STR',			'%b');
define('STRFTIME_MONTH_STR_LONG',		'%B');
define('STRFTIME_YEAR_NUM',				'%Y');


function currentDate($outFormat = DATETIME_MYSQL_FORMAT)
{
	date_default_timezone_set('America/Caracas');//arreglarlo para posibles versiones con horarios distintos al de venezuela
	return date($outFormat);
}

/** Funcion para transformar fecha al formato de fecha de la BD **/
function dateToDB(
		$dateToDB, 
		$inFormat = DATE_DEFAULT_FORMAT, 
		$dbFormat = DATETIME_MYSQL_FORMAT
)
{
	$timestamp = getTimeStamp($inFormat, $dateToDB);
	return date($dbFormat, $timestamp);
}

/** Funcion para transformar fecha del formato proveniente de la BD **/
function dateFromDB(
		$dateFromDB,
		$outFormat = DATE_DEFAULT_FORMAT,
		$dbFormat = DATETIME_MYSQL_FORMAT
)
{
	$timestamp = getTimeStamp($dateFromDB,$dbFormat);
	return date($outFormat, $timestamp);
}

/** Funcion para obtener el timestamp de un string de una fecha **/
function getTimeStamp($datetimeStr, $inFormat)
{	
	$dt = date_parse_from_format($inFormat, $datetimeStr);
	return mktime(
			$dt['hour'], $dt['minute'], 0, $dt['month'], $dt['day'], $dt['year']
		);
}

function getDateES($date , $inFormat = DATETIME_MYSQL_FORMAT, $outFormat = "%A %d de %B del %Y")
{
	$timestamp = getTimeStamp($date,$inFormat);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		setlocale(LC_ALL,"esp");
	} else {
		setlocale(LC_ALL,"es_ES");
	}
	return strftime($outFormat,$timestamp);
}

/*
 * Retorna el url con el slash al final si este no lo tiene
 */
function endSlash($url)
{
	return substr($url, -1)!=='/' ? $url . '/' : $url;
}

/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 * 
 * sacadio de http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 400, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}

/*
 * Random Pasword
 * http://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
 *  */
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

/*
 * Append http if no exist in url
 * http://stackoverflow.com/questions/2762061/how-to-add-http-if-its-not-exists-in-the-url
 */
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
/*
 * Replace space with dashes
 * http://stackoverflow.com/questions/11330480/strip-php-variable-replace-white-spaces-with-dashes
 */
function seoUrl($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}