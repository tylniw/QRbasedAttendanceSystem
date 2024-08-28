<?php
// Include the database connection file
include 'library/db.php';
session_start();


?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Student Dashboard</title>
        <link href="library/studMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <style>
            .bg {
                display: grid;
                place-content: center;
                height: 60vh;
                width: 100vw;

                background: var(--pri-color);
                background: var(--gradient);

                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

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

            span{
                font-size: 100px;
                color: white;
                text-align:center;
            }

            #sect-timetable {
                height: 40vh;
                width: 100vw;

                display: grid;
                place-content: center;

                gap: 3rem;

            }

            #sect-timetable h1, #sect-timetable p {
                color: var(--pri-color);
                text-align: center;
                font-size: 25px;
            }

            #sect-timetable h1 {
                font-size: 50px;
            }

            #sect-timetable a {
                color: #FFF;

                background: var(--pri-color);
                background: var(--gradient);

                padding: 1rem 2rem;
                border-radius: 25rem;

                width: max-content;

                text-decoration: none;

                transition: 300ms ease;

                place-self: center;

            }

            #sect-timetable a:hover {
                opacity: 0.75;
                transition: 500ms ease;
            }

            #sect-timetable a:active {
                opacity: 0.5;
                transition: 500ms ease;
            }

            #greeting {
                font-size: 50px;

            }

            body {
                overflow-x: hidden;
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

        <div class="bg" style="text-align:center;">
            <span id="greeting"></span>
            <span id="time"></span>
            <span id="date" style="font-size:60px;"></span>
        </div>

        <section id="sect-timetable">
            <h1>Schedule</h1>
            <p> Here, you can easily check your daily and weekly timetable for your classes</p>
            <a href="http://tinyurl.com/Timetabledaily" target="_blank">View Today's Schedule</a>
        </section>
        <script>
            function updateTime() {
                // Get current date and time
                var now = new Date();

                // Format the time to hh:mm:ss AM/PM
                const formattedTime = now.toLocaleString('en-US', {hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true});

                // Format the date to DD/MM/YYYY
                const formattedDate = [
                    ('0' + now.getDate()).slice(-2),
                    ('0' + (now.getMonth() + 1)).slice(-2),
                    now.getFullYear()
                ].join('/');

                // Get the day of the week
                const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const dayOfWeek = daysOfWeek[now.getDay()];

                // Determine the appropriate greeting based on the time of day
                const hour = now.getHours();
                let greeting = 'Hello! Nice to meet you!';

                if (hour >= 0 && hour < 12) {
                    greeting = 'Good Morning!';
                } else if (hour >= 12 && hour < 18) {
                    greeting = 'Good Afternoon!';
                } else {
                    greeting = 'Good Night!';
                }

                // Update the greeting and date/time displays
                document.getElementById("greeting").innerHTML = greeting + " It's now ";
                document.getElementById("time").innerHTML = formattedTime;
                document.getElementById("date").innerHTML = dayOfWeek + ", " + formattedDate;
            }

            function startTimer() {
                // Run the updateTime function every second
                setInterval(updateTime, 1000);
            }

            startTimer();
        </script>

    </body>
</html>