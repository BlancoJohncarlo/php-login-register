<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css"/>
    <title>Butterfly</title>
   </head>

   <body>
    
<div class="container">
        <?php

        if(isset($_POST["submit"])){
            $first_name = $_POST["firstname"];
            $last_name = $_POST["lastname"];
            $business_name = $_POST["business_name"];
            $address = $_POST["address"];
            $email = $_POST["email"];
            $contact_number = $_POST["contact"];
            $user_name = $_POST["username"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            $wild_fpn = $_POST["wild_fpn"];
            $wild_cpn = $_POST["wild_cpn"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors= array();
            if (empty($first_name) OR empty($last_name) OR empty($business_name) OR empty($address) OR empty($contact_number) OR empty($user_name) OR empty($password) OR empty($passwordRepeat) OR empty($wild_fpn) OR empty($wild_cpn)){
                array_push($errors,"All fields are required");
            }
            if (strlen($password)<= 8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE user_name = '$user_name'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount > 0) {
            array_push($errors,"username already exists!");
           }
            
            if (count($errors)>0){
                foreach($errors as $error){
                    echo"<div class='alert alert-danger'>$error</div>";
                }
            }else{

                $sql = "INSERT INTO users(first_name, last_name, business_name, address, email, contact_number, user_name,password,passwordRepeat, wild_fpn,wild_cpn) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                 $stmt = mysqli_stmt_init($conn);
                 $prepareStmt= mysqli_stmt_prepare($stmt, $sql);
            if($prepareStmt){
        mysqli_stmt_bind_param($stmt,"sssssssssss",$first_name, $last_name, $business_name, $address, $email, $contact_number,$user_name,$password,$passwordHash, $wild_fpn, $wild_cpn);
                    mysqli_stmt_execute($stmt);
                    echo"<div class='alert alert-success'> Your are registered successfully.</div>";
                 }
                 else{
                    die("Something went wrong");
                 }

            }
            
        }
        ?>

        <form action ="registration.php" method="post">
            <label class="reg-design">Registration</label>
            <div class="form-group">
                <input type="text" class="form-control" name="firstname" placeholder="First Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="lastname" placeholder="Last Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="business_name" placeholder="Business Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="contact" placeholder="Contact_Number">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="User Name">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password: morethan 8 characters">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="wild_fpn" placeholder="Wildlife Farm Permit Number">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="wild_cpn" placeholder="Wildlife Collector Permit Number">
            </div>

            <div class="form-btn">
                <input type="submit" class= "btn btn-primary" value="Register" name="submit">
            </div>

            
        
                
        </form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
</div>
       
   </body>




</html>