<?php

require_once dirname(__DIR__, 2) . "../classes/autoload.php"; /** DIR not going up the directory correctly **/
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DevConnect\Project;

/**
 * API for the Message class
 *
 * @author Devon Beets <dbeetzz@gmail.com> based on code by Derek Mauldin
 **/