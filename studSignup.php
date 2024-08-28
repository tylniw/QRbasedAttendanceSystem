<?php
session_start();
include 'library/db.php';

if (isset($_POST['signup'])) {
    $studID = mysqli_real_escape_string($conn, $_POST['id']);
    $studPassword = mysqli_real_escape_string($conn, $_POST['pw']);
    $studConfirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    $studName = mysqli_real_escape_string($conn, $_POST['name']);
    $studEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $studPhone = mysqli_real_escape_string($conn, $_POST['phonenumber']);

    // Validate user input
    if (empty($studID) || empty($studPassword) || empty($studName) || empty($studPhone)) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: All fields except email are required.";
    } else if (!filter_var($studEmail, FILTER_VALIDATE_EMAIL)) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: Invalid email format.";
    } else if ($studPassword !== $studConfirmPassword) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: Passwords do not match.";
    } else {
        // Hash the password using the md5 algorithm
        $hashed_password = md5($studPassword);

// Prepare the SQL statement using prepared statements
        $stmt = mysqli_prepare($conn, "INSERT INTO student (studID, studPassword, studName, studEmail, studPhone) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $studID, $hashed_password, $studName, $studEmail, $studPhone);

// Execute the prepared statement
        try {
            mysqli_stmt_execute($stmt);
            header("Location: studLogin.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            // Check if the error message is due to a duplicate entry
            if ($e->getCode() == 1062) {
                $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> The student ID " . $studID . " already exists.";
            } else {
                $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: An unexpected error occurred. Please try again later.";
                error_log("Error executing SQL query: " . $e->getMessage());
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);

    // Display the error message (if any)
    if (!empty($error_message)) {
        echo "<div style=\"text-align:center;\">$error_message</div></br/>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <meta charset="UTF-8">
        <title>Student Sign Up</title>
        <link rel="stylesheet" type="text/css" href="library/studMystyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <style>

            .form-container {
                padding: .8rem 1rem;
                width: 40vw;
                text-align:center;
            }

            .form-grid {
                padding: 1rem;
            }

            .form-grid label {
                text-align: left;
                margin-bottom: 0;
            }

            .button-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
            }

            body {
                display: grid;
                place-content: center;
                height: 100vh;
                background: url('https://wallpapercave.com/wp/wp6517811.jpg')no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

            input,label,select{
                font-size: 1.1rem;
            }

            input[type="name"] {
                text-align: left;
            }

            input[type="tel"] {
                text-align: left;
            }

            input[type="email"] {
                text-align: left;
            }

        </style>
    </head> 
    <body>
        <div class="bg">
            <div class="form-container">
                <h1 class="heading" style="text-align:center;">Create An Account</h1>         
                <form action="" class="form" method="POST"> 
                    <div class="form-grid">   
                        <div>
                            <label>Student ID:</label>
                            <input type="id" name="id" value="" size="12" maxlength="12" onkeypress="return event.charCode != 32" placeholder="Student ID" required />
                        </div>
                        <div>
                            <label>Password:</label>
                            <input type="password" name="pw" value="" size="20" maxlength="20" placeholder="Password" onkeypress="return event.charCode != 32" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                        </div>
                        <div>
                            <label for="confirmPassword">Confirm Password:</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" maxlength="20" onkeypress="return event.charCode != 32" placeholder="Confirm Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                        </div>
                        <div>
                            <label>Name:</label>
                            <input type="text" name="name" value="" size="10" style="text-transform: uppercase;" placeholder="Name" required/>
                        </div>
                        <div>
                            <label>Phone Number:</label>
                            <input type="tel" name="phonenumber" value="" size="11" maxlength="11" onkeypress="return event.charCode != 32" placeholder="Phone Number" required/>
                        </div>
                        <div>
                            <label>Email:</label>
                            <input type="email" name="email" value="" size="10" onkeypress="return event.charCode != 32" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@ypccollege\.edu\.my$" title="Example format: example@ypccollege.edu.my"/>
                        </div>


                    </div>
                    <div class="button-container">
                        <button type="submit" name="signup">Sign Up</button>
                        <button type="reset">Clear</button>
                        <button onclick="backPHP()">Back</button>  
                    </div>
                </form>
                <script>
                    function backPHP() {
                        window.location.href = "studLogin.php";
                    }
                </script>
            </div>
        </div>
    </body>
</html>