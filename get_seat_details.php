<?php
// get_seat_details.php
include 'asset/db.php'; // Oyage connection path eka hariyatama danna

header('Content-Type: application/json');

if (isset($_POST['location_id'])) {
    $locId = $_POST['location_id'];

    // 1. Location eke Total Seats ganna
    $sql_loc = "SELECT total_seats, location_name FROM locations WHERE id = $locId";
    $result_loc = $conn->query($sql_loc);
    $locationData = $result_loc->fetch_assoc();

    // 2. Danata Book wela thiyena seats tika ganna
    $sql_book = "SELECT seat_number FROM bookings WHERE location_id = $locId";
    $result_book = $conn->query($sql_book);

    $bookedSeats = [];
    if ($result_book->num_rows > 0) {
        while($row = $result_book->fetch_assoc()) {
            $bookedSeats[] = $row['seat_number']; // Array ekata add wenawa
        }
    }

    // JSON widihata eliyata denawa
    echo json_encode([
        'total_seats' => $locationData['total_seats'],
        'location_name' => $locationData['location_name'],
        'booked_seats' => $bookedSeats
    ]);
}

$conn->close();
?>