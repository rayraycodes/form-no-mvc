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

// Get data from the form
$student_id = $_POST['id'];
$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$project_title = $_POST['projecttitle'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$time_slot = $_POST['timeslot'];

// Check if the student has already registered
$sql_check = "SELECT * FROM student_registrations WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result) > 0) {
    // Student is already registered
    echo "You have already registered for a time slot. Do you want to change your registration?";
    echo '<form action="update_registration.php" method="post">';
    echo '<input type="hidden" name="student_id" value="' . $student_id . '">';
    echo '<input type="hidden" name="new_time_slot" value="' . $time_slot . '">';
    echo '<input type="submit" value="Change Registration">';
    echo '</form>';
} else {
    // Student is not registered, insert data into the database
    $sql_insert = "INSERT INTO student_registrations (student_id, first_name, last_name, project_title, email, phone, time_slot)
                   VALUES ('$student_id', '$first_name', '$last_name', '$project_title', '$email', '$phone', $time_slot)";

    if (mysqli_query($conn, $sql_insert)) {
        echo "Registration successful. You have been registered for Time Slot $time_slot.";
        // Display the list of registered students
        include 'display_students.php';
    } else {
        echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
    }
}

        // Get the form data
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $projecttitle = $_POST['projecttitle'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Validate the form data
        if (!preg_match("/^[0-9]{8}$/", $id)) {
            die("Invalid ID");
        }

        if (!preg_match("/^[A-Za-z]+$/", $firstname)) {
            die("Invalid first name");
        }

        if (!preg_match("/^[A-Za-z]+$/", $lastname)) {
            die("Invalid last name");
        }

        if (empty($projecttitle)) {
            die("Project title is required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email");
        }

        if (!preg_match("/^[0-9]+$/", $phone)) {
            die("Invalid phone number");
        }





// Close the database connection
mysqli_close($conn);
?>