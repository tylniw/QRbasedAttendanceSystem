<?php
// Include the database connection file
include 'library/db.php';

?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Main Dashboard</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <style>
            .bg {
                display: grid;
                place-content: center;
                height: 50vh;
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

            .topnav a.active {
                background-color: #04AA6D;
                color: white;
            }

            .topnav .icon {
                display: none;
            }


            span{
                font-size: 100px;
                color: white;
                text-align:center;
            }

            #sect-timetable {
                height: 33vh;
                width: 100vw;
                display: grid;
                place-content: center;

            }

            #sect-timetable h1, #sect-timetable p {
                color: var(--pri-color);
                text-align: center;
                font-size: 25px;
            }

            #sect-timetable h1 {
                font-size: 40px;
            }

            #sect-timetable a {
                color: #FFF;

                background: var(--pri-color);
                background: var(--gradient);

                padding: 1rem .5rem;
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
                margin: 0;
                padding: 0;
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

        <div class="bg">
            <span id="greeting"></span>
            <span id="time"></span>
            <span id="date" style="font-size:60px;"></span>
            
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

        </div>
        <section id="sect-timetable">
            <h1>Schedule</h1>
            <p> Here, you can easily check your daily and weekly timetable for your classes</p>
            <a href="http://tinyurl.com/Timetabledaily" target="_blank">View Today's Schedule</a>
        </section>
    </body>
</html>