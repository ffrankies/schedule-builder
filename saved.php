<?php
require("processing/dbFunctions.php");
session_start();
if(session_id() == '' || !isset($_SESSION["name"]))
    header("Location: login.php");
else {
    $name = $_SESSION["name"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Builder Generated Schedules</title>
    <link type="text/css" rel="stylesheet" href="style/saved.css"/>
    <link type="text/css" rel="stylesheet" href="style/animate.css"/>
    <script type="text/javascript" src="scripts/saved.js"></script>
</head>
<body>
    <div id="header">
        <h1>Schedule Builder</h1>
        <h2>Saved Schedules</h2>
    </div>
    
    <div id="schedules"></div>
    
    <a href="../show.php"><div class="link">Generated Schedules</div></a>
    <a href="../select.php"><div class="link">Make a Selection</div></a>
    <a href="../processing/logout.php"><div class="link">Log Out</div></a>
</body>
</html>