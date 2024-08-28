<?php
session_start();
$_SESSION['user'] = 'staff';
$_SESSION['id'] = '';
include 'library/db.php';

if (isset($_POST["login"])) {
    // Get the values of the form inputs
    $id = htmlspecialchars($_POST['id']);
    $pw = htmlspecialchars($_POST['pw']);

    // Run the query to check if the user exists
    $search = "SELECT * FROM lecturer WHERE lecID='$id' and lecPassword= MD5('$pw')";
    $result = mysqli_query($conn, $search);

    // Check if the query returned any rows
    if (mysqli_num_rows($result) > 0) {
        // Set the session variables
        $_SESSION['id'] = $id;
        $_SESSION['loggedin'] = true;

        // Redirect to the dashboard
        header("Location: lecDashboard.php");
        exit();
    } else {
        // Display an error message with a Font Awesome icon
        $error_message = "<i class=\"fa fa-times-circle\" style=\"color:red;\"></i> Invalid ID or password. Please try again.";
        echo "<div style=\"text-align:center;\">$error_message</div>";
    }
}

// close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Staff Login</title>
        <link rel="stylesheet" type="text/css" href="library/lecMystyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
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
    </head> 
    <body>
        <div class="form-container">
            <img src="library/ypclogo.jpg" alt="" style="width:80%;"/>
            <h1 class="heading" style="font-size:28px;
                padding-bottom: 10px;">QR based YPC Student Attendance System</h1>
            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" method="POST" class="form">            
                <div class="form-grid">
                    <div>
                        <label>Staff Email:</label>
                        <input type="email" name="id" value="" size="10" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@ypccollege\.edu\.my$" title="Example format: example@ypccollege.edu.my"/>
                    </div>

                    <div>
                        <label>Password:</label>
                        <input type="password" name="pw" value="" size="10" placeholder="Password" minlength="8" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least 8 characters including one uppercase letter, one lowercase letter, and one digit.">
                        <a href="lecForgotPassword.php" style="text-align:left;">Forget Your Password ?</a>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" name="login" id="login">Login</button>
                    <button id="signup">Sign Up</button>
                </div>
            </form>

            <script>
                window.history.forward();
                ;

                var btn = document.getElementById('signup');
                btn.addEventListener('click', function () {
                    document.location.href = 'lecSignup.php';
                });
            </script>
        </div>   
    </body>
</html>