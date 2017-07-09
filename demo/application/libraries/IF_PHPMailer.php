<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IF_PHPMailer {
    public function __construct()
	{
        require_once(THIRD_PATH.'php_mailer/PHPMailerAutoload.php');
    }
}