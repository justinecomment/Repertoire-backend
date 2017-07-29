<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('contacts.php');
    require_once('db.php');
   
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
            
        if($_REQUEST){
            $searchvalue = $_GET['search'];
            $sql = $connection->query("SELECT * FROM contacts WHERE nom_contacts='$searchvalue'");
          
            while ($result = $sql->fetch()){
                 $data[] = array("nom_contacts"=>$result['nom'],
                                "prenom_contacts"=>$result['prenom'],
                                "email_contacts"=>$result['email'],
                                "id_contacts"=>$result['id']);
            }
             echo json_encode($data);
        }

        else{
            $connection = mysqli_connect($HOST, $USER, $PASS,$DBNAME); 
            $result = mysqli_query($connection,"select * from contacts");

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("nom_contacts"=>$row['nom_contacts'],
                                "prenom_contacts"=>$row['prenom_contacts'],
                                "email_contacts"=>$row['email_contacts'],
                                "id_contacts"=>$row['id_contacts']);
            }
            echo json_encode($data);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);   
        $_POST = json_decode(file_get_contents('php://input'), true);
       
        if(!empty($_POST['nom'])&& !empty($_POST['prenom']) && !empty($_POST['email'])) {
            $ins_query=$connection->prepare("insert into contacts (nom_contacts, prenom_contacts, email_contacts) values( :nom, :prenom, :email)");
            $ins_query->bindParam(':nom', $_POST['nom_contacts']);
            $ins_query->bindParam(':prenom', $_POST['prenom_contacts']);
            $ins_query->bindParam(':email', $_POST['email_contacts']);
            $ins_query->execute();

            $result = $connection->prepare("select * from contacts");
            $result->execute();
            echo json_encode($result->fetchAll());
        }
    }

    if  ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $_GET = json_decode(file_get_contents('php://input'), true);
        $id = $_GET ['id'];
        
        $sql =  $connection->prepare("DELETE FROM contacts WHERE id_contacts = '$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){

        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS); 
        $id = $_GET ['id'];
        $nom = $_GET ['nom'];
        $prenom = $_GET ['prenom'];
        $email = $_GET ['email'];

        $sql =$connection->prepare("UPDATE contacts SET nom_contacts='$nom', prenom_contacts='$prenom', email_contacts='$email' WHERE id_contacts='$id'");
        $result = $sql->execute();
        echo json_encode($result);
    }

?>
