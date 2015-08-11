<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IF_PHPMailer {
    public function IF_PHPMailer()
	{
        require_once(THIRD_PATH.'php_mailer/PHPMailerAutoload.php');
    }
}