<?php

require_once dirname(__DIR__, 2) . "../classes/autoload.php"; /** DIR not going up the directory correctly **/
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DevConnect\Message;

/**
 * API for the Message class
 *
 * @author
 **/