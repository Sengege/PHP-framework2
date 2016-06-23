<?php
error_reporting(E_ALL);  //开启错误输出选项

require 'Slim/Slim.php';                        //包含Slim框架
require 'scripts/prepend.php';
include 'scripts/functions.php';

require_once('scripts/functions/functions.php');

//include 'databaseConnect.php';


\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/', 'home');
$app->get('/register', 'signup');
$app->get('/EN/', 'en_home');
$app->get('/EN/register', 'en_signup');
$app->get('/dashboard/group/:groupID', 'singleGroup');
$app->get('/dashboard/user/:userName', 'singleUser');

$app->get('/resetPassword/:hashcode', function ($hashcode) use ($app) { require 'html/en/resetPassword.php'; });
$app->get('/terms', function () use ($app) { require 'html/cn/termsandconditions.php'; });
$app->notFound( function() { require 'html/cn/404.php'; });

function home() { require('html/cn/homepage.php'); }
function signup() { require('html/cn/signup.php'); }
function en_home() { require('html/en/homepage.php'); }
function en_signup() { require('html/en/signup.php'); }
function singleGroup($groupID) { global $student; if($student->userExists())	{require('html/cn/singleGroup.php'); } else { header('Location: /'); die(); } }
function singleUser($userName) { global $student; if($student->userExists())	{require('html/cn/singleUser.php'); } else { header('Location: /'); die(); } }
$app->run();
?>