<?php
require("processing/dbFunctions.php");
session_start();
if(session_id() == '' || !isset($_SESSION["name"]))
    header("Location: login.php");
else {
    $name = $_SESSION["name"];
    //$superuser = $_SESSION["superuser"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Schedule Builder Class Selection</title>
    <link type="text/css" rel="stylesheet" href="style/select.css"/>
    <script type="text/javascript" src="scripts/getclasses.js"></script>
    <!--I don't think I need to validate anything here-->
    <script type="text/javascript" src="scripts/validate.js"></script>
</head>
<body>
    <h1>Schedule Builder</h1>
    <h2>Class Selection</h2>
    <h3><?php echo $name ?>, select the classes you'd like to add to your schedule.</h3>
    <div id="table">
        <div id="departments">
        <?php
        //Get all departments and put each in a div
        $con = connect();
        $depts = getDepartments($con);
        foreach($depts as $row) {
            echo "<div class='department'>$row</div>";
        }
        ?>
        </div>
        <div id="classes">
        <!--Classes will be added here dynamically-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        <!--<div class="class">Placeholder to test css</div>-->
        </div>
        <!-- Another script to add an action to each department when clicked -->
        <!-- When clicked, run a query to get all classes in department -->
        <!-- Classes entered into nect column, from which a single class can be picked and added to
        selectedclasses div -->
        <!-- A class should only be able to be added once -->
    </div>
    <h3 id="instruction">Click on a Class to Remove it From the Selected List</h3>
    <div id="selectedclasses">
    <!--Users's selected classes will appear here-->
    </div>
    <div id="generate">Generate my Schedules</div>
    <a href="../saved.php"><div id="link">View Saved Schedules</div></a>
    <a href="processing/logout.php"><div id="logout">Log Out</div></a>
</body>
</html>