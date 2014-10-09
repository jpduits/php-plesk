<?php

require_once("SplClassLoader.php");
$classLoader = new SplClassLoader('pmill\Plesk', '../src');
$classLoader->register();

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'email'=>'test1365695330@example.com',
	'password'=>'areallylongstringwithsomenumbers2',
);

$request = new \pmill\Plesk\UpdateEmailPassword($config, $params);
$info = $request->process();

var_dump($info);