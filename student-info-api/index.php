<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //Database Setup
    define("HOST", "localhost");
    define("DBUSER", "root");
    define("DBPASSWORD", "");
    define("DBNAME", "sample-dct");

    function connect(){
        $con = mysqli_connect(HOST, DBUSER, DBPASSWORD, DBNAME);
        
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $con;
    }
    function closeConnection($con){
        mysqli_close($con);
    }
    ////////////////////////////////////////////////////////////
    $method = $_SERVER['REQUEST_METHOD'];

    if($method == "GET"){
        $sql = "SELECT * FROM tblstudents";
        if(isset($_GET['id'])){
            $sql = "SELECT * FROM tblstudents WHERE id =" . $_GET['id'];
        }

        $connect = connect();
        
        $result = mysqli_query($connect, $sql);
    
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
                $data = $row;
            }
        } 
        else {
            $data = "0 results";
        }
        mysqli_free_result($result);
        closeConnection($connect);
        echo json_encode($data);
    }

    if($method == "POST"){
        $data = urldecode(file_get_contents('php://input'));
        $response = null;
        $value = json_decode($data, TRUE);

        $name = $value['name'];  
        $address = $value['address']; 
        $course = $value['course']; 

        $sql = "INSERT INTO tblstudents (name, address, course) VALUES ('$name', '$address','$course')";
    
        $connect = connect();
        if (mysqli_query($connect, $sql)) {
            $response =
            [
                "message" => "Student of " . $value['firstname'] . " ". $value['lastname'] . " was Added",
            ];
        } 
        else {
            $response =
            [
                "message" => "Error: " . $sql . "<br>" . mysqli_error($conn),
            ];
        }
        closeConnection($connect);
        echo json_encode($response);
    }

    if($method == "PUT"){
        $response = null;
        $sql = null;

        $data = urldecode(file_get_contents('php://input'));
        
        $value = json_decode($data, TRUE);

        if(isset($_GET['id'])){
            $name = $value['name'];  
            $address = $value['address']; 
            $course = $value['course']; 
            $sql = "UPDATE tblstudents SET name = '$name', address = '$address' , course = '$course' WHERE id = " . $_GET['id'];
        }
        else{
            die("Error ID");
        }
        
        $connect = connect();
        
        if (mysqli_query($connect, $sql)) {
            $message = "Record Update Successful";
        } 
        else {
            $message = "Error Updating record";
        }
        closeConnection($connect);
        echo json_encode($message);      
    }

    if($method == "DELETE"){
        $response = null;
        $sql = null;

        $data = urldecode(file_get_contents('php://input'));
        
        $value = json_decode($data, TRUE);

        if(isset($_GET['id'])){
            $sql = "DELETE FROM tblstudents WHERE id = " . $_GET['id'];
        }
        else{
            die("Error ID");
        }

        $connect = connect();
        
        if (mysqli_query($connect, $sql)) {
            $response =
            [
                "message" => "Student of " . $value['firstname'] . " ". $value['lastname'] . " was Deleted",
            ];
        } 
        else {
            $message = "Error Deleting record";
        }

        echo json_encode($response);
        closeConnection($connect);
        echo json_encode($response);      
    }


?>