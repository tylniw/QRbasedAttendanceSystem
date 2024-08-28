<?php
session_start();
include 'library/db.php';


// Retrieve data from database
$lecID = mysqli_real_escape_string($conn, $_SESSION['id']);
$sql = "SELECT lecID FROM lecturer WHERE lecID = '$lecID'";
$result = mysqli_query($conn, $sql);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Fetch the first row
    $row = mysqli_fetch_assoc($result);
} else {
    // No rows were returned
    echo "No results found.";
}

if (isset($_POST['create'])) {
    $lecID = mysqli_real_escape_string($conn, $_POST['lecID']);
    $subID = mysqli_real_escape_string($conn, $_POST['subID']);
    $subName = mysqli_real_escape_string($conn, $_POST['subName']);
    $faculty = mysqli_real_escape_string($conn, $_POST['faculty']);

    $sql = "INSERT INTO subject (lecID, subID, subName, faculty) 
        VALUES ('$lecID', '$subID','$subName', '$faculty')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            window.location.href = "lecGenerate.php";
          </script>';
    } else {
        echo "Error creating record: " . mysqli_error($conn);
    }

    // Generate options for select input
    $sql = "SELECT subID, subName FROM subject";
    $result = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . $row['subID'] . "'>" . $row['subName'] . "</option>";
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Class</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
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

            .form-container {
                padding: 1.5rem 2rem;
                position: relative;
                top: 25px;
            }

            .form {
                display: flex;
                flex-direction: column;
                gap: .5rem;
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
                padding-top: 1rem;
            }

            h1{
                text-align:center;
                font-size: 2rem;
            }

            input,label,select{
                font-size: 1.4rem;
                margin-bottom: .2rem;
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
                <form class="form" action="lecCreate.php" method="POST">
                    <div>
                        <h1>Create A Class</h1>
                    </div>

                    <div>
                        <label for="lecID">Staff Email:</label>
                        <input type="text" name="lecID" value="<?php echo $_SESSION['id']; ?>" size="10" readonly style="cursor: pointer;"/>
                    </div>

                    <div>
                        <label for="subjectID">Subject Code:</label>
                        <input type="text" name="subID" value="" size="10" placeholder="Subject Code" required/>
                    </div>

                    <div>
                        <label for="subjectname">Subject Name:</label>
                        <input type="text" name="subName" value="" size="10" placeholder="Subject Name" required/>
                    </div>

                    <div>
                        <label for="faculty">Faculty:</label>
                        <select name="faculty" id="faculty">
                            <option value="FA">Faculty of Accountancy</option>
                            <option value="FOA">Faculty of Art</option>
                            <option value="FB">Faculty of Business</option>
                            <option value="FGS">Faculty of General Studies</option>
                            <option value="FIT">Faculty of Internet Technology</option>
                            <option value="FPS">Faculty of Professional Studies</option>
                            <option value="FS">Faculty of Science</option>
                            <option value="PU">PRE- U</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button type="submit" name="create">Create</button>
                        <button type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
