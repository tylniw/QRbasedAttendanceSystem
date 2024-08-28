<?php

require_once "library/db.php";

$lessonID = $_POST["lessonID"] ?? "";

if ($lessonID === "") {
    echo json_encode(array('error' => 'Lesson ID not provided.'));
} else {

    // Generate a unique OTP
    do {
        $otp = rand(100000, 999999);
        $sql = "SELECT * FROM lesson WHERE lessonOTP = '$otp'";
        $result = $conn->query($sql);
    } while ($result->num_rows > 0);

    // Update the OTP into the lesson table
    $sql = "UPDATE lesson SET lessonOTP = '$otp' WHERE lessonID = '$lessonID'";
    $result = $conn->query($sql);

    if ($result) {
        // Return the OTP as a JSON response
        echo json_encode(array('otp' => $otp));
    } else {
        echo json_encode(array('error' => 'Failed to update OTP.'));
    }
}

?>
