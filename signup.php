<?php
//Close earlier session
if(session_id() != "") {
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
    <title>Schedule Builder Sign Up<br/></title>
    <link type="text/css" rel="stylesheet" href="style/form.css">
    <script type="text/javascript" src="scripts/validate.js"></script>
</head>
<body>
    <div id="header">
        <h1>Schedule Builder</h1>
        <h2>Sign Up to Start Building Schedules!</h2>
    </div>
    <fieldset>
        <form action="processing/accountmanager.php" method="post" 
        name="signupform">
            <?php
            if($_GET['match'] == 'yes')
                echo "<p id='nomatcher'>This email is already 
                in use.</p>";
            ?>
            <p>Email</p>
            <p class="error" id="emailer">Email must be between 3 and 15 
            characters long</p>
            <p class="error" id="emailtypeer">Email must be of the form 
            example@example.com</p>
            <p><input type="text" name="email" value="example@example.com" 
            id="emailin"/></p>
            <p>Name</p>
            <p class="error" id="nameer">Name must be between 3 and 15 
            characters long</p>
            <p><input type="text" name="name" value="Example" 
            id="namein"/></p>
            <p>Password</p>
            <p class="error" id="passworder">Password must be between 3 and 15 
            characters long</p>
            <p><input type="password" name="password" value="******" 
            id="pwdin"/></p>
            <p><input type="submit" name="signupSubmit" value ="Sign Up!"/></p>
            <a href="login.php"><div id="button">Log In</div></a>
        </form>
    </fieldset>
</body>
</html>