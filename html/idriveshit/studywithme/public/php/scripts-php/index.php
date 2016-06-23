<?php

require '../Slim/Slim.php';
include 'databaseConnect.php';
include 'userGroups.php';


\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/groups', 'userGroups');


$app->run();






?>