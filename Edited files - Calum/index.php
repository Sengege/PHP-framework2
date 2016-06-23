<?php
error_reporting(E_ALL);

require 'Slim/Slim.php';
require 'scripts/prepend.php';
include 'scripts/functions.php';

require_once('scripts/functions/functions.php');

//include 'databaseConnect.php';


\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/', 'home');
$app->get('/register', 'signup');
$app->get('/CN/', 'cn_home');
$app->get('/CN/register', 'cn_signup');
$app->get('/dashboard/group/:groupID', 'singleGroup');

function home() { require('html/en/homepage.php'); }
function signup() { require('html/en/signup.php'); }
function cn_home() { require('html/cn/homepage.php'); }
function cn_signup() { require('html/cn/signup.php'); }
function singleGroup($groupID) { require('html/en/singleGroup.php'); }
$app->run();
?>