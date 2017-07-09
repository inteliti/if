<?php

/* * ***************************************************
 * URLs, Redirect
 * *************************************************** */

//Carga uno o mas plugins ubicado en plugins/
function if_plugin($plg)
{
	if(!is_array($plg))
	{
		$plg = array($plg);
	}
	foreach($plg as $v)
	{
		include_once IF_PATH_PLUGINS_SERVER.$v."/_loader.php";
	}
}

//Carga un plugin ubicado en templates/<PLANTILLA_ACTIVA>/plugins/
function if_plugin_tmpl($plg)
{
	include_once IF_PATH_TMPL_SERVER."plugins/{$plg}/_loader.php";
}

//Para construir enlaces con URLs de CodeIgniter
//Ej de uso: <a href="<?= a('Usuario/details/122') ? >">Usuario #122</a>
function a($codeigniterUrl)
{
	return IF_PATH_INDEX_CLIENT . trim($codeigniterUrl, '/') . '/';
}

/* * ***************************************************
 * Objects, Arrays funtions
 * *************************************************** */

//Transforma un array de objetos a un array simple del tipo
//[$keyIndex => $valueIndex]
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

/* * ***************************************************
 * Debug function
 * *************************************************** */

function d($a)
{
	echo "<pre>";
	var_dump($a);
	echo"</pre>";
}
