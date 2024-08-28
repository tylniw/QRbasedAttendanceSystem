<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <style>
        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5rem;

        }
    </style>

    <body>
        <section class="container mt-5">
            <h1>Take Attendance</h1>
            <?php
            include 'library/db.php';

            if (isset($_POST["createLesson"])) {
                $lecID = $_POST["lecID"];
                $subID = $_POST["subID"];
                $lessonDate = $_POST["lessonDate"];
                $classduration = $_POST["classduration"];

                $sql = "INSERT INTO lesson (subID, lessonDate, lessonDuration) VALUES ('$subID', '$lessonDate', '$classduration')";
                mysqli_query($conn, $sql);

                if (mysqli_affected_rows($conn) > 0) {
                    echo "
                    <table class='table table-striped'>
                      <tbody>
                        <tr>
                          <td>Staff Email</td>
                          <td>$lecID</td>
                        </tr>
                        <tr>
                          <td>Subject ID</td>
                          <td>$subID</td>
                        </tr>
                        <tr>
                          <td>Lesson Date</td>
                          <td>$lessonDate</td>
                        </tr>
                        <tr>
                          <td>Lesson Duration</td>
                          <td>$classduration</td>
                        </tr>
                      </tbody>
                    </table>
                ";

                    $lessonId = mysqli_insert_id($conn);
                } else {
                    echo "Error creating lesson: " . mysqli_error($conn);
                }
            }
            ?>
            <div class="button-container">
                <button class="btn btn-outline-success w-100"  onclick="generateQR(<?php echo $lessonId; ?>);">Take Attendance</button>
                <button class="btn btn-outline-success w-100" onclick="backPHP()">Back</button>
            </div>
            <br>

            <div class="mt-4 mx-auto p-1" style="box-shadow: 0 0 5px #AAA; width: 300px; height: 300px; border-radius: 1rem; display: grid; place-content: center;">
                <img id="qrCode" src="" width="250" height="250" alt="QR Code" onerror="this.onerror=null; this.src='https://static.vecteezy.com/system/resources/thumbnails/005/720/408/small_2x/crossed-image-icon-picture-not-available-delete-picture-symbol-free-vector.jpg'; this.alt='Generate QR to Start!';"/>
            </div>
            <h3 id="code" class="text-center"></h3>


        </section>

        <script>
            var qrInterval;

            function generateQR(lessonID) {
                // Stop any previously running intervals
                clearInterval(qrInterval);

                // Generate the QR code immediately
                updateQRCode(lessonID);

                // Set interval to update QR code every 10 seconds
                qrInterval = setInterval(function () {
                    updateQRCode(lessonID);
                }, 10000);
            }

            function backPHP() {
                window.location.href = "lecGenerate.php";
            }

            function updateQRCode(lessonID) {
                $.ajax({
                    url: 'generateOTP.php',
                    type: 'POST',
                    data: {lessonID: lessonID},
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            var otp = response.otp;

                            // Generate login URL and QR code URL
                            var serverURL = "<?php echo $_SERVER['HTTP_HOST']; ?>";
                            var loginURL = "http://" + serverURL + "/QRbasedStudentAttendanceSystem/studLogin.php?otp=" + otp;
                            var qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + loginURL;

                            // Set the QR code image source
                            $('#qrCode').attr('src', qrCodeUrl);
                            $('#code').text(otp);
                        }
                    }
                });
            }
        </script>
    </body>
</html>
