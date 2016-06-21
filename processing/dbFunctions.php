<?php

    //=========================================================================
    // Contains callable functions for creating, populating and deleting 
    // database tables for the Schedule Builder.
    // @author: Frank Derry Wanye
    // @date: 05/25/2016
    //=========================================================================
    
    /**************************************************************************
     * Connects to the database using mysqli. In Cloud9, this is the default
     * database - c9
     *************************************************************************/
    function connect() {
        
        //Database location: 127.0.0.1
        //Default username: wanyef
        //Default password: none
        //Default database: c9
        $connect = new mysqli("127.0.0.1","wanyef","", "c9");    
        
        //Complains if the connection fails
        if( !$connect || $connect->connect_error )
            echo "Unable to connect to database: $connect->connect_error 
                <br />";
            
        return $connect;
            
    }
    
    /**************************************************************************
     * Creates all the tables needed for the Schedule Builder. At present, 
     * the simple implementation only has three tables: Departments, Classes, 
     * and Sections
     * ***********************************************************************/
    function createTables($connection) {
        
        //Stores the query strings for creating tables
        $queryString = array();
        
        //The Users Table
        $queryString[] = "CREATE TABLE Users (
            Email VARCHAR(25) NOT NULL,
            Name VARCHAR(35) NOT NULL,
            Password VARCHAR(25) NOT NULL,
            Constraint UserPrimKey PRIMARY KEY (Email)
            )";
        
        //The Departments Table
        $queryString[] = "CREATE TABLE Departments (
            DeptID INT(3) UNSIGNED NOT NULL,
            DeptName VARCHAR(20) NOT NULL,
            DeptCode VARCHAR(3) NOT NULL,
            Clicks INT(6) UNSIGNED NOT NULL,
            CONSTRAINT DeptPrimKey PRIMARY KEY (DeptID),
            CONSTRAINT UniqueDeptCode UNIQUE (DeptCode)
            )";
        
        //The Classes Table
        $queryString[] = "CREATE TABLE Classes (
            DeptCode VARCHAR(3) NOT NULL,
            ClassNum INT(3) UNSIGNED NOT NULL,
            ClassName VARCHAR(30) NOT NULL,
            Credits INT(1) UNSIGNED NOT NULL,
            CONSTRAINT ClassPrimKey PRIMARY KEY (DeptCode, ClassNum),
            CONSTRAINT ClassForeignKey FOREIGN KEY (DeptCode) 
                REFERENCES Departments(DeptCode) 
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT CreditsRange CHECK (Credits >= 1 and Credits <= 5)
            )";
            
        //The Sections Table
        $queryString[] = "CREATE TABLE Sections (
            DeptCode VARCHAR(3) NOT NULL,
            ClassNum INT(3) UNSIGNED NOT NULL,
            SectNum INT(2) UNSIGNED NOT NULL,
            Start TIME NOT NULL,
            End TIME NOT NULL,
            Days VARCHAR(3) NOT NULL,
            CONSTRAINT SectPrimKey PRIMARY KEY (DeptCode, ClassNum, SectNum),
            CONSTRAINT SectForeignKey FOREIGN KEY (DeptCode, ClassNum ) 
                REFERENCES Classes(DeptCode, ClassNum) 
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT SectTimeRangeCheck CHECK (End > Start)
            )";
            
        // //The Days Table
        // $queryString[] = "CREATE TABLE Days (
        //     DeptCode VARCHAR(3) NOT NULL,
        //     ClassNum INT(3) UNSIGNED NOT NULL,
        //     SectNum INT(2) UNSIGNED NOT NULL,
        //     Day VARCHAR(1) NOT NULL,
        //     CONSTRAINT SectPrimKey PRIMARY KEY (DeptCode, ClassNum, SectNum,
        //     Day),
        //     CONSTRAINT SectForeignKeyDeptCode 
        //     FOREIGN KEY (DeptCode, ClassNum, SectNum) 
        //         REFERENCES Sections(DeptCode, ClassNum, SectNum) 
        //         ON DELETE CASCADE ON UPDATE CASCADE,
        //     CONSTRAINT DayRange CHECK (Day = 'M' OR Day = 'T' OR Day = 'W' OR 
        //     Day = 'R' OR Day = 'F')
        //     )";
        
        //Creates all Tables in the database
        foreach( $queryString as $statement ) {
            
            //Complains if create table query fails    
            if ( !$connection->query($statement) )
                if ( $connection->errno == 1050 ) //Alert if table already exists
                    echo("Table already exists.<br>"); 
                else
                    echo("Error creating table: $connection->errno : 
                        $connection->error <br />"); //Alert if error is something else
            
        }
        
    }
    
    //Populates the Friends table from file
    function populateFromFile($connection) {
        
        //Gets pointer to file
        $files = array();
        $files[] = @fopen("../dbData/Depts.txt", "r");
        $files[] = @fopen("../dbData/Classes.txt", "r");
        $files[] = @fopen("../dbData/Sects.txt", "r");
        //$files[] = @fopen("../dbData/Days.txt", "r");
        
        //Inserts data from Depts.txt into Departments table
        if( $files[0] ) {
            
            //While there is a line
            while( ( $buffer = fgets($files[0], 4096) ) !== false ) {
                
                //Splits string by commas
                $data = explode(",", $buffer);
                
                //Adds a ',' in between $data elements
                //Apparently, entries into a query must be surrounded by single 
                //quotes
                $qData = implode("','", $data);
                
                //Builds query insert string
                $insert = "INSERT INTO Departments ( DeptID, DeptName, DeptCode, 
                    Clicks ) VALUES ( '$qData' )";
                
                //Runs query, complains if query fails 
                if ( !$connection->query($insert) )
                    echo "Error inserting record: $connection->error <br />";
                
            }
                
            
        } 
        else
            echo "Error: Depts.txt does not exist, or could not be opened. 
                <br />";
            
        //Inserts data from Classes.txt into Classes table
        if( $files[1] ) {
            
            //While there is a line
            while( ( $buffer = fgets($files[1], 4096) ) !== false ) {
                
                //Splits string by commas
                $data = explode(",", $buffer);
                
                //Adds a ',' in between $data elements
                //Apparently, entries into a query must be surrounded by single 
                //quotes
                $qData = implode("','", $data);
                
                //Builds query insert string
                $insert = "INSERT INTO Classes ( DeptCode, ClassNum, ClassName,
                    Credits ) VALUES ( '$qData' )";
                
                //Runs query, complains if query fails 
                if ( !$connection->query($insert) )
                    echo "Error inserting record: $connection->error <br />";
                
            }
                
            
        } 
        else
            echo "Error: Classes.txt does not exist, or could not be opened.";   
            
        //Inserts data from Sects.txt into Sections table
        if( $files[2] ) {
            
            //While there is a line
            while( ( $buffer = fgets($files[2], 4096) ) !== false ) {
                
                //Splits string by commas
                $data = explode(",", $buffer);
                
                //Adds a ',' in between $data elements
                //Apparently, entries into a query must be surrounded by single 
                //quotes
                $qData = implode("','", $data);
                
                //Builds query insert string
                $insert = "INSERT INTO Sections ( DeptCode, ClassNum, SectNum,
                    Start, End, Days ) VALUES ( '$qData' )";
                
                //Runs query, complains if query fails 
                if ( !$connection->query($insert) )
                    echo "Error inserting record: $connection->error <br />";
                
            }
                
            
        } 
        else
            echo "Error: Sects.txt does not exist, or could not be opened. 
                <br />";
            
        // //Inserts data from Days.txt into Days table
        // if( $files[3] ) {
            
        //     //While there is a line
        //     while( ( $buffer = fgets($files[3], 4096) ) !== false ) {
                
        //         //Splits string by commas
        //         $data = explode(",", $buffer);
                
        //         //Adds a ',' in between $data elements
        //         //Apparently, entries into a query must be surrounded by single 
        //         //quotes
        //         $qData = implode("','", $data);
                
        //         //Builds query insert string
        //         $insert = "INSERT INTO Days ( DeptCode, ClassNum, SectNum, Day
        //             ) VALUES ( '$qData' )";
                
        //         //Runs query, complains if query fails 
        //         if ( !$connection->query($insert) )
        //             echo "Error inserting record: $connection->error <br />";
                
        //     }
                
            
        // } 
        // else
        //     echo "Error: Days.txt does not exist, or could not be opened.";
        
    }
    
    /**************************************************************************
     * Clears the database by dropping all tables
     * ***********************************************************************/
    function dropTables($connection) {
        
        $queryString = array();
        
        //Order is important because of Foreign Key Constraints
        $queryString[] = "DROP TABLE Days";
        $queryString[] = "DROP TABLE Sections";
        $queryString[] = "DROP TABLE Classes";
        $queryString[] = "DROP TABLE Departments";
        $queryString[] = "DROP TABLE Users";
        
        foreach( $queryString as $statement ) {
            
            if ( !$connection->query($statement) )
                    echo "Error Deleting Table: $connection->error <br />";
            
        }
        
    }
    
    /**************************************************************************
     * Displays all table contents as html tables
     *************************************************************************/
    function displayTables($connection) {
        
        $result = $connection->query("SHOW TABLES");
        
        foreach( $result as $row ) {
        
            foreach( $row as $key => $value ) {
                
                echo "<h1>---------------$value----------------</h1>";
                
                echo "<table>";
                
                $showAll = $connection->query("SELECT * FROM $value");
                
                foreach( $showAll as $row ) {
                    
                    echo "<tr>";
                    
                    foreach( $row as $key2 => $value2 ) {
                        
                        echo "<td>$value2</td>";
                        
                    }
                    
                    echo "</tr>";
                    
                }
                
                echo "</table>";
                
            }  
        
        }
        
    }
    
    /**************************************************************************
     * Retrieves all departments from the Departments table and returns them as
     * an array
     *************************************************************************/
    function getDepartments($connection) {
        
        $queryString = "SELECT DeptName, DeptCode
        FROM Departments";
        
        $deptsArray = array();
        
        $result = $connection->query($queryString);
        
        if(!$result)
            echo "Could not query departments from database <br>\n";
        else {
            
            foreach($result as $row) {
                
                $temp = "";
                
                foreach($row as $key => $value) {
                    
                    $temp .= "$value | ";
                    //echo "value: $value \n";
                }
                
                $temp = substr($temp, 0, -2);
                
                $deptsArray[] = $temp;
                
            }
            
        }
        
        //echo $deptsArray[0];
        return $deptsArray;
        
    }
    
    /**************************************************************************
     * Retrieves all classes for a specific department from the Classes table 
     * and returns them as an array
     *************************************************************************/
    function getClasses($connection, $department) {
        
        $queryString = "SELECT ClassName, ClassNum, Credits
        FROM Classes
        WHERE DeptCode = '$department'";
        
        $result = $connection->query($queryString);
        if(!$result)
            echo "Could not retrieve classes from database";
        else {
            
            return $result;
            
        }
        
    }
    
    /**************************************************************************
     * ========================================================================
     * Modify this, because sections aren't gonna show up, they're gonna only
     * be accessed by app
     * ========================================================================
     * Retrieves all sections for a given class from the Sections table, as
     * well as the days on which they're offered, and returns them as an array
     *************************************************************************/
    function getSections($connection, $department, $class) {
        
        $queryString = "SELECT DeptCode, ClassNum, SectNum, Start, End, Days
        FROM Sections
        WHERE Sections.DeptCode = '$department' AND
              Sections.ClassNum = '$class'";
        
        $sectsArray = array();
        
        //Get all sections for specified class
        $result = $connection->query($queryString);
        if(!$result)
            echo "Could not query sections from database <br>\n";
        else {
            
            foreach($result as $row) {
                
                $sectsArray[] = $row;
                
            }
            
        }
        
        // foreach($sectsArray as $section) {
            
        //     $subQuery = "SELECT Day
        //     FROM Days
        //     WHERE Days.DeptCode = '$department' AND
        //           Days.ClassNum = '$class' AND
        //           Days.SectNum = '$section'";
                  
        //     $result = $connection->query($subQuery);
            
        //     if(!$result)
        //         echo "Could not query days from database <br>\n";
        //     else {
                
        //         foreach($result as $row) {
            
        //             echo sizeOf($row);
                    
        //             foreach($row as $key => $value) {
                        
        //                 $section[] = $value;
                        
        //             }
                    
        //         }
                
        //     }
            
        // }
        
        return $sectsArray;
        
    }
    
?>