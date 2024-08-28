<?php
// Start a session
session_start();
include 'library/db.php';


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $id = $_POST['studID'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new password is the same as old password
    $stmt = $conn->prepare("SELECT studPassword FROM student WHERE studID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['studPassword'] === md5($newPassword)) {
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
        $stmt = $conn->prepare("UPDATE student SET studPassword = ? WHERE studID = ?");
        $stmt->bind_param("si", $hashed_password, $id);

        if ($stmt->execute()) {
            // Password updated successfully
            header("Location: studLogin.php?id=$id");
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
        <link rel="stylesheet" type="text/css" href="library/studMystyle.css">
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
                background: url('https://wallpapercave.com/wp/wp6517811.jpg')no-repeat center center fixed;
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
        
    </head>
    <body>
        <div class="form-container">
            <form action="" method="post">
                <h1 style="text-align:center;">Reset Password</h1>
                <p style="text-align:center;">Please fill up the details below to reset password. </p>
                <div>
                    <label for="id">Student ID:</label>
                    <input type="text" name="studID" value="" size="10" placeholder="Student ID" required />
                </div>
                <div>
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" size="12" placeholder="New Password" maxlength="12" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                    <span id="passwordError" style="text-align:center;"></span>
                </div>
                <div>
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" size="12" placeholder="Confirm Password" maxlength="12" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                </div><br/>

                <div class="button-container">                 
                    <button type="submit">Reset</button>
                    <button onclick="backPHP()">Back</button>
                </div>
            </form>

            <script>
                function backPHP() {
                    window.location.href = "studLogin.php";
                }
            </script>
        </div>
    </body>
</html>

