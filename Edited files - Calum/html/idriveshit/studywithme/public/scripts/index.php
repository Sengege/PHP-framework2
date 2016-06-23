<?php

require '../Slim/Slim.php';
include 'databaseConnect.php';
include 'functions.php';
include 'getModules.php';
include 'getGroups.php';
include 'getGroupInfo.php';

\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/modules/:universityID', 'getModules');
$app->get('/groups/', 'getGroups');
$app->get('/groups/:groupID/info', 'getGroupInfo');






/*
$app->get('/hubtickets/login/:username/:password', 'login');
$app->post('/hubtickets/register', 'addUser');
$app->post('/hubtickets/purchase', 'purchase');
$app->get('/hubtickets/getPurchasedTickets/:userID', 'getPurchasedTickets');
$app->post('/hubtickets/removePurchasedTickets', 'removePurchasedTickets');

$app->get('/hubtickets/login/:username/:password', 'login');
$app->post('/hubtickets/register', 'addUser');
$app->post('/hubtickets/purchase', 'purchase');

$app->get('/hubtickets/resetDatabase', 'resetDatabase');
*/

$app->run();
?>