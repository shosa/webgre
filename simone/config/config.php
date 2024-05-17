<?php

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_FOLDER', 'htdocs');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
 */

define('DB_HOST', "31.11.39.126");
define('DB_USER', "Sql1740525");
define('DB_PASSWORD', "W49Zya2v5W!");
define('DB_NAME', "Sql1740525_5");
// Replace these values with your actual database credentials
$servername = "31.11.39.126";
$username = "Sql1740525";
$password = "W49Zya2v5W!";
$dbname = "Sql1740525_5";
/**
 * Get instance of DB object
 */
function getDbInstance()
{
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}