<?php
session_start();
include 'library/db.php';

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Lecturer Profile</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <style>
            .topnav {
                overflow: hidden;
                background-color: #333;
                position: fixed;
                top: 0%;
                width: 100%;
            }

            .topnav a {
                float: left;
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
                padding: 0;
            }

            .bg {
                display: grid;
                place-content: center;
                height: 100vh;
                background-image: var(--gradient);
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

            .card {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                max-width: 400px;
                margin-top: 60px;
                text-align: center;
                font-family: Jost;
                line-height: .5;
                padding-bottom: 0px;
            }

            .title {
                color: grey;
                font-size: 20px;
            }

            button {
                border: none;
                outline: 0;
                display: inline-block;
                padding: 10px;
                color: white;
                background-color: #174304;
                text-align: center;
                cursor: pointer;
                width: 100%;
                font-size: 18px;
                border-radius: 0rem;
            }

            a {
                text-decoration: none;
                font-size: 22px;
                color: black;
            }
            button:hover, a:hover {
                opacity: 0.7;
            }

        </style>

    </head>

    <body>
        <div class="topnav">
            <img src="library/ypclogo.jpg" alt="" style="width:210px;float:left; padding-left:5px; padding-top:4px;"/>
            <a href="lecLogout.php" style="float:right";><i class="fas fa-sign-out-alt" style="color:white"></i> Logout</a>
            <a href="lecProfile.php" style="float:right";><i class="fa fa-user"style="color:white"></i> Profile</a>
            <a href="lecAttendance.php" style="float:right";><i class="fas fa-chart-pie"style="color:white"></i> Attendance</a>
            <a href="lecGenerate.php" style="float:right";><i class="fa fa-qrcode" style="color:white"></i> Generate</a>
            <a href="lecCreate.php" style="float:right";><i class="fa fa-chalkboard" style="color:white"></i> Create</a>        
            <a href="lecDashboard.php" style="float:right";><i class="fa fa-home" style="color:white"></i> Home</a>
        </div>

        <?php
        $sql = "SELECT lecName, lecID, lecPhoto, lecPhone FROM lecturer WHERE lecID = '{$_SESSION['id']}'";

// Execute the query and store the result
        $result = mysqli_query($conn, $sql);

// Check if there is any data returned from the query
        if (mysqli_num_rows($result) > 0) {
            // Loop through the data and display it
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<div class='bg'>";
                echo "<div class='card'>";
                if (!empty($row['lecPhoto'])) {
                    $img_data = base64_encode($row['lecPhoto']);
                    echo '<img src="data:image/jpeg;base64,' . $img_data . '" style="width:250px;" />';
                }

                echo "<h1>" . $row['lecName'] . "</h1>";
                echo "<p class='title'>Lecturer</p>";
                echo "<p>" . $row['lecID'] . "</p>";
                echo "<p>" . $row['lecPhone'] . "</p>";
                echo "<p><button id='btnEditProfile'>Edit</button></p>";
                echo "</div>";
            }
        }

// Close the database connection
        mysqli_close($conn);
        ?>

        <script>
            var btn = document.getElementById('btnEditProfile');
            btn.addEventListener('click', function () {
                document.location.href = 'lecEditProfile.php';
            });
        </script>
    </body>
</html>