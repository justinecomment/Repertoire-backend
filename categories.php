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
                 $data[] = array("nom_categories"=>$result['nom_categories'],
                                 "id_categories"=>$result['id_categories']);
         }
         echo json_encode($data);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
        $_POST = json_decode(file_get_contents('php://input'), true);
        $categorie =  $_POST['categorieName'];

        if(!empty($_POST['categorieName'])) {
            $ins_query=$connection->prepare("insert into categories (nom_categories) values('$categorie')");
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
       
        $sql =$connection->prepare("UPDATE categories SET nom_categories='$category' WHERE id_categories='$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }

    if  ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $_GET = json_decode(file_get_contents('php://input'), true);
        $id = $_GET ['id'];
        
        $sql =  $connection->prepare("DELETE FROM categories WHERE id_categories = '$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }


?>