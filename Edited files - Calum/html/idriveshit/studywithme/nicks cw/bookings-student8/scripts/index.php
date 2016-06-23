<?php

require '../Slim/Slim.php';
include 'databaseConnect.php';
include 'getPurchasedTickets.php';
include 'removePurchasedTickets.php';
include 'getSeats.php';
include 'login.php';
include 'addUser.php';
include 'purchase.php';
include 'resetDatabase.php';

\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/hubtickets/getSeats', 'getSeats');
$app->get('/hubtickets/login/:username/:password', 'login');
$app->post('/hubtickets/register', 'addUser');
$app->post('/hubtickets/purchase', 'purchase');
$app->get('/hubtickets/getPurchasedTickets/:userID', 'getPurchasedTickets');
$app->post('/hubtickets/removePurchasedTickets', 'removePurchasedTickets');

$app->get('/hubtickets/login/:username/:password', 'login');
$app->post('/hubtickets/register', 'addUser');
$app->post('/hubtickets/purchase', 'purchase');

$app->get('/hubtickets/resetDatabase', 'resetDatabase');

$app->run();






?>