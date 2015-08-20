<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*********************************************************
 * 
 * _If_Constants
 * 
 * constantes utiles usadas en el sistema
 * 
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización. 
 * 
 *********************************************************/

define('IF_VERSION', '1.0');

//timezone
date_default_timezone_set('America/Caracas');

//shortword
$C =& $this->config;

/*********************************************************
 * URLS PARA USAR LADO CLIENTE
 * Se pueden anadir mas si se necesitan 
 *********************************************************/
define('BASE_URL', $C->item('base_url'));

define('APP_URL', BASE_URL . 'application/');
// Apunta a application/index.php/ de codeigniter, ideal para armar links
define('INDEX_URL', BASE_URL . 'index.php/');
// Apunta a application/assets/
define('ASSETS_URL', APP_URL . 'assets/');
// Elementos compartidos entre las plantillas (de haberlos)
define('SHARED_URL', APP_URL . 'templates/_shared/');
// La plantilla usada se configura en config/config.php
define('TMPL_URL', APP_URL . 'templates/'.$C->item('tmpl').'/');
// Apunta a application/plugins/
define('PLUGINS_URL', APP_URL . 'plugins/');

/*********************************************************
 * PATHS PARA USAR LADO SERVER
 * Se pueden anadir mas si se necesitan
 *********************************************************/
define('VIEWS_PATH', APPPATH . 'views/');
define('ASSETS_PATH', APPPATH . 'assets/');
define('TMPL_PATH', APPPATH. 'templates/'.$C->item('tmpl').'/');
define('PLUGINS_PATH', APPPATH . 'plugins/');
define('THIRD_PATH', APPPATH . 'third_party/');

/*********************************************************
 * COLOCAR EN TRUE DURANTE PRODUCCION, FALSE EN DESPLIEGUE
 * Usar esta constante donde se necesite para DEBUG
 *********************************************************/
define('IF_PRODUCTION', 1);
error_reporting(IF_PRODUCTION ? E_ALL : 0); //errors solo en production