<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

// DEFAULT MYSQL DATABASE 
// Determine database based on URI
$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, '/crm-v2/') !== false) {
	$db_details = [
		'HOST' => 'localhost',
		'USERNAME' => 'root',
		'PASSWORD' => '',
		'DATABASE' => 'crm_v2'
	];
} elseif (strpos($uri, '/crm-test/') !== false) {
	$db_details = [
		'HOST' => 'localhost',
		'USERNAME' => 'root',
		'PASSWORD' => '',
		'DATABASE' => 'crm-test-db'
	];
} else {
	$db_details = [
		'HOST' => 'localhost',
		'USERNAME' => 'root',
		'PASSWORD' => '',
		'DATABASE' => 'crm_v2'
	];
}

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $db_details['HOST'],
	'username' => $db_details['USERNAME'],
	'password' => $db_details['PASSWORD'],
	'database' => $db_details['DATABASE'],
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


// SECOND DATABASE = ORACLE
$username = 'apps';
$password = 'ap823core';
$database = 'ZPIL';
$conn = '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=10.10.2.248)
		(PORT=1521))(CONNECT_DATA=(SID=ZPIL)))';


$db['oracle'] = array(
	'dsn'	=> '',
	'hostname' => $conn,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'dbdriver' => 'oci8',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	// 'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => FALSE
);
