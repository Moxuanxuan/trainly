
<!-- Administrators main home page, 
accesed after logging in -->


<!-- connect to database -->
<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Trainly";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
?>

<!-- html body -->
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
    <link rel="stylesheet" href="home.css">
</head>
        
<body>
    <div class="header">
        <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
            <a class="pure-menu-heading" href="index.php">Trainly</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu"><a href="adminhome.php" class="pure-menu-link">Home</a></li>
                <li class="pure-menu-item"><a href="adminstuhome.php" class="pure-menu-link">Student Page</a></li>
                <li class="pure-menu-item"><a href="history.php" class="pure-menu-link">Account History</a></li>
                <li class="pure-menu-item"><a href="index.php" class="pure-menu-link">Log Out</a></li>
                <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
                <a href="#" id="menuLink1" class="pure-menu-link">Reports</a>
                <ul class="pure-menu-children">
                    <li class="pure-menu-item"><a href="ReportTask1.php" class="pure-menu-link">Faculty</a></li>
                    <li class="pure-menu-item"><a href="ReportTask2.php" class="pure-menu-link">B Grade</a></li>
                    <li class="pure-menu-item"><a href="ReportTask3.php" class="pure-menu-link">Quizzes</a></li>
                    <li class="pure-menu-item"><a href="ReportTask4.php" class="pure-menu-link">Incomplete</a></li>
                    <li class="pure-menu-item"><a href="ReportTask5.php" class="pure-menu-link">Questions</a></li>
                </ul>
                </li>
            </ul>
                
        </div>
    </div>
    <div class="content">
    <!-- read email information after logging in -->
    <?php
        
        $email = $_SESSION['email'];
        $idsql = "SELECT s.StudentID FROM Student s WHERE s.Email = '$email'";
        $fnamesql = "SELECT s.F_Name FROM Student s WHERE s.Email = '$email'";
        $lnamesql = "SELECT s.L_Name FROM Student s WHERE s.Email = '$email'";
        $result2 = mysqli_query($conn, $idsql);
        $result3 = mysqli_query($conn, $fnamesql);
        $result4 = mysqli_query($conn, $lnamesql);
    
                 
        //get StudentID 
        $row = mysqli_fetch_assoc($result2);
        $row2 = mysqli_fetch_assoc($result3);
        $row3 = mysqli_fetch_assoc($result4);
        $id = ($row['StudentID']);
        $fname = ($row2['F_Name']);
        $lname = ($row3['L_Name']);
            
    // enroll in course where user id is paramater
    $enrollcoursesql = "SELECT * FROM
        (SELECT c.Name AS Course_Name, c.Pri_Topic AS Primary_Topic, st.Topic AS Secondary_Topics, 'Enrolled' AS Category
        FROM ((Student s INNER JOIN Enroll_in e ON s.StudentID = e.StudentID)
	   INNER JOIN Course c ON e.CourseID = c.CourseID)
	   INNER JOIN Sec_topic st ON c.CourseID = st.CourseID
       WHERE s.StudentID = '$id'
       ORDER BY e.Rating DESC
       LIMIT 100) AS a";
    // display enrool course result       
    $enrollcourseresult = mysqli_query($conn, $enrollcoursesql);
        
    ?>
    
    <br><h2 class="content-head is-center">Welcome <?php echo $fname . " " . $lname; ?></h2>
    <h5>Admin ID#: <?php echo $_SESSION['StudentID']; ?></h5>
    
    
    <?php
    
        $faclist = "SELECT s.F_Name AS First_Name, s.L_Name AS Last_Name, f.Title AS Title, f.Affiliation AS Affiliation, f.Work_Website AS Website FROM Student s
        INNER JOIN Faculty f ON s.StudentID = f.StudentID";
        
    $fresult = mysqli_query($conn, $faclist);
    
    echo "<h3 class='content-subhead'>FACULTY LIST</h3>";
    echo "<table class='pure-table'><thead><tr><th><b>First Name</th><th><b>Last Name</th><th><b>Title</th><th><b>Affiliation</th><th><b>Website</th></tr></thead><tbody>";
    
    while ($frow = mysqli_fetch_assoc($fresult))
    {
        $ffname = $frow['First_Name'];
        $flname = $frow['Last_Name'];
        $ftitle = $frow['Title'];
        $faffl = $frow['Affiliation'];
        $fweb = $frow['Website'];
        
        echo "<tr><td>".$ffname . "</td><td>" . $flname . "</td><td>" . $ftitle . "</td><td>" . $faffl . "</td><td>" . $fweb . "</td></tr>";
    }
    echo "</tbody></table>";
    
    ?>
    
    <form class="pure-form" method = POST action = "">
        <fieldset>
            <input type="text" name ="ftitle" placeholder= "Title">
            <input type="text" name ="faffl" placeholder= "Affiliation">
            <input type="text" name ="fweb" placeholder= "Website">
            <input type="submit" class="pure-button" name= "val_btn" value = "Validate">
        </fieldset>
       
    </form>
    
    <?php
    if (isset($_POST['val_btn']))
    {
            $fftitle = mysql_real_escape_string($_POST['ftitle']);
            $ffaffl = mysql_real_escape_string($_POST['faffl']);
            $ffweb = mysql_real_escape_string($_POST['fweb']);
            
            $valsql = "UPDATE Faculty SET AdminID = '$id'
            WHERE Title = '$fftitle' AND Affiliation = '$ffaffl' AND Work_Website = '$ffweb'";
            
            $valresult = mysqli_query($conn, $valsql);
            
            
    }
    ?>
    
    
    
        <?php
    
        $adlist = "SELECT a.StudentID AS ID, s.F_Name AS First_Name, s.L_Name AS Last_Name FROM Admin a
        INNER JOIN Student s ON s.StudentID = a.StudentID";
        
    $aresult = mysqli_query($conn, $adlist);
    
    echo "<h3 class='content-subhead'>ADMIN LIST</h3>";
    echo "<table class='pure-table'><thead><tr><th>ID</th><th>First Name</th><th>Last Name</th></th></tr></thead><tbody>";
    
    while ($arow = mysqli_fetch_assoc($aresult))
    {
        $aid = $arow['ID'];
        $af = $arow['First_Name'];
        $al = $arow['Last_Name'];
        
        echo "<tr><td>".$aid . "</td><td>" . $af . "</td><td>" . $al . "</td></tr>";
    }
    echo "</tbody></table>";
    
        if (isset($_POST['aval_btn']))
    {
            $adminid = mysql_real_escape_string($_POST['aid']);
            $avalsql = "UPDATE Admin SET Grantor = '$id' WHERE StudentID = '$adminid'";          
            $avalresult = mysqli_query($conn, $avalsql); 
    }
    
    ?>
    
    <form class="pure-form pure-form-aligned" method = POST action = "">
        <fieldset>
            <input type="text" name ="aid" placeholder= "ID">
            <input type="submit" class="pure-button" name= "aval_btn" value = "Validate">
        </fieldset>
    </form>
    
</body>
    
</html>

