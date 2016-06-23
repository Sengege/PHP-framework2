<?php
error_reporting(E_ALL);

require '../Slim/Slim.php';
require 'prepend.php';
//include 'databaseConnect.php';

include 'functions.php';
include 'getModules.php';
include 'getGroups.php';
include 'getGroupInfo.php';
include 'addUser.php';
include 'login.php';
include 'logout.php';
include 'addGroup.php';
include 'joinGroup.php';
include 'activation.php';
include 'notConfirmed.php';
include 'updateStudent.php';
include 'studentData.php';
include 'updatemodule.php';
include 'groupChat.php';
include 'groupMeetings.php';
include 'getNotifications.php';
include 'createTag.php';
include 'getTags.php';

\Slim\Slim::registerAutoloader();

$app = new Slim\Slim(); // start it up and declare our routes

$app->get('/activate/:activation', 'activation');
$app->get('/notConfirmed/resend', 'nc_resendActivation');
$app->get('/notConfirmed/change/:email', 'nc_changeEmail');
$app->get('/notConfirmed/delete', 'nc_deleteAccount');

$app->get('/modules/:universityID', 'getModules');
$app->get('/groups/', 'getGroups');
$app->get('/groups/join/:groupID', 'joinGroup');
$app->get('/groups/:groupID/info', 'getGroupInfo');
$app->post('/groups/add', 'addGroup'); 
$app->post('/student/register/', 'addUser'); 
$app->post('/student/login/','login');
$app->get('/student/logout/','logOut');
$app->get('/student/register/validate/email', function () use ($app) {
    validateEmail($app->request()->get('email'));
});
$app->get('/student/register/validate/username', function () use ($app) {
    validateUsername($app->request()->get('username'));
});

// Routes for update Student Details
$app->post('/student/change/email','updateEmail');
$app->post('/student/change/username','updateUsername');
$app->post('/student/change/password','updatePassword');
$app->post('/student/change/personal','updatePersonal');
$app->get('/student/getAllData','getAllStudentData');
$app->post('/student/change/modules','updatemodule');
//$app->post('/student/change/addmodules', 'addmodules');

$app->get('/groups/messages/all/:groupID','getAllMessages');
$app->get('/groups/messages/check/:groupID/:lastMessageID','checkNewMessages');
$app->post('/groups/messages/add','addMessage');

$app->post('/groups/meetings/add','addMeeting');
$app->post('/groups/meetings/freeRooms','getFreeRooms');
$app->get('/groups/meetings/get/:groupID','getMeetings');
$app->get('/groups/meetings/attend/:meetingID','attendMeeting');
$app->get('/groups/meetings/cancelAttend/:meetingID','cancelAttendMeeting');

$app->get('/notifications','getNotifications');

$app->post('/tags/new', 'newTag');
$app->get('/tags/:Tag_categoryID', 'getTags');

$app->run();
?>