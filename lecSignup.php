<?php
session_start();
include 'library/db.php';


if (isset($_POST['signup'])) {
    $lecID = $_POST['id'];
    $lecPassword = $_POST['pw'];
    $confirmPassword = $_POST['confirmPassword'];
    $lecName = $_POST['name'];
    $lecPhone = $_POST['phonenumber'];

    // Validate user input
    if (empty($lecID) || empty($lecPassword) || empty($confirmPassword) || empty($lecName) || empty($lecPhone)) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: All fields are required.";
    } else if (strlen($lecPassword) < 8) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: Password must be at least 8 characters long.";
    } else if ($lecPassword !== $confirmPassword) {
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: Passwords do not match.";
    } else {
        // Check if the lecID already exists in the database
        $query = "SELECT * FROM lecturer WHERE lecID = '$lecID'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Error: Staff Email already exists.";
        } else {
            $sql = "INSERT INTO lecturer (lecID, lecPassword, lecName, lecPhone)
                    VALUES ('$lecID', MD5('$lecPassword'), '$lecName', '$lecPhone')";

            if (mysqli_query($conn, $sql)) {
                header("Location: lecLogin.php");
                exit();
            } else {
                // Display an error message with a Font Awesome icon
                $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Invalid ID or password. Please try again.";
            }
        }
    }

    mysqli_close($conn);

    // Display the error message (if any)
    if (!empty($error_message)) {
        echo "<div style=\"text-align:center;\">$error_message</div>";
    }
}
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">        
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">       
        <title>Staff Sign Up</title>
        <link rel="stylesheet" type="text/css" href="library/lecMystyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">  
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <style>
            .form-container {
                padding: .8rem 1rem;
                width: 40vw;
                text-align:center;
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
                grid-template-columns: repeat(3, 1fr);
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

            .error-container {
                background-color: #f44336; /* Red color */
                color: white; /* Text color */
                font-size: 1rem;
                height: 1.5rem;
                text-align:center;
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
                <form action="" class="form" method="POST"> <!-- added method attribute -->
                    <div class="form-grid">   
                        <div>
                            <label>Staff Email:</label>
                            <input type="email" name="id" value="" size="10" placeholder="Email" style="text-transform: lowercase;" required pattern="[a-zA-Z0-9._%+-]+@ypccollege\.edu\.my$" title="Example format: example@ypccollege.edu.my"/>
                        </div>
                        <div>
                            <label>Password:</label>
                            <input type="password" name="pw" value="" size="10" placeholder="Password" maxlength="10" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                        </div>
                        <div>
                            <label for="confirmPassword">Confirm Password:</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" maxlength="10" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit." placeholder="Confirm Password" required>
                        </div>
                        <div>
                            <label>Name:</label>
                            <input type="name" name="name" value="" size="10" placeholder="Name" style="text-transform: uppercase;" required/>
                        </div>
                        <div>
                            <label>Phone Number:</label>
                            <input type="tel" name="phonenumber" value="" size="10"  maxlength="11" placeholder="Phone Number" required/>
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
                        window.location.href = "lecLogin.php";
                    }

                    var input = document.getElementById("phonenumber");
                    input.addEventListener("input", function (event) {
                        var value = event.target.value;
                        var newValue = value.replace(/-/g, "");
                        if (value !== newValue) {
                            event.target.value = newValue;
                        }
                    });

                </script>
            </div>
        </div>
    </body>
</html>