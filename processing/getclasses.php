<?php
/******************************************************************************
 * Queries the database to get the classes for a given department number, turns
 * each class into a div with a 'department' id, and echoes them. The echoed
 * divs are picked up through AJAX by a javascript script.
 *****************************************************************************/
if(isset($_GET["deptcode"])) {
    require("dbFunctions.php");
    
    //The department for which the request is made
    $deptcode= $_GET["deptcode"];
    $con = connect();
    $classes = getClasses($con, $deptcode);
    
    //Formats the div's content
    foreach($classes as $row) {
        
        $str = "";
        foreach($row as $key => $value) {
            
            if($key == "ClassNum")
                $str .= "$deptcode $value | ";
            elseif($key == "Credits")
                $str .= "$value credit hours";
            else
                $str .= "$value | ";
        
        }
        echo "<div class='class'>$str</div>";
        
    }
}
else
    echo "No class was picked";
?>