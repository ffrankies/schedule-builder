<?php
    
$email = $_POST['email'];
$password = $_POST['password'];

require("dbFunctions.php");
$con = connect();

//If logging in, making sure the email and password exist
if(isset($_POST["loginSubmit"])) {
    
    $result = $con->query("SELECT * 
                    FROM Users
                    WHERE Users.email = '$email' AND
                          Users.password = '$password'");
    
    if($result->num_rows == 0) {
        header("Location: ../login.php?match=no");
    }
    else {
        //Start session with said user
        session_start();
        $_SESSION["email"] = $email;
        
        //Get user's name from database for customized message
        $result = $con->query("SELECT Name 
                    FROM Users
                    WHERE Users.Email = '$email'");
        
        foreach($result as $row) 
            foreach($row as $key=>$value) 
                $name = $value; //Hopefully this works. Weird stuff happens with number of rows sometimes
        
        $_SESSION["name"] = $name;
        
        header("Location: ../select.php");
    }
    
}

//If signing up, making sure the email doesn't always exist
if(isset($_POST["signupSubmit"])) {
    
    $result = $con->query("SELECT *
                    FROM Users
                    WHERE Users.Email = '$email'");
    
    if(!$result)
        echo $con->error;
                    
    if($result->num_rows > 0) {
       header("Location: ../signup.php?match=yes");
    }
    else {
        $name = $_POST['name'];
        //Add account to database, redirect to login page
        $queryString = "INSERT INTO Users ( Email, Name, Password ) 
                        VALUES ( '$email', '$name', '$password' )";
                        
        if(!$con->query($queryString))
            echo "Failed to create account.";
        else
            header("Location: ../login.php");
    }
    
}
?>