<?php 

    require('dbFunctions.php');
    $con = connect();
    dropTables($con);
    createTables($con);
    
    populateFromFile($con);
    
    displayTables($con);

?>