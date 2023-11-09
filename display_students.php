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

// Query the database to retrieve registered students' information
$sql_query = "SELECT student_id, first_name, last_name, project_title, email, phone, time_slot FROM student_registrations";

$result = mysqli_query($conn, $sql_query);

// Check if there are any registered students
if (mysqli_num_rows($result) > 0) {
    echo "<h1>List of Registered Students</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Project Title</th><th>Email</th><th>Phone</th><th>Time Slot</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["student_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["project_title"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["time_slot"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No students have registered yet.";
}

// Close the database connection
mysqli_close($conn);
?>