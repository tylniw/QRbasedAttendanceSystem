<?php
session_start();
include 'library/db.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Student Attendance</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>



        <style>
            .topnav {
                overflow: hidden;
                background-color: #333;
                margin-bottom: 0;
            }

            .topnav a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            .topnav a:hover {
                background-color: #ddd;
                color: black;
            }

            @media screen and (max-width: 500px) {
                .topnav a {
                    float: none;
                    display: block;
                }
            }

            body {
                margin: 0;
                padding: 0；
                    height： auto;
                background-image: var(--gradient);
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background-position: center center;
                text-align: center;
            }

            .alert {
                padding: 10px;
                width: 100%;
                height: 50px;
                background-color: #f44336;
                color: white;
                display: inline-block;
            }

            .closebtn {
                margin-left: 15px;
                color: white;
                font-weight: bold;
                float: right;
                font-size: 22px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            .closebtn:hover {
                color: black;
            }

            table, th, td {
                border-collapse: collapse;
                padding: 5px;
            }
            th {
                background-color: #909090;
            }

            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 100px; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                text-align: left;
            }

            /* Modal Content */
            .modal-content {
                background-color: #006400;
                margin: 0 auto;
                padding: 40px;
                border: 1px solid #888;
                width: 50%;
            }

            /* The Close Button */
            .close {
                color: #aaaaaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>
    <body>

        <div class="topnav" id="myTopnav">
            <img src="library/ypclogo.jpg" alt="" style="width:210px;float:left; padding-left:5px; padding-top:4px;"/>
            <a href="lecLogout.php" style="float:right";><i class="fas fa-sign-out-alt" style="color:white"></i> Logout</a>
            <a href="lecProfile.php" style="float:right";><i class="fa fa-user"style="color:white"></i> Profile</a>
            <a href="lecAttendance.php" style="float:right";><i class="fas fa-chart-pie"style="color:white"></i> Attendance</a>
            <a href="lecGenerate.php" style="float:right";><i class="fa fa-qrcode" style="color:white"></i> Generate</a>
            <a href="lecCreate.php" style="float:right";><i class="fa fa-chalkboard" style="color:white"></i> Create</a>        
            <a href="lecDashboard.php" style="float:right";><i class="fa fa-home" style="color:white"></i> Home</a>         
        </div>

        <section class="p-5 container">
            <form method='post' style="text-align:center;">
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display = 'none';">&times;</span> 
                    <strong>Reminder : </strong> Please select subject before searching student attendance.
                </div>
                <h1 class="mb-4">View Student Attendance</h1>
                <label>Subject: </label>
                <select id='subID' name='subject'>
                    <option value=''></option>
                    <?php
                    $lecID = $_SESSION['id'];
                    $sql = "SELECT subID, subName FROM subject WHERE lecID = '$lecID'";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['subID'] . "'>" . $row['subID'] . " - " . $row['subName'] . " </option>";
                    }
                    ?>
                </select>

                <button type='submit' name='filter'>Search</button>
            </form>

            <?php
            if (isset($_POST['filter'])) {
                $filter = $_POST['subject'];
                $lecID = $_SESSION['id'];

                $query = "SELECT s.studID, s.studName, s.studPhone, COALESCE(COUNT(DISTINCT a.lessonID), 0) AS 'Attended', 
                            total_lessons AS 'Total Lessons', 
                            CONCAT(FORMAT(COALESCE(COUNT(DISTINCT a.lessonID), 0) * 100 / total_lessons, 2), '%') AS 'Attendance Percentage' 
                            FROM student s 
                            JOIN attendance a ON s.studID = a.studID 
                            JOIN lesson l ON a.lessonID = l.lessonID 
                            JOIN subject sub ON l.subID = sub.subID 
                            JOIN (SELECT COUNT(*) AS total_lessons FROM lesson WHERE subID = '$filter') AS tl ON 1=1 
                            WHERE sub.subID = '$filter' AND sub.lecID = '$lecID' 
                            GROUP BY s.studID;";

                $result = mysqli_query($conn, $query);

                if ($result->num_rows > 0) {
                    echo "<br/>";
                    echo "<table id='attendance' style='margin: 0 auto;'><tr><th>No</th><th>Student ID</th><th>Student Name</th><th>Student Phone Number</th><th>Attended</th><th>Total Lessons</th><th>Attendance Percentage</th><th>Actions</th></tr>";
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . $row['studID'] . "</td>";
                        echo "<td>" . $row['studName'] . "</td>";
                        echo "<td>" . $row['studPhone'] . "</td>";
                        echo "<td>" . $row['Attended'] . "</td>";
                        echo "<td>" . $row['Total Lessons'] . "</td>";
                        echo "<td>" . $row['Attendance Percentage'] . "</td>";
                        echo "<td><button id='myBtn' onclick='openModal(" . json_encode($row) . ")' style='border-radius:0rem; padding:.2rem; font-size:1rem;'>Send</button></td></tr>";
                        echo "</tr>";
                        $i++;

                        echo '<div id="myModal" class="modal">';
                        echo '<div class="modal-content">';
                        echo '<span class="close">&times;</span>';
                        echo '<form>';
                        echo '<h1 style="text-align:center;">Send Notification Form</h1><br/>';
                        echo '<label for="phone-number">Student Phone Number:</label>';
                        echo '<input type="text" id="phone-number" name="phone-number" value="' . $row['studPhone'] . '" readonly><br/><br/>';
                        echo '<label for="name">Student Name:</label>';
                        echo '<input type="text" id="name" name="name" value="' . $row['studName'] . '" readonly><br/><br/>';
                        echo '<label for="message">Message:</label>';
                        echo '<textarea id="message" name="message" style="width: 100%;height: 80px; resize: none; color:black;"></textarea><br/><br/>';
                        echo '<button type="button" onclick="whatsapp()" style="border-radius: 0rem;">Send</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No results found.";
                }
            }
            
            mysqli_close($conn);
            ?>



            <script>
                $(document).ready(function () {
                    $('#attendance').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": true
                    });
                });

                // add event listeners to each table row
                var rows = document.getElementsByTagName("tr");
                for (var i = 1; i < rows.length; i++) {  // start at 1 to skip header row
                    rows[i].addEventListener("click", function () {
                        openModal(this);  // pass the row that was clicked to the openModal function
                    });
                }

                function openModal(row) {
                    // retrieve the data from the row that was clicked
                    var phoneNumber = row.cells[3].textContent;
                    var name = row.cells[2].textContent;
                    var message = 'Hello ' + name + ', your attendance for the class today is absent.';
                    document.getElementById("phone-number").value = phoneNumber;
                    document.getElementById("name").value = name;
                    document.getElementById("message").value = message;
                    // open the modal
                    document.getElementById("myModal").style.display = "block";
                }

                function closeModel() {
                    document.getElementById("myModal").style.display = "none";
                }

                function whatsapp() {
                    var phoneNumber = document.getElementById("phone-number").value;
                    var name = document.getElementById("name").value;
                    var message = document.getElementById("message").value;

                    var url = "https://wa.me/" + "6" + phoneNumber + "?text="
                            + "*Name :* " + name + "%0a"
                            + "*Message :* " + message;

                    window.open(url, '_blank').focus();
                    closeModel(); // close the modal after submitting the form
                }
                // Get the modal
                var modal = document.getElementById("myModal");

                // Get the button that opens the modal
                var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks the button, open the modal 
                btn.onclick = function () {
                    modal.style.display = "block";
                }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function () {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function (event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>

    </body>
</html>