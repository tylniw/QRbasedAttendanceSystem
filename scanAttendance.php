<?php

// Start the session
session_start();

// Check if the student is logged in and their role is 'student'
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    // Redirect to the login page
    header('Location: studLogin.php');
    exit();
}

// Check if the OTP is provided
if (!isset($_GET['otp'])) {
    $error_message = "Invalid code, please scan again.";
    echo "<script>alert('$error_message')</script>";
    exit();
}

$otp = $_GET['otp'];

// Get the lesson ID and student ID for the current session and OTP
include 'library/db.php';

// Get the lesson ID and student ID for the current session and OTP
$sql = "SELECT lesson.lessonID, student.studID FROM lesson 
    INNER JOIN subject ON lesson.subID = subject.subID 
    INNER JOIN attendance ON lesson.lessonID = attendance.lessonID 
    INNER JOIN student ON attendance.studID = student.studID 
    WHERE lesson.lessonOTP = '$otp' AND student.studID = '{$_SESSION['studID']}'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // The OTP is invalid or the student is not enrolled in the lesson
    echo 'Invalid OTP.';
    exit();
}

$row = mysqli_fetch_assoc($result);
$lessonID = $row['lessonID'];
$studID = $row['studID'];

// Check if the student has already marked their attendance for this lesson
$sql = "SELECT * FROM attendance WHERE studID = '$studID' AND lessonID = '$lessonID'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // The student has already marked their attendance for this lesson
    echo 'You have already marked your attendance for this lesson.';
    exit();
}

// Insert a new attendance record for the student and lesson
$sql = "INSERT INTO attendance (studID, lessonID) VALUES ('$studID', '$lessonID')";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Attendance marked successfully
    echo 'Attendance marked successfully.';
} else {
    // Attendance marking failed
    echo 'Error marking attendance: ' . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);






