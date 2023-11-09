<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$id = isset($_GET['umid']) ? $_GET['umid'] : '';
// Database connection
$db = new PDO('mysql:host=localhost;dbname=webtech_registration', 'root', '');

function checkIDExists($id) {
    global $db;

    $stmt = $db->prepare("SELECT * FROM student_registrations WHERE id = ?");
    $stmt->execute([$id]);

    return $stmt->rowCount() > 0;
}
function checkIDAssociatedWithSlot($id, $slot) {
    global $db;

    $stmt = $db->prepare("SELECT * FROM student_registrations WHERE id = ? AND time_slot = ?");
    $stmt->execute([$id, $slot]);

    return $stmt->rowCount() > 0;
}
// Fetch the available slots from your database
$stmt = $db->prepare("SELECT * FROM student_registrations");
$stmt->execute();

$availableSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = [];

for ($i = 1; $i <= 6; $i++) {
    $timeSlotLabel = "4/19/2070, " . (($i % 3) + 6) . ":00 PM – " . (($i % 3) + 7) . ":00 PM";
    $remainingSeats = 6 - $availableSlots[$i]['time_slot'];
    $isFull = $remainingSeats <= 0;
    $isIDExists = strlen($id) == 8 ? checkIDExists($id) : false;
    $isIDAssociatedWithThisSlot = strlen($id) == 8 ? checkIDAssociatedWithSlot($id, $i) : false;

    $options[] = [
        'value' => $i,
        'text' => $isFull ? "$timeSlotLabel, FULL" : "$timeSlotLabel, $remainingSeats seats remaining",
        'disabled' => !$isIDExists || ($isFull && !$isIDAssociatedWithThisSlot)
    ];
}

header('Content-Type: application/json');
echo json_encode($options);