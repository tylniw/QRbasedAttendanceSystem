<?php
session_start();
include 'library/db.php';


if (isset($_SESSION['id'])) {

    $lecID = $_SESSION['id'];
    $sql = "SELECT * FROM subject WHERE subject.lecID = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the lecturer ID to the placeholder
    mysqli_stmt_bind_param($stmt, "s", $lecID);

    // Execute query and fetch results
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $options = "";

    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='{$row['subID']}'>{$row['subID']} - {$row['subName']}</option>";
    }
} else {
    // Handle the case where $_SESSION['id'] is not set
    $options = "<option value=''>No options available</option>";
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Generate QR code</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            .form-container {
                padding: 1.5rem 2rem;
                position: relative;
                top: 25px;
            }

            .form {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .form-grid {
                padding: 2rem 2rem;
            }

            .form-grid label {
                text-align: left;
                margin-bottom: 1rem;
            }

            .button-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                padding-top: 2rem;
            }

            h1{
                text-align:center;
                font-size: 2rem;
            }

            input,label,select{
                font-size: 1.4rem;
                margin-bottom: .4rem;
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

        <div class="bg">
            <div class="form-container">
                <form method="post" action="create_lesson.php">

                    <div>
                        <h1>Generate QR code</h1>                      
                    </div>

                    <div>
                        <label for="lecID">Staff Email:</label>
                        <input type="text" id="lecID" name="lecID" value="<?php echo $lecID; ?>" readonly style="cursor: pointer;" required/>
                    </div>

                    <div>
                        <label for="subID">Subject:</label>
                        <select name="subID" id="subID">
                            <?php echo $options; ?>
                        </select>
                    </div>

                    <div>
                        <label for="lessonDate">Lesson Date:</label>
                        <input type="date" id="lessonDate" name="lessonDate" required>
                    </div>    

                    <div>
                        <label for="classDuration">Lesson Duration:</label>
                        <select name="classduration" id="lessonDuration">
                            <option value="01:00:00">1 Hour</option>
                            <option value="01:30:00">1 Hour 30 Minutes</option>
                            <option value="02:00:00">2 Hours</option>
                            <option value="02:30:00">2 Hour 30 Minutes</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button type="submit" name="createLesson">Generate</button>
                        <button type="reset">Clear</button>
                    </div>

                </form>
            </div>
        </div>
    </body>
</html>