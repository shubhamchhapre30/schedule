<?php 

require_once('Autoloader.php');
OAuth2\Autoloader::register();

$dsn      = 'mysql:dbname=schedullo_demo;host=localhost';
$username = 'root';
$password = '';



$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$server = new OAuth2\Server($storage);
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); 
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
//$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
?>