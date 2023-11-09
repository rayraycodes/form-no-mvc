<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "webtech_registration");

function getAvailableSlots($conn) {
    // Query to get the count of registrations for each timeslot
    $sql = "SELECT time_slot, COUNT(*) as count FROM student_registrations GROUP BY time_slot";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Initialize an array to hold the count of registrations for each timeslot
        $registrations = array_fill(1, 6, 0);

        while ($row = mysqli_fetch_assoc($result)) {
            $registrations[$row['time_slot']] = $row['count'];
        }

        // Calculate the available slots for each timeslot
        $availableSlots = array_map(function($count) {
            return 6 - $count;
        }, $registrations);

        return $availableSlots;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

$availableSlots = getAvailableSlots($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Web Technology Class Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Web Technology Class Registration</h1>
        <form action="registration.php" method="post">
        <label for="id">UM ID:</label>
        <input type="text" id="id" name="id" pattern="\d{8}" title="Student ID must be 8 digits" required>

        <label for="firstname">First Name:</label>
         <input type="text" id="firstname" name="firstname" pattern="[A-Za-z]+" title="First name should only contain letters" required>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" pattern="[A-Za-z]+" title="Last name should only contain letters" required>

        <label for="projecttitle">Project Title:</label>
        <input type="text" id="projecttitle" name="projecttitle" title="Project title is required" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Email should be in the format something@something.something" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" pattern="\d{3}-\d{3}-\d{4}" title="Phone number should be in the format 000-000-0000" required>

        <!-- Your HTML form -->
        <label for="timeslot">Select Time Slot:</label>
        <select id="timeslot" name="timeslot">
            <?php
            for ($i = 1; $i <= 6; $i++) {
                $timeSlotLabel = "4/19/2070, " . (($i % 3) + 6) . ":00 PM – " . (($i % 3) + 7) . ":00 PM";
                if ($availableSlots[$i] == 0) {
                    echo "<option value='$i' disabled>$timeSlotLabel, FULL</option>";
                } else {
                    echo "<option value='$i'>$timeSlotLabel, {$availableSlots[$i]} seats remaining</option>";
                }
            }
            ?>
        </select>

    <input type="submit" value="Register">
</form>
    </div>

    <script>
        document.getElementById('phone').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\dA-Z]/ig, '').replace(/(.{3})(.{3})(.{4})/, '$1-$2-$3');
        });
        document.getElementById('id').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '').slice(0, 8);
        });
        document.getElementById('id').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '').slice(0, 8);

            var umid = e.target.value;

            // Fetch the time slots from the server
            fetch('get_timeslots.php?umid=' + umid)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the received data to the console

                    var select = document.getElementById('timeslot');
                    select.innerHTML = ''; // Clear the current options

                    // Add the new options
                    data.forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = item.value;
                        option.text = item.text;
                        option.disabled = item.disabled;
                        select.add(option);
                    });
                });
        });
    </script>
</body>
</html>