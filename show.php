<?php
require("processing/dbFunctions.php");
session_start();
if(session_id() == '' || !isset($_SESSION["name"]))
    header("Location: login.php");
else {
    $name = $_SESSION["name"];
}

/******************************************************************************
 * A function that obtains the cartesian product of a vector of vectors
 * Authors: Serg and Jon from StackOverflow
 *****************************************************************************/
function cartesian($input) {
    // filter out empty values
    $input = array_filter($input);

    $result = array(array());

    foreach ($input as $key => $values) {
        $append = array();

        foreach($result as $product) {
            foreach($values as $item) {
                $product[$key] = $item;
                $append[] = $product;
            }
        }

        $result = $append;
    }

    return $result;
}

/******************************************************************************
 * Compares two section arrays - if they have the same values, then returns
 * true, otherwise returns false
 *****************************************************************************/
function areSame($array1, $array2) {
    
    if($array1["DeptCode"] == $array2["DeptCode"] &&
        $array1["ClassNum"] == $array2["ClassNum"] &&
        $array1["SectNum"] == $array2["SectNum"] &&
        $array1["Start"] == $array2["Start"] &&
        $array1["End"] == $array2["End"] &&
        $array1["Days"] == $array2["Days"])
        return true;
    else
        return false;
    
}

/******************************************************************************
 * Compares two strings - if they have intersecting characters, returns true,
 * else returns false
 *****************************************************************************/
function intersection($string1, $string2) {
    
    if(similar_text($string1, $string2) == 0)
        return false;
    else 
        return true;
        
}

/******************************************************************************
 * Echoes a row for the specified time and for the specified schedule
 *****************************************************************************/
function makeRow($time, $schedule) {
    echo "<tr>\n";
    //Prints the time in the first column
    echo "<th>$time</th>\n";
    //Cycles through the 7 columns of the table
    for($i = 0; $i < 7; ++$i) {
        $found = false;
        foreach($schedule as $section) {
            //Makes sure the class is placed in the correct row (day)
            if( $i == 0 && strrpos($section["Days"],"M") > -1 ||
                $i == 1 && strrpos($section["Days"],"T") > -1 ||
                $i == 2 && strrpos($section["Days"],"W") > -1 ||
                $i == 3 && strrpos($section["Days"],"R") > -1 ||
                $i == 4 && strrpos($section["Days"],"F") > -1 )
                //If class occurs at $time and day, place class in div
                if($time >= $section["Start"] &&
                    $time < $section["End"]) {
                        $dept = $section["DeptCode"];
                        $class = $section["ClassNum"];
                        $sect = $section["SectNum"];
                        echo "<td class='occupied'>$dept $class $sect</td>";
                        $found = true;
                }
            }
        //If no class at specified time and day, echo empty div
        if($found == false)
            echo "<td></td>\n";
    }
    echo "</tr>\n";
}
/******************************************************************************
 * Echoes a table for a specified schedule of class sections
 *****************************************************************************/
function makeTable($currentSchedule) {
    //print_r($currentSchedule);
    echo"<table>\n";
        echo"<tr>\n";
            echo"<th>Time</th>\n";
            echo"<th>Monday</th>\n";
            echo"<th>Tuesday</th>\n";
            echo"<th>Wednesday</th>\n";
            echo"<th>Thursday</th>\n";
            echo"<th>Friday</th>\n";
            echo"<th>Saturday</th>\n";
            echo"<th>Sunday</th>\n";
        echo"</tr>\n";
        makeRow("08:00:00", $currentSchedule);
        makeRow("08:30:00", $currentSchedule);
        makeRow("09:00:00", $currentSchedule);
        makeRow("09:30:00", $currentSchedule);
        makeRow("10:00:00", $currentSchedule);
        makeRow("10:30:00", $currentSchedule);
        makeRow("11:00:00", $currentSchedule);
        makeRow("11:30:00", $currentSchedule);
        makeRow("12:00:00", $currentSchedule);
        makeRow("12:30:00", $currentSchedule);
        makeRow("13:00:00", $currentSchedule);
        makeRow("13:30:00", $currentSchedule);
        makeRow("14:00:00", $currentSchedule);
        makeRow("14:30:00", $currentSchedule);
        makeRow("15:00:00", $currentSchedule);
        makeRow("15:30:00", $currentSchedule);
        makeRow("16:00:00", $currentSchedule);
        makeRow("16:30:00", $currentSchedule);
        makeRow("17:00:00", $currentSchedule);
        makeRow("17:30:00", $currentSchedule);
        makeRow("18:00:00", $currentSchedule);
        makeRow("18:30:00", $currentSchedule);
        makeRow("19:00:00", $currentSchedule);
        makeRow("19:30:00", $currentSchedule);
    echo "</table>\n";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Builder Generated Schedules</title>
    <link type="text/css" rel="stylesheet" href="style/show.css"/>
    <link type="text/css" rel="stylesheet" href="style/animate.css"/>
    <script type="text/javascript" src="scripts/show.js"></script>
</head>
<body>
    <div id="header">
        <h1>Schedule Builder</h1>
        <h2>Generated Schedules</h2>
    </div>
    <div id="prev">Previous</div>
    <div id="center">Error Loading Script</div>
    <div id="next">Next</div>
    <?php
    if(isset($_COOKIE["selected"]))
        $selected = $_COOKIE["selected"];
    else //No selection was made
        echo "Error: could not retrieve selected classes";
    
    //Breaks cookie down into array of classes    
    $classes = explode("-",$selected);
    
    $sections = array();
    
    //For each class, gets all possible schedules, appends to $sections array
    foreach($classes as $value) {
        
        $value = trim($value, " ");
        $dbdata = explode(" ", $value);
        
        $con = connect();
        $result = getSections($con, $dbdata[0], $dbdata[1]);
        $sections[] = $result;
        
    }
    
    $sectionsData = array();
    
    //The classes that were selected
    foreach($sections as $class)
        //The sections for that class
        foreach($class as $section) {
            //The data for that class
            $sectionsData[] = $section;
        }
        
    //Holds all the possible schedules that could be generated
    //Very large array: I got 1080 possible schedules from 6 classes
    $schedules = cartesian($sections);
    
    //Holds all schedules without conflicts
    $workingschedules = array();
    
    //Goes through all schedules, if a schedule has no conflicts, appends it to
    //the $workingschedules array
    foreach($schedules as $schedule) {
        
        $works = true;
        
        foreach($schedule as $section) {
            
            foreach($schedule as $section2) {
                
                //If the two arrays are not the same
                if( areSame($section, $section2) == false ) {
                    //Make sure their times don't conflict
                    if( intersection($section2["Days"], $section["Days"]) &&
                        !($section2["Start"] >= $section["End"]) &&
                        !($section2["End"] <= $section["Start"]) ) {
                        //If they conflict, break from this loop
                        $works = false;
                        break;
                    }
                }
                
            }
            
        }
        
        if( $works )
            $workingschedules[] = $schedule;
        
    }
    
    $count = count($workingschedules);
    
    //If no working schedules
    if($count == 0)
        echo "<div id='noSchedules'>Sorry, there are no possible schedules that 
            include all the classes you picked. Try switching one class out for
            a different one, or removing one class.</div>";
    else {
    
        //Makes a table for each working schedule
        foreach($workingschedules as $schedule)
            makeTable($schedule);
        
        echo "<div id='error'>This schedule is already saved!</div>\n
            <div id='success'>This schedule has been successfully saved!</div>\n
            <div class='link' id='save'>Save This Schedule</div>\n";
            
    }
        
    ?>
    <a href="../saved.php"><div class="link">View Saved Schedules</div></a>
    <a href="../select.php"><div class="link">Make Another Selection</div></a>
    <a href="../processing/logout.php"><div class="link">Log Out</div></a>
</body>
</html>