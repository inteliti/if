<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');
/* * *******************************************************
 * 
 * _If_Constants
 * 
 * constantes utiles usadas en el sistema
 * 
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización. 
 * 
 * ATENCION: a partir de v1.2.3+ todas las constantes de if
 * deben comenzar con IF_
 * 
 * ******************************************************* */

define('IF_VERSION', '1.2.3');

//timezone
date_default_timezone_set('America/Caracas');

//shortword
$C = & $this->config;

/* * *******************************************************
 * PATHS PARA USAR LADO CLIENTE
 * Se pueden anadir mas si se necesitan 
 * ******************************************************* */
define('IF_PATH_BASE_CLIENT', $C->item('base_url'));
define('IF_PATH_APP_CLIENT', IF_PATH_BASE_CLIENT . 'application/');
define('IF_PATH_INDEX_CLIENT', IF_PATH_BASE_CLIENT . 'index.php/');
define('IF_PATH_ASSETS_CLIENT', IF_PATH_APP_CLIENT . 'assets/');
define('IF_PATH_SHARED_CLIENT', IF_PATH_APP_CLIENT . 'templates/_shared/');
define('IF_PATH_TMPL_CLIENT',
	IF_PATH_APP_CLIENT . 'templates/' . $C->item('tmpl') . '/');
define('IF_PATH_PLUGINS_CLIENT', IF_PATH_APP_CLIENT . 'plugins/');
define('IF_PATH_LIBS_CLIENT', IF_PATH_APP_CLIENT . 'libraries/');

//LEGACY (DEPRECATED)
define('BASE_URL', IF_PATH_BASE_CLIENT);
define('APP_URL', IF_PATH_APP_CLIENT);
define('INDEX_URL', IF_PATH_INDEX_CLIENT);
define('ASSETS_URL', IF_PATH_ASSETS_CLIENT);
define('SHARED_URL', IF_PATH_SHARED_CLIENT);
define('TMPL_URL', IF_PATH_TMPL_CLIENT);
define('PLUGINS_URL', IF_PATH_PLUGINS_CLIENT);
define('LIBS_URL', IF_PATH_LIBS_CLIENT);


/* * *******************************************************
 * PATHS PARA USAR LADO SERVER
 * Se pueden anadir mas si se necesitan
 * ******************************************************* */
define('IF_PATH_VIEWS_SERVER', APPPATH . 'views/');
define('IF_PATH_ASSETS_SERVER', APPPATH . 'assets/');
define('IF_PATH_TMPL_SERVER', APPPATH . 'templates/' . $C->item('tmpl') . '/');
define('IF_PATH_PLUGINS_SERVER',  APPPATH . 'plugins/');
define('IF_PATH_THIRD_SERVER', APPPATH . 'third_party/');

//LEGACY (DEPRECATED)
define('VIEWS_PATH', IF_PATH_VIEWS_SERVER);
define('ASSETS_PATH', IF_PATH_ASSETS_SERVER);
define('TMPL_PATH', IF_PATH_TMPL_SERVER);
define('PLUGINS_PATH', IF_PATH_PLUGINS_SERVER);
define('THIRD_PATH', IF_PATH_THIRD_SERVER);


/* * *******************************************************
 * COLOCAR EN TRUE DURANTE PRODUCCION, FALSE EN DESPLIEGUE
 * Usar esta constante donde se necesite para DEBUG
 * ******************************************************* */
define('IF_PRODUCTION', 1);
error_reporting(IF_PRODUCTION ? E_ALL : 0); //errors solo en production