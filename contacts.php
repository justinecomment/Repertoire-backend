<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('contacts.php');
    require_once('db.php');
   
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = mysqli_connect($HOST, $USER, $PASS,$DBNAME); 

        $result = mysqli_query($connection,"select * from contacts");
        $data = array();

        while ($row = mysqli_fetch_array($result)) {
        $data[] = array("nom"=>$row['nom'],"prenom"=>$row['prenom'],"email"=>$row['email'], "id"=>$row['id']);
        }
        echo json_encode($data);
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!empty($_POST['nom'])&& !empty($_POST['prenom']) && !empty($_POST['email'])) {
            $ins_query=$connection->prepare("insert into contacts (nom, prenom, email) values( :nom, :prenom, :email)");
            $ins_query->bindParam(':nom', $_POST['nom']);
            $ins_query->bindParam(':prenom', $_POST['prenom']);
            $ins_query->bindParam(':email', $_POST['email']);
            $ins_query->execute();
        }

        $result = $connection->prepare("select * from contacts");
        $result->execute();
        echo json_encode($result->fetchAll());
    }

    if  ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $_GET = json_decode(file_get_contents('php://input'), true);
        $id = $_GET ['id'];
        var_dump($id);
        
        $sql =  $connection->prepare("DELETE FROM contacts WHERE id = '$id'");
        $result = $sql->execute();
        echo json_encode($result);
        
}



?>
