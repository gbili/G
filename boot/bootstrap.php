<?php
//get the config data
$config = new Zend_Config_Ini('Miner/conf/ini', 'staging');
//database initialization
$adapterName = $config->database->adapter;
$zendDbAdapter = 'Zend_Db_Adapter_' . $adapterName;
/*  $config['database']['connection']['host'];
	$config['database']['connection']['user'];
	$config['database']['connection']['pass'];
	$config['database']['connection']['dbname'];*/
$dbAdapter = new $zendDbAdapter($config->database->connection);

//store in registry
Zend_Registry::set('dbAdapter', $dbAdapter);
unset($adapterName, $dbAdapter, $config, $zendDbAdapter);


//launch application

//choose country normalizer that will be used for normalization of input countries
G_Country_Normalizer::$adapterName = 'Db';
//choose the source validator adapter
G_Source_Validator::$adapterName = 'Db';
//now the validator will make an instance of G_Source_Validator_Adapter_Db
//but it has to have a Db adapter that it will use to talk to Db(db)
//the Source_Validator_Adapter_Db_Adapter also needs an adapter to talk
//to Db
/*
 * Set the requestor databse adapter
 */
//create a pdo instance
$rDBMS = mb_strtolower($config->database->rdbms);
$dSN = "$rDBMS:host={$config->database->connection->host};dbname:{$config->database->connection->dbname}";
//throws exception if fails catched in wrapper
$adapter = new PDO($dSN,
				   $config->database->connection->user,
				   $config->database->connection->pass);
//all requestor extenders will have ->getAdapter() available
G_Db_Req_Abstract::setAdapter($adapter);
