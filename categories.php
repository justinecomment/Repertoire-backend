<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('categories.php');
    require_once('db.php');


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);  
        $sql = $connection->query("SELECT * FROM categorie");
         while ($result = $sql->fetch()){
                 $data[] = array("nom_categorie"=>$result['nom_categorie'],
                                 "id_categorie"=>$result['id_categorie']);
         }
         echo json_encode($data);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
       
        $_POST = json_decode(file_get_contents('php://input'), true);
        $categorie =  $_POST['categorieName'];

        if(!empty($_POST['categorieName'])) {
            $sql=$connection->prepare("insert into categorie (nom_categorie) values('$categorie')");
            $resultat = $sql->execute();

            echo json_encode($resultat);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
        
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $id = $_GET['id'];  
        $category = $_GET['category'];  
       
        $sql =$connection->prepare("UPDATE categorie SET nom_categorie='$category' WHERE id_categorie='$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }

    if  ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $_GET = json_decode(file_get_contents('php://input'), true);
        $id = $_GET ['id'];
        
        $sql =  $connection->prepare("DELETE FROM categorie WHERE id_categorie = '$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }


?>