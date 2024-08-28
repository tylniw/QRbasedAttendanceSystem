<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Student Profile</title>
        <link href="library/studMystyle.css" rel="stylesheet" type="text/css"/>
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
            }

            .title {
                color: grey;
                font-size: 20px;
            }

            button {
                border: none;
                outline: 0;
                display: inline-block;
                padding: 8px;
                color: white;
                background-color: #0F4D92;
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
            <a href="studLogout.php" style="float:right";><i class="fas fa-sign-out-alt" style="color:white"></i> Logout</a>
            <a href="studProfile.php" style="float:right";><i class="fa fa-user"style="color:white"></i> Profile</a>
            <a href="studAttendance.php" style="float:right";><i class="fa fa-bar-chart" style="color:white"></i> Attendance</a>      
            <a href="studDashboard.php" style="float:right";><i class="fa fa-home" style="color:white"></i> Home</a>
        </div>

        <?php
        session_start();
        include 'library/db.php';

        $sql = "SELECT studName, studID, studPhoto, studPhone, studEmail FROM student WHERE studID = '{$_SESSION['studID']}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<div class='bg'>";
                echo "<div class='card'>";
                if (!empty($row['studPhoto'])) {
                    $img_data = base64_encode($row['studPhoto']);
                    echo '<img src="data:image/jpeg;base64,' . $img_data . '" style="width:280px;" />';
                }
                echo "<h1>" . $row['studName'] . "</h1>";
                echo "<p class='title'>Student</p>";
                echo "<p>" . $row['studID'] . "</p>";
                echo "<p>" . $row['studPhone'] . "</p>";
                echo "<p>" . $row['studEmail'] . "</p>";
                echo "<p><button id='btnEditProfile'>Edit</button></p>";
                echo "</div'>";
            }
        }
        mysqli_close($conn);
        ?>

        <script>
            var btn = document.getElementById('btnEditProfile');
            btn.addEventListener('click', function () {
                document.location.href = 'studEditProfile.php';
            });
        </script>

    </body>
</html>