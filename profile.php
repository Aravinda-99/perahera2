<?php
session_start();
include 'asset/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// User ge bookings ganna query eka
// Bookings table eka Locations table eka ekka JOIN karanawa location name eka ganna
$sql = "SELECT b.id, b.booking_date, b.seat_number, l.location_name, b.final_price 
        FROM bookings b 
        JOIN locations l ON b.location_id = l.id 
        WHERE b.user_id = ? 
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Perahera Festival</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include 'asset/header.php'; ?>

    <div class="profile-container">
    
    <div class="user-header">
        <div>
            <h2>Hello, <?php echo htmlspecialchars($user_name); ?>!</h2>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user_email); ?></p>
        </div>
    </div>

    <h3 class="table-title">My Bookings History</h3>

    <div class="table-responsive">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Location</th>
                    <th>Seat Number</th>
                    <th>Booking Date/Time</th>
                    <th>Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ref-col"><?php echo $refNo; ?></td>
                            <td><?php echo htmlspecialchars($row['location_name']); ?></td>
                            <td><span style="font-weight:bold;">No. <?php echo htmlspecialchars($row['seat_number']); ?></span></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td>
                                <a href="view_ticket.php?id=<?php echo $row['id']; ?>" class="view-ticket-btn">
                                    View Ticket <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-bookings">You have no bookings yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
    <?php include 'asset/footer.php'; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>