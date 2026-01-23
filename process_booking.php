<?php
// 1. START SESSION (Log wela inna User ID eka ganna meka aniwarya wenawa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. INCLUDE DATABASE CONNECTION
// (Oyage file name eka 'db.php' nam methana 'asset/db.php' kiyala wenas karanna)
include 'asset/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- GET FORM DATA ---
    $locId = $_POST['location_id'];
    $seat = $_POST['seat_number'];
    $name = $_POST['full_name'];
    $phone = $_POST['phone_number'];
    
    // --- PAYMENT & DISCOUNT DATA ---
    $agentCode = !empty($_POST['agent_code']) ? $_POST['agent_code'] : NULL;
    $discount = $_POST['discount_amount'];
    $finalPrice = $_POST['final_price'];

    // --- GET USER ID FROM SESSION (NEW) ---
    // User log wela innawanam ID eka gannawa, nathnam NULL yanawa
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    
    // --- GENERATE TRANSACTION DATA ---
    $transactionId = "TXN_" . strtoupper(uniqid()); 
    $paymentMethod = "Card";
    $paymentStatus = "Success";

    // --- CHECK SEAT AVAILABILITY ---
    $check = "SELECT * FROM seats WHERE location_id = $locId AND seat_number = $seat AND status = 'booked'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        // ERROR: Seat eka kalin book wela
        echo_sweet_alert('error', 'Booking Failed', 'Sorry! This seat is already booked.', 'index.php');
    } else {
        
        // --- STEP A: INSERT INTO BOOKINGS TABLE ---
        // Aluth column eka: user_id methanata ekathu kala
        $stmt1 = $conn->prepare("INSERT INTO bookings (location_id, seat_number, full_name, phone_number, agent_code, discount_amount, final_price, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Types: i=int, s=string, d=double
        // "iisssddi" -> anthima 'i' eka user_id sadaha
        $stmt1->bind_param("iisssddi", $locId, $seat, $name, $phone, $agentCode, $discount, $finalPrice, $userId);
        
        if ($stmt1->execute()) {
            $newBookingId = $conn->insert_id; // Aluth Booking ID eka gannawa
            $stmt1->close();

            // --- STEP B: UPDATE SEATS TABLE ---
            $status = 'booked';
            $stmt2 = $conn->prepare("INSERT INTO seats (location_id, seat_number, status) VALUES (?, ?, ?)");
            $stmt2->bind_param("iis", $locId, $seat, $status);
            $stmt2->execute();
            $stmt2->close();

            // --- STEP C: FETCH AGENT NAME (OPTIONAL) ---
            $agentName = "None"; 
            if ($agentCode) {
                $sqlAgent = "SELECT agent_name FROM agents WHERE agent_code = '$agentCode'";
                $resAgent = $conn->query($sqlAgent);
                if ($resAgent->num_rows > 0) {
                    $rowAgent = $resAgent->fetch_assoc();
                    $agentName = $rowAgent['agent_name'];
                }
            }

            // --- STEP D: INSERT INTO PAYMENTS TABLE ---
            $stmt3 = $conn->prepare("INSERT INTO payments (booking_id, payment_status, amount_paid, referral_code, agent_name, discount_amount, payment_method, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt3->bind_param("isdssdss", $newBookingId, $paymentStatus, $finalPrice, $agentCode, $agentName, $discount, $paymentMethod, $transactionId);
            
            if ($stmt3->execute()) {
                $stmt3->close();
                
                // --- SUCCESS: SHOW SWEET ALERT ---
                // Methana thama 'string + string' error eka thibune. Dan '.' dammama hari.
                echo_sweet_alert(
                    'success', 
                    'Payment Successful!', 
                    'Your Booking is Confirmed. Transaction ID: ' . $transactionId, 
                    'index.php'
                );

            } else {
                echo "Error saving payment: " . $conn->error;
            }

        } else {
            echo "Error booking: " . $conn->error;
        }
    }
    $conn->close();
}

// --- SWEET ALERT FUNCTION ---
function echo_sweet_alert($icon, $title, $text, $redirectUrl) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<title>Processing...</title>';
    echo '<style>body { font-family: sans-serif; background-color: #f4f4f4; }</style>';
    echo '</head>';
    echo '<body>';
    echo '<script>';
    echo "
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            confirmButtonText: 'OK',
            confirmButtonColor: '#27ae60',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '$redirectUrl';
            }
        });
    ";
    echo '</script>';
    echo '</body>';
    echo '</html>';
    exit(); 
}
?>