<?php
//Start a session
session_start();
//If user session is in progress
if( session_id() != "" && isset($_SESSION["user"]) )
    header("Location: select.php");
else //Else, go to login screen
    header("Location: login.php");
?>