<?php

    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: origin, x-requested-with, content-type');

 
    include_once('contacts.php');
    require_once('db.php');
   
    $con= mysqli_connect($HOST, $USER, $PASS, $DBNAME) or die(mysqli_connect_error());

    
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = mysqli_query($con,"select * from contacts");
    $data = array();

    while ($row = mysqli_fetch_array($result)) {
    $data[] = array("nom"=>$row['nom'],"prenom"=>$row['prenom'],"email"=>$row['email']);
    }
    echo json_encode($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    
    $data = json_decode(file_get_contents("php://input"));
    
   // @$nom = $request->nom;
   $nom = mysqli_real_escape_string($data->nom);
   $prenom = mysqli_real_escape_string($data->prenom);
   $email = mysqli_real_escape_string($data->email);

   mysql_query("INSERT INTO contacts (nom, prenom, email) VALUES ($nom, $prenom, $email)");
}


?>

