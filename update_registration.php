<?php
// Establish a database connection (adjust these values as needed)
$host = "localhost";
$username = "root";
$password = "";
$database = "webtech_registration";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get data from the confirmation form
$student_id = $_POST['student_id'];
$new_time_slot = $_POST['new_time_slot'];

// Update the registration with the new time slot
$sql_update = "UPDATE student_registrations SET time_slot = $new_time_slot WHERE student_id = '$student_id'";

if (mysqli_query($conn, $sql_update)) {
    echo "Registration updated successfully. You are now registered for Time Slot $new_time_slot.";
    // Display the list of registered students
    include 'display_students.php';
} else {
    echo "Error: " . $sql_update . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>