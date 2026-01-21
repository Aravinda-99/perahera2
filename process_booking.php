<?php
include 'asset/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $locId = $_POST['location_id']; // ID eka enawa form eken
    $seat = $_POST['seat_number'];
    $name = $_POST['full_name'];
    $phone = $_POST['phone_number'];

    // Double check: Seat eka kalin book welada balanna (Security ekata)
    $check = "SELECT * FROM bookings WHERE location_id = $locId AND seat_number = $seat";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        echo "<script>alert('Sorry! This seat was just booked by someone else.'); window.location.href='index.php';</script>";
    } else {
        // Booking eka save karanna
        $stmt = $conn->prepare("INSERT INTO bookings (location_id, seat_number, full_name, phone_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $locId, $seat, $name, $phone);

        if ($stmt->execute()) {
            echo "<script>alert('Booking Successful!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>