<?php 

    require_once('db.php');


    $username = strtolower($_POST['username']);
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $id = uniqid('U-', true);
    $encrypted_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $query = "INSERT INTO User VALUES (?, ?, ?, ?, ?)";

    $data = [$id, $fullname, $username, $email, $encrypted_password];

    $queryExecution = $db->prepare($query);


    try{
        $queryExecution->execute($data);
        header('location: ../../app/login.php');
    } catch (Exception $e){
        $checkDuplicateUsername = "SELECT COUNT(*) AS 'Count' FROM User WHERE username = '$username'";        
        $checkDuplicateEmail = "SELECT COUNT(*) AS 'Count' FROM User WHERE email = '$email'"; 

        // Cek duplikat username
        $checkDuplicateUsernameExecution = $db->query($checkDuplicateUsername);
        $fetchUsernameQuery =  $checkDuplicateUsernameExecution->fetch(PDO::FETCH_ASSOC);

        if ($fetchUsernameQuery['Count'] > 0){
            header('location: ../../app/register.php?err=1');            
            die();
        }
        
        // Cek duplikat email

        $checkDuplicateEmailExecution = $db->query($checkDuplicateEmail);
        $fetchEmaiQuery = $checkDuplicateEmailExecution->fetch(PDO::FETCH_ASSOC);        

        if ($fetchEmaiQuery['Count'] > 0){
    
            header('location: ../../app/register.php?err=2');            
            die();
        }
        
        header('location: ../../app/register.php?err=3');
    }

?>