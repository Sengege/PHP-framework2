<?php

    function newTag() {
       global $db;
	   global $noerrors;
        if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
        $request = Slim\Slim::getInstance()->request();
    	$tagCat = $request->post('tagCat');
        $stmt = $db->prepare("SELECT * FROM `tag` t JOIN `tag_category` tc ON (t.tag_categoryID = tc.tag_categoryID) WHERE t.tag_categoryID = :category AND t.name_EN = :tagName");
    	$tagName = $request->post('tagName');
        
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
	   global $noerrors;
        if($noerrors <> 0)
	{
		echo json_encode(array("errorcode"=>$noerrors));
		return;
	}
        $stmt = $db->prepare("INSERT INTO `tag` (`tag_categoryID`,`name_CN`,`name_EN`) VALUES (:category, :nameCN, :nameEN)");
		$stmt->bindParam(':category', $tagCat);
        $stmt->bindParam(':nameCN', $tagName);
        $stmt->bindParam(':nameEN', $tagName);
        
        if ($stmt->execute()) {
          echo "successful";
        } else {
          echo "unsuccessful";
		  echo var_dump($stmt->errorInfo());
	}}
    

?>