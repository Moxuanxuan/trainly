<!-- report task 1 -->
<html>
    <head>
        <title>Faculty</title>
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
      <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
      <link rel="stylesheet" type="text/css" href="home.css">
    </head>
    <body>  
<!-- header: nav-bar -->
    <div class="header">
        <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
            <a class="pure-menu-heading" href="index.php">Trainly</a>
            
            <ul class="pure-menu-list">
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

     <div class="container-padding bottom-padding">
       <h3 class="content-head">Report Task 1:</h3>
        <h3>Find Faculty members who are Associate Professors and are<br> responsible for creating more than one course</h3>
<!--     sql query used in report 1     -->
        <div class="sql-query">
            <h4>Motivation:</h4> 
           <p>Find faculty who are associate professors who could be promoted by viewing their work efforts,
              in this case, being responsible for the number of classes they created. </p>
            
        </div>
<!-- retrieves query information from db  -->
        <form method = "POST" action = "ReportTask1.php">
            <input type="submit" class="pure-button" name= "report_task1_btn" value = "Click to Run">
        </form>
<!-- connect to db -->
        <?php
        session_start();
        $servername = "localhost";

        $username = "root";
        $password = "";
        $dbname = "Trainly";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
//runs query 
       if (isset($_POST['report_task1_btn']))
        {
            
            $sql_reporttask1 = "SELECT s.F_Name AS First_Name, s.L_Name AS Last_Name FROM Student s WHERE s.StudentID IN (SELECT f.StudentID FROM Course c INNER JOIN F_Create fc ON c.CourseID = fc.CourseID INNER JOIN Faculty f ON fc.FacultyID = f.StudentID GROUP BY f.StudentID HAVING COUNT(c.CourseID) > 1) AND s.StudentID IN (SELECT f.StudentID FROM Faculty f WHERE f.Title LIKE \"Associate Professor\") ORDER BY s.F_Name, s.L_Name";
            #$sql_reporttask1 = "SELECT F_Name as First_Name, L_Name as Last_Name FROM student";

            $result= mysqli_query($conn, $sql_reporttask1);
            #$row = mysqli_fetch_assoc($result);
            echo "<table class='pure-table pure-tabled-bordered'><thead>
                    <tr><th>First Name</th>
                    <th>Last Name</th></tr>
                </thead>
                <tbody>";
            while($taskresult_row = mysqli_fetch_assoc($result))
            {
                $First_Name = $taskresult_row['First_Name'];        
                $Last_Name = $taskresult_row['Last_Name'];
                echo "<tr>     <td>".$First_Name."</td>
                                <td>".$Last_Name."</td>
                    </tr>";
            }
            echo "</tbody>
                  </table>";
        }
// link to task 2
        echo "<br><a href=\"./ReportTask2.php\">Link to Report Task 2</a>";
    ?>


   </div>
    </body>
</html>






