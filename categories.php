<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('categories.php');
    require_once('db.php');


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);  
        $sql = $connection->query("SELECT * FROM categories");
         while ($result = $sql->fetch()){
                 $data[] = array("nom"=>$result['nom'],
                                 "id"=>$result['id']);
         }
         echo json_encode($data);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
        $_POST = json_decode(file_get_contents('php://input'), true);
        $categorie =  $_POST['categorieName'];

        if(!empty($_POST['categorieName'])) {
            $ins_query=$connection->prepare("insert into categories (nom) values('$categorie')");
            $ins_query->execute();

            $result = $connection->prepare("select * from categories");
            $result->execute();
            echo json_encode($result->fetchAll());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
        
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $id = $_GET['id'];  
        $category = $_GET['category'];  
       
        $sql =$connection->prepare("UPDATE categories SET nom='$category' WHERE id='$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }

?>