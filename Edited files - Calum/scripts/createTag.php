<?php
// Tester:
/*
    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>'; 
    echo '<button onclick="testNewTag()">Test newTag() function</button>
    <script>
      function testNewTag() {
        $.post("https://web.igp.noel.me.uk/scripts/tags/new", {"tagCat": 2, "tagName": "C# Programming"}, function(resp) {
          console.log(resp);
        }, "JSON");
      }
    </script>';
*/

    function newTag() {
        global $db;
        
        $request = Slim\Slim::getInstance()->request();
    	$tagCat = $request->post('tagCat');
    	$tagName = $request->post('tagName');
        
        $stmt = $db->prepare("SELECT * FROM `Tag` t JOIN `Tag_category` tc ON (t.Tag_categoryID = tc.Tag_categoryID) WHERE t.Tag_categoryID = :category AND t.name_EN = :tagName");
        $stmt->bindParam(':category', $tagCat);
        $stmt->bindParam(':tagName', $tagName);
        $stmt->execute();
        $returned = $stmt->rowCount();
        
        if ($returned == 0) {
          insertNewTag($tagCat, $tagName);
        } else {
          echo "tag already exists";
        }
    }


    function insertNewTag($tagCat, $tagName) {
        global $db;
        
        $stmt = $db->prepare("INSERT INTO `Tag` (`Tag_categoryID`,`name_CN`,`name_EN`) VALUES (:category, :nameCN, :nameEN)");
        $stmt->bindParam(':category', $tagCat);
        $stmt->bindParam(':nameCN', $tagName);
        $stmt->bindParam(':nameEN', $tagName);
        
        if ($stmt->execute()) {
          echo "successful";
        } else {
          echo "unsuccessful";
        }
    }

?>