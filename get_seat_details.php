<?php
include 'asset/db.php';
header('Content-Type: application/json');

if (isset($_POST['location_id'])) {
    $locId = $_POST['location_id'];

    // Select ticket_price as well
    $sql_loc = "SELECT total_seats, location_name, ticket_price FROM locations WHERE id = $locId";
    $result_loc = $conn->query($sql_loc);
    $locationData = $result_loc->fetch_assoc();

    // Get Booked Seats
    $sql_book = "SELECT seat_number FROM seats WHERE location_id = $locId AND status = 'booked'";
    $result_book = $conn->query($sql_book);

    $bookedSeats = [];
    if ($result_book->num_rows > 0) {
        while($row = $result_book->fetch_assoc()) {
            $bookedSeats[] = $row['seat_number'];
        }
    }

    echo json_encode([
        'total_seats' => $locationData['total_seats'],
        'location_name' => $locationData['location_name'],
        'ticket_price' => $locationData['ticket_price'], // Price eka pass karanawa
        'booked_seats' => $bookedSeats
    ]);
}
$conn->close();
?>