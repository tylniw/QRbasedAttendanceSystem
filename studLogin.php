<?php
session_start();
$_SESSION['user'] = 'student';
$_SESSION['studID'] = '';
include 'library/db.php';

if (isset($_GET['otp']) && !isset($_SESSION['lessonID'])) {
    $otp = $_GET['otp'];
    $query = "SELECT lessonID FROM lesson WHERE lessonOTP = $otp";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['lessonID'] = $row['lessonID'];
    } else {
        echo "OTP expired: Please scan again!";
    }
}

if (isset($_POST["login"])) {
    // Get the values of the form inputs
    $studID = htmlspecialchars($_POST['studID']);
    $studPW = htmlspecialchars($_POST['studPW']);

    // Run the query to check if the user exists
    $search = "SELECT * FROM student WHERE studID = '$studID' AND `studPassword` = MD5('$studPW')";
    $result = mysqli_query($conn, $search);

    // Check if the query returned any rows
    if (mysqli_num_rows($result) == 1) {
        // Set the session variables
        $_SESSION['studID'] = $studID;
        $_SESSION['loggedin'] = true;

        //take attendance
        if (isset($_SESSION['lessonID'])) {
            $search = "INSERT INTO attendance (attnStatus, studID, lessonID)
            VALUES ('Present', '$studID', '{$_SESSION['lessonID']}');";

            $result = mysqli_query($conn, $search);

            unset($_SESSION['lessonID']);
        }

        // Redirect to the dashboard
        header("Location: studDashboard.php");
        exit();
    } else {
        // Display an error message with a Font Awesome icon
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Invalid ID or password. Please try again.";
        echo "<div style=\"text-align:center;\">$error_message</div>";
    }
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Student Login</title>
        <link rel="stylesheet" type="text/css" href="library/lecMystyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel="icon" href="https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type="image/x-icon">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        
        <style>
            .form-container {
                padding: 1rem 1rem;
                width: 40vw;
                text-align: center;
            }

            .form-grid {
                padding: 0.5rem 1rem;
            }

            .form-grid label {
                text-align: left;
                margin-bottom: 0;
            }

            .button-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }

            body {
                margin: 0;
                padding: 0;
                display: grid;
                place-content: center;
                height: 100vh;
                background: url('https://wallpapercave.com/wp/wp6517811.jpg')no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

        </style>

    </head>

    <body>

        <div class="bg">
            <div class="form-container">
                <img src="library/ypclogo.jpg" alt="" style="width:80%;" />
                <h1 class="heading" style="font-size:28px;">QR based YPC Student Attendance System</h1>
                <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" method="POST" class="form">
                    <div class="form-grid">

                        <div>
                            <label>Student ID:</label>
                            <input type="text" name="studID" value="" size="12" placeholder="Student ID" required />
                        </div>

                        <div>
                            <label>Password:</label>
                            <input type="password" name="studPW" value="" size="20" placeholder="Password"  maxlength="20" required />
                            <a href="studForgotPassword.php" style="text-align:left;">Forget Your Password ? </a>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" name="login" id="login">Login</button>
                        <button id="signup">Sign Up</button>
                    </div>
                </form>

                <script>
                    var btn = document.getElementById('signup');
                    btn.addEventListener('click', function () {
                        document.location.href = 'studSignup.php';
                    });
                </script>
            </div>
    </body>

</html>