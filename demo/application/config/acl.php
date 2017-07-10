<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['acl_source']				= 'database';

$config['acl_db_roles']				= 'if_roles';
$config['acl_db_acciones']			= 'if_acciones';
$config['acl_db_roles_acciones']	= 'if_roles_acciones';

/*
 * 
 * 
 * debe existir tabla roles, acciones y roles_acciones
 * 
$acl['model']	= array(
	'rol' => array(
		'persona' => 'view',
		'persona' => 'create',
		'persona' => 'edit',
		'persona' => 'remove',
	),
);
 * */
 
$config['acl_model'] = array();
