<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>View Attendance</title>
        <link href = "library/studMystyle.css" rel = "stylesheet" type = "text/css"/>
        <link href = "https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel = "stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src = 'https://kit.fontawesome.com/a076d05399.js' crossorigin = 'anonymous'></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

            h2,p{
                text-align:center;
            }

            .form-container {
                width: 50vw;

                padding: 3rem;
                border-radius: 1rem;

                border: rgba(255, 255, 255, .5) 2px solid;

                background: rgba(255, 255, 255);
                border-radius: 16px;
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .form-grid {
                padding: 1rem 2rem;
            }

            .form-grid label {
                text-align: left;
                margin-bottom: 1rem;
            }

            .button-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
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

            h1{
                text-align:center;
                font-size: 2rem;
                color: black;
            }

            input,label,select{
                font-size: 1.4rem;
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

        <div class="bg">
            <div class="form-container">
                <h1>Attendance</h1> 
                <?php
                session_start();
                include 'library/db.php';
             

                if (isset($_SESSION['studID'])) {
                    $stmt = mysqli_prepare($conn, "SELECT subject.subName AS 'Subject Name', COUNT(attendance.studID) AS 'Attended', COUNT(lesson.lessonID) AS 'Total Lessons',
                                                        ROUND(COUNT(attendance.studID) * 100.0 / COUNT(lesson.lessonID), 2) AS 'Attendance'                                                 FROM subject
                                                 INNER JOIN lesson ON lesson.subID = subject.subID
                                                 LEFT JOIN attendance ON attendance.lessonID = lesson.lessonID AND attendance.studID = ?
                                                 GROUP BY subject.subName;");

                    mysqli_stmt_bind_param($stmt, "s", $_SESSION['studID']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    // Initialize two arrays to store the labels and data for the chart
                    $labels = array();
                    $data = array();

                    // Fetch data and populate the arrays
                    if (mysqli_num_rows($result) == 0) {
                        echo "No rows found for student ID " . $_SESSION['studID'];
                    }
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $labels[] = $row["Subject Name"];  // Add subName value to the labels array
                            $data[] = $row["Attendance"];  // Add present_count value to the data array
                        }
                    } else {
                        echo "No data found";
                    }
                } else {
                    echo "No student ID found in session";
                }

                // Close database connection
                mysqli_close($conn);
                ?>


                <canvas id="myChart"></canvas>
                <script>
                    var ctx = document.getElementById("myChart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($labels); ?>, // Use the $labels array here
                            datasets: [{
                                    label: 'Attendance',
                                    data: <?php echo json_encode($data); ?>, // Use the $data array here
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                        },
                        options: {
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Subject'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: 100,
                                    ticks: {
                                        callback: function (value, index, values) {
                                            return value + '%';
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Percentage %'
                                    }
                                }
                            }
                        }
                    });
                </script>


            </div>
    </body>
</html>