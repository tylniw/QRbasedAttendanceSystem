<?php
session_start();
include 'library/db.php';


// Retrieve data from database
$sql = "SELECT lecName, lecID, lecPhoto, lecPhone, lecPassword FROM lecturer WHERE lecID = '{$_SESSION['id']}'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Fetch the first row
    $row = mysqli_fetch_assoc($result);
} else {
    $error_message = "No results found.";
    echo "<script>alert('$error_message')</script>";
}

// Update data
if (isset($_POST['submit'])) {
    $phone = $_POST['phone'];
    $new_password = $_POST['password'];

    // Check if a new password is provided
    if (!empty($new_password)) {
        $password = md5($new_password);
    } else {
        $password = $row['lecPassword'];
    }

    // Update phone and password
    $sql = "UPDATE lecturer SET lecPhone=?, lecPassword=? WHERE lecID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $phone, $password, $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    // Update photo
    if ($_FILES['lecPhoto']['error'] == 0) {
        $image = file_get_contents($_FILES['lecPhoto']['tmp_name']);
        $sql = "UPDATE lecturer SET lecPhoto=? WHERE lecID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "bi", $image, $_SESSION['id']);
        mysqli_stmt_send_long_data($stmt, 0, $image);
        mysqli_stmt_execute($stmt);
        $affected_rows += mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
    }

    if ($affected_rows > 0) {
        header("Location: lecProfile.php");
        exit();
    } else {
        $error_message = "Record not updated. Please try again.";
        echo "<script>alert('$error_message')</script>";
    }
}


// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Edit Lecturer Profile</title>
        <link href="library/lecMystyle.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel = "icon" href = "https://www.ypccollege.edu.my/wp-content/uploads/2021/02/favicon.ico" type = "image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#updateBtn').click(function () {
                    $('form').submit();
                });
            });
        </script>
        <style>
            input{
                font-size: 1rem;
                margin-bottom: 10px;
                width: 150px;
            }

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
            .container {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.3);
                max-width: 60%;
                margin: 0 auto;
                text-align: center;
                font-family: Jost;
                padding: .5rem .5rem;
                padding-bottom: 8rem;
                position: absolute;
                top:100px;
                left: 290px;
            }

            h1{
                font-size: 35px;
            }
            .input-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                grid-gap: 10px;
                margin-bottom: 10px;
            }

            input[type="text"], select {
                font-size: 20px;
                border-radius: 0rem;
                padding: .0.8rem .2rem .5rem 1.5rem;
                background-color: rgba(255, 255, 255, .4);
                border: rgba(255, 255, 255, .5) 2px solid;
                max-width: 100%;
            }

            button {
                border: none;
                outline: 0;
                display: inline-block;
                padding: 8px;
                color: white;
                background-color: #174304;
                text-align: center;
                cursor: pointer;
                min-width: 150px;
                font-size: 18px;
                border-radius: 0rem;
            }

            .button-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }

            button:hover, a:hover {
                opacity: 0.50;
                transistion： 0.5s;
            }

            input,
            select {
                font-size: 20px;
                border-radius: 0rem;
                width: 400px;
                padding: .0.8rem .2rem .5rem 1.5rem;
                background-color: rgba(255, 255, 255, .4);
                border: rgba(255, 255, 255, .5) 2px solid;
            }

            a {
                text-decoration: none;
                font-size: 22px;
                color: black;
            }

            .column {
                float: left;
                width: 50%;
                padding: 18px;
                height: 350px;
                text-align: left;

            }

            /* Clear floats after the columns */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }


            input[type="file"] {
                place-items: center;
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
            <form method="post" action="lecEditProfile.php" enctype="multipart/form-data">
                <div class="container">
                    <h1>Edit Staff Information</h1><br/>
                    <div class="row">
                        <div class="column">
                            <img src="<?php
if (!empty($row['lecPhoto'])) {
    $img_data = base64_encode($row['lecPhoto']);
    echo 'data:image/jpeg;base64,' . $img_data;
} else {
    echo 'path/to/default/photo.jpg'; // path to default photo
}
?>" alt="Insert Profile Photo" style="width: 90%;padding-left: 40px;">
                            <input type="file" class="form-control" name="lecPhoto" style="padding: 0rem; border: 0px; background: transparent; position:relative; padding-left:68px; top:20px;">
                        </div>
                        <div class="column">
                            <div>
                                <label>Name:</label>
                                <input type="text" name="name" value="<?php echo $row['lecName']; ?>" size="10" readonly style="cursor: pointer;"/>
                            </div>                        
                            <div>
                                <label>Email:</label>
                                <input type="text" name="email" value="<?php echo $row['lecID']; ?>" size="10" readonly style="cursor: pointer;"/>
                            </div>
                            <div>
                                <label>Phone Number:</label>
                                <input type="text" name="phone" value="<?php echo $row['lecPhone']; ?>" size="10" />
                            </div>                        

                            <div>
                                <label>Current/ New Password:</label>
                                <input type="password" name="password" value="" size="10" placeholder="Current/ New Password" value="<?php echo $row['lecPassword']; ?>" required/>
                            </div><br/>

                            <div class="button-container">
                                <button id="updateBtn" name="submit" type="submit">Update</button>
                                <button type="reset">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>