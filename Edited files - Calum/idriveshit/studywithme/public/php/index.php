<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */

$app = new \Slim\Slim();

$app->container->singleton('db', function() {
    return new PDO('mysql: host=localhost; dbname=studywithme', '40100046', 'BLL7fJLQt3AWWnPQ');
});

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// http://stackoverflow.com/questions/6207286/backbone-js-how-to-use-with-php
// $app->response()->status(500);

//Get all users
$app->get('/groups',
    function() use ($app) {
        $conn = getConnection($app);
        $stmt = $conn->prepare("SELECT groups.groupID, groups.groupName 
            FROM membership 
            JOIN groups ON (membership.groupID = groups.groupID) 
            WHERE membership.memberID = 4000"
        );
        $stmt->execute();
        $groupsUserIsIn = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $conn->prepare("SELECT * FROM groups LIMIT 1"); //TEST - Fix this query eventually to show suggested groups, subjects should have broader categories
        $stmt2->execute();
        $suggestedGroups = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        $both = array();
        $both['suggested'] = $suggestedGroups;
        $both['groupsIn'] = $groupsUserIsIn;

        echo json_encode($both);
    }
);

$app->get('/groups/:id',
    function($id) use ($app) {
        $conn = $app->db;
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM groups WHERE groupID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $group = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($group);
    }
);

$app->get('/groups/:gid/messages', 
    function($gid){ 
        echo '{name: "max", age: 20}'; 
    }
);

$app->get('/groups/:gid/messages/:mid', 
    function($gid, $mid){
        $data = array();
        $data['gid'] = $gid;
        $data['mid'] = $mid;
        echo json_encode($data);
    }
);

function getConnection($app) {
    //Utility function - Puts DB in scope
    $conn = $app->db;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();