<?php
if(isset($_GET["action"])) {
    session_start();
    unset($_SESSION["name"]);
    unset($_SESSION["email"]);
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Schedule Builder Log In</title>
    <link type="text/css" rel="stylesheet" href="style/form.css"/>
    <link type="text/css" rel="stylesheet" href="style/animate.css"/>
    <script type="text/javascript" src="scripts/validate.js"></script>
</head>
<body>
    <div id="header">
        <h1>Schedule Builder</h1>
        <h2>Log In to Start Building Schedules</h2>
    </div>
    <fieldset>
        <form action="processing/accountmanager.php" method="post" 
        name="loginform">
            <?php
            if($_GET['match'] == 'no')
                echo "<p id='nomatcher'>Email and password combination does 
                not exist.<br/> Please try again, or sign up.</p>"
            ?>
            <p>Email</p>
            <p class="error" id="emailer">Email must be between 3 and 15 
            characters long</p>
            <p class="error" id="emailtypeer">Email must be of the form 
            example@example.com</p>
            <p><input type="text" name="email" value="example@example.com" 
            id="emailin"/>
            <p>Password</p>
            <p class="error" id="passworder">Password must be between 3 and 15 
            characters long</p>
            <p><input type="password" name="password" value="mypasswrd" 
            id="pwdin"/></p> 
            <p><input type="submit" name="loginSubmit" value ="Log In"/></p>
            <a href="signup.php"><div id="button">Sign Up!</div></a>
        </form>
    </fieldset>
</body>
</html>