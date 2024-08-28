<?php
// Start a session
session_start();
session_regenerate_id(true);
include 'library/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $id = $_POST['id'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new password is the same as old password
    $stmt = $conn->prepare("SELECT lecPassword FROM lecturer WHERE lecID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['lecPassword'] === md5($newPassword)) {
        // New password is the same as old password
        $_SESSION['error'] = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> New password must be different from old password.";
    } else if ($newPassword !== $confirmPassword) {
        // Passwords do not match
        $_SESSION['error'] = " <i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Passwords do not match.";
    } else if (strlen($newPassword) < 8) {
        // Password is too short
        $_SESSION['error'] = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Password is too short.";
    } else {
        // Password validation checks passed
        $hashed_password = md5($newPassword);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE lecturer SET lecPassword = ? WHERE lecID = ?");
        $stmt->bind_param("si", $hashed_password, $id);

        if ($stmt->execute()) {
            // Password updated successfully
            header("Location: lecLogin.php?id=$id");
            exit();
        } else {
            // Password update failed
            $_SESSION['error'] = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Password update failed. Please try again later.";
        }
    }
}

// Show error or success message if set
if (isset($_SESSION['error'])) {
    echo '<div class="error" style="text-align:center;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
} else if (isset($_SESSION['success'])) {
    echo '<div class="success" style="text-align:center;">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Forgot Your Password ? </title>
        <link rel="stylesheet" type="text/css" href="library/lecMystyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <style>
            .form-container {
                padding: .8rem 1rem;
                width: 40vw;
                text-align:left;
            }

            body {
                color: grey;
            }

            .form-grid {
                padding: .5rem 1rem;
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
                display: grid;
                place-content: center;
                height: 95vh;
                background: url('https://cutewallpaper.org/25/anime-office-wallpaper/free-download-office-xfiles-background-deepstate-office-background-scenes-2560x1536-for-your-desktop-mobile-amp-tablet-explore-54-xfiles-background-xfiles-wallpaper.png')no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

            input {
                padding: .5rem .5rem .5rem 1.5rem;
                background-color: rgba(255, 255, 255, .4);
                border: rgba(255, 255, 255, .5) 2px solid;
            }


            @media only screen and (max-width:800px) {
                /* For mobile phones: */
                .form-container {
                    width: 100%;
                    max-width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                }
            }

        </style>
        
        <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }
            setTimeout("preventBack()", 0);

            window.onunload = function () {
                null;
            };

            // Regenerate session ID on each page load
            window.onload = function () {
                var xhttp = new XMLHttpRequest();
                xhttp.open("GET", "refresh_session.php", true);
                xhttp.send();
            };
            // Disable refresh
            document.onkeydown = function (e) {
                if (e.keyCode == 116) {
                    return false;
                }
            };

            document.onmousedown = function (e) {
                if (e.button == 4) {
                    return false;
                }
            };
        </script>
    </head>
    <body>
        <div class="form-container">
            <form action="" method="post">
                <h1 style="text-align:center;">Reset Password</h1>
                <p style="text-align:center;">Please fill up the details below to reset password. </p>
                <div>
                    <label for="id">Staff Email:</label>
                    <input type="text" name="id" value="" size="10" placeholder="Staff Email" required pattern="[a-zA-Z0-9._%+-]+@ypccollege\.edu\.my$" title="Example format: example@ypccollege.edu.my" />
                </div>
                <div>
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" value="" maxlength="10" placeholder="New Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit."/>
                </div>
                <div>
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" value="" maxlength="10" placeholder="Confirm Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit."/>
                </div><br/>

                <div class="button-container">                 
                    <button type="submit">Reset</button>
                    <button onclick="backPHP()">Back</button>
                </div>
            </form>

            <script>
                function backPHP() {
                    window.location.href = "lecLogin.php";
                }
            </script>
        </div>
    </body>
</html>

