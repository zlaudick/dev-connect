<?php
namespace Edu\Cnm\DevConnect\Test;
use Edu\Cnm\DevConnect\{Project, Tag};
// grab the project test parameters
require_once("DevConnectTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");
/**
 * Full PHPUnit test for the Project class
 *
 * This is a complete PHPUnit test of the Project class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Project
 * @author Marcelo Ibarra <mibarra5@cnm.edu>