<?php
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
		$inFormat = DATE_DEFAULT_FORMAT_NO_TIME, 
		$dbFormat = DATE_MYSQL_FORMAT
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
