<?php
// Start session to access logged-in user data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perahera Festival 2026 - Book Tickets</title>
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* === PAYMENT MODAL STYLES === */
        .payment-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .payment-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            position: relative;
            text-align: center;
            animation: popUp 0.3s ease-out;
        }

        @keyframes popUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .payment-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .payment-inputs input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .card-row {
            display: flex;
            gap: 10px;
        }

        .btn-pay {
            width: 100%;
            padding: 15px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-pay:hover {
            background-color: #219150;
        }
        
        /* Close button for modal */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #555;
        }
    </style>
</head>
<body>

    <?php include 'asset/header.php'; ?>

    <section class="hero">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="hero-video/v1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-content-h1">Experience the Sacred Splendor of the Gangaramaya Perahera!</h1>
            <p class="hero-content-p">Reserve your seats online and witness Sri Lanka’s most revered cultural procession.</p>
        </div>
    </section>

    <section class="locations-wrapper">
        <section class="search-container">
            <div class="search-card">
                <h1 class="search-card-h1">Select Your Location To Book Your Ticket</h1>
            </div>
        </section>

        <section class="popular-locations">
            <div class="section-header">
                <h2><u>Popular</u> Locations</h2>
            </div>
            <div class="location-grid">
                <div class="location-card" onclick="toggleBookingSection(1)" style="background-image: url('https://images.unsplash.com/photo-1513622470522-26c3c8a854bc?q=80&w=400')">
                    <span>Location A</span>
                </div>
                <div class="location-card" onclick="toggleBookingSection(2)" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                    <span>Location B</span>
                </div>
                <div class="location-card" onclick="toggleBookingSection(3)" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                    <span>Location C</span>
                </div>
                <div class="location-card" onclick="toggleBookingSection(4)" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                    <span>Location D</span>
                </div>
            </div>
        </section>
    </section>

    <section id="booking-panel" class="booking-panel" style="display:none;">
        <div class="booking-container">
            <button class="close-panel-btn" onclick="closeBookingPanel()"><i class="fas fa-times"></i> Close</button>
            
            <h2 id="panel-title">Select Seats</h2>
            <p>Select an available seat (Green) to proceed.</p>

            <div class="booking-layout">
                <div class="seats-area">
                    <div class="seat-grid" id="seat-container"></div>
                    <p id="seat-status">Please select a seat.</p>
                </div>

                <div class="form-area">
                    <form id="main-booking-form" action="process_booking.php" method="POST" class="booking-form">
                        <input type="hidden" name="location_id" id="form-location-id">
                        <input type="hidden" name="seat_number" id="form-seat">
                        
                        <div style="background: #eef; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ccd;">
                            <p style="margin:5px 0; font-weight:bold;">Ticket Price: Rs. <span id="display-price">0.00</span></p>
                            <p style="margin:5px 0; color: green; font-size: 14px; display:none;" id="discount-msg">
                                Discount: - Rs. <span id="display-discount">0</span>
                            </p>
                            <hr style="border-top: 1px solid #ccc;">
                            <p style="margin:5px 0; font-size: 1.2rem; font-weight:bold; color: var(--primary);">
                                Total: Rs. <span id="display-total">0.00</span>
                            </p>
                        </div>

                        <input type="hidden" name="final_price" id="input-final-price">
                        <input type="hidden" name="discount_amount" id="input-discount-amount">

                        <label>Full Name</label>
                        <input type="text" id="cust-name" name="full_name" required placeholder="Enter Name">
                        
                        <label>Phone Number</label>
                        <input type="text" id="cust-phone" name="phone_number" required placeholder="07x xxxxxxx">

                        <label>Referral Code (Optional)</label>
                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                            <input type="text" name="agent_code" id="agent-code" placeholder="Enter Agent Code">
                            <button type="button" onclick="applyCode()" style="background: #333; color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer;">Apply</button>
                        </div>
                        <small id="code-status" style="display: block; margin-bottom: 15px; font-weight:bold;"></small>
                        
                        <button type="button" class="btn-submit" onclick="openPaymentModal()">Proceed to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="paymentModal" class="payment-modal">
        <div class="payment-content">
            <span class="close-btn" onclick="closePaymentModal()">&times;</span>
            
            <div class="payment-header">
                <h3><i class="fas fa-lock"></i> Secure Payment</h3>
                <p>Total to Pay: <strong style="color: #27ae60; font-size: 1.2rem;">Rs. <span id="pay-amount-display">0.00</span></strong></p>
            </div>

            <div class="payment-inputs">
                <input type="text" placeholder="Card Number (Dummy)" maxlength="16">
                <div class="card-row">
                    <input type="text" placeholder="MM/YY" maxlength="5">
                    <input type="text" placeholder="CVC" maxlength="3">
                </div>
                <input type="text" placeholder="Cardholder Name">
            </div>

            <button class="btn-pay" onclick="confirmPaymentAndBook()">Pay Now</button>
        </div>
    </div>

    <?php include 'asset/footer.php'; ?>

    <script>
    // ==========================================
    // 0. CHECK LOGIN STATUS (PHP to JS)
    // ==========================================
    // User log wela innam 'true', nathnam 'false' kiyana eka methana set wenawa
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    // ==========================================
    // GLOBAL VARIABLES
    // ==========================================
    let currentTicketPrice = 0;
    let discountPercentage = 0; 
    let discountAmountInRupees = 0;
    let finalPayableAmount = 0;

    // ==========================================
    // 1. TOGGLE BOOKING PANEL
    // ==========================================
    function toggleBookingSection(locationId) {
        
        // --- NEW: CHECK LOGIN FIRST ---
        if (!isLoggedIn) {
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please log in to book your seats.',
                showCloseButton: true, // <--- Methanatath damma
                confirmButtonText: 'Login Now',
                confirmButtonColor: '#FF9933',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php'; 
                }
            });
            return; 
        }

        // --- Log wela nam pahala tika wada karanawa ---

        const panel = document.getElementById('booking-panel');
        const title = document.getElementById('panel-title');
        const formLocId = document.getElementById('form-location-id');
        const seatContainer = document.getElementById('seat-container');
        
        document.querySelectorAll('.location-card').forEach(card => card.classList.remove('active-loc'));
        
        panel.style.display = 'block';
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });

        formLocId.value = locationId;
        seatContainer.innerHTML = "<p>Loading available seats...</p>";
        title.innerText = "Checking availability...";
        
        // Reset Logic
        discountPercentage = 0;
        discountAmountInRupees = 0;
        document.getElementById('agent-code').value = '';
        document.getElementById('discount-msg').style.display = 'none';
        document.getElementById('code-status').innerText = '';
        
        // Auto Fill Logic
        const loggedInName = "<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>";
        const loggedInPhone = "<?php echo isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : ''; ?>";

        if(loggedInName !== "") document.getElementById('cust-name').value = loggedInName;
        if(loggedInPhone !== "") document.getElementById('cust-phone').value = loggedInPhone;

        const formData = new FormData();
        formData.append('location_id', locationId);

        fetch('get_seat_details.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            title.innerText = "Book Seats for " + data.location_name;
            currentTicketPrice = parseFloat(data.ticket_price);
            updatePriceDisplay();
            generateSeats(data.total_seats, data.booked_seats);
        })
        .catch(error => {
            console.error('Error:', error);
            seatContainer.innerHTML = "<p style='color:red'>Error loading data.</p>";
        });
    }

    function closeBookingPanel() {
        document.getElementById('booking-panel').style.display = 'none';
        document.querySelector('.popular-locations').scrollIntoView({ behavior: 'smooth' });
    }

    // ==========================================
    // 2. REFERRAL CODE & PRICE UPDATE
    // ==========================================
    function applyCode() {
        const code = document.getElementById('agent-code').value;
        const statusMsg = document.getElementById('code-status');

        if(code.trim() === "") return;

        const formData = new FormData();
        formData.append('agent_code', code);

        fetch('check_agent.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                discountPercentage = parseFloat(data.discount);
                statusMsg.innerText = "Success! " + discountPercentage + "% Discount.";
                statusMsg.style.color = "green";
                updatePriceDisplay();
            } else {
                discountPercentage = 0;
                statusMsg.innerText = "Invalid Code";
                statusMsg.style.color = "red";
                updatePriceDisplay();
            }
        });
    }

    function updatePriceDisplay() {
        discountAmountInRupees = (currentTicketPrice * discountPercentage) / 100;
        finalPayableAmount = currentTicketPrice - discountAmountInRupees;

        document.getElementById('display-price').innerText = currentTicketPrice.toFixed(2);
        document.getElementById('display-total').innerText = finalPayableAmount.toFixed(2);
        
        document.getElementById('input-final-price').value = finalPayableAmount;
        document.getElementById('input-discount-amount').value = discountAmountInRupees;

        if(discountPercentage > 0) {
            document.getElementById('discount-msg').style.display = 'block';
            document.getElementById('display-discount').innerText = discountAmountInRupees.toFixed(2);
        } else {
            document.getElementById('discount-msg').style.display = 'none';
        }
    }

    // ==========================================
    // 3. SEATS & MODAL
    // ==========================================
    function generateSeats(totalSeats, bookedSeats) {
        const container = document.getElementById('seat-container');
        container.innerHTML = ""; 

        for (let i = 1; i <= totalSeats; i++) {
            let seatDiv = document.createElement('div');
            seatDiv.innerText = i;
            seatDiv.classList.add('seat');

            if (bookedSeats.includes(i.toString()) || bookedSeats.includes(i)) {
                seatDiv.classList.add('booked');
            } else {
                seatDiv.onclick = function() { selectSeat(this, i); };
            }
            container.appendChild(seatDiv);
        }
    }

    function selectSeat(element, seatNum) {
        document.querySelectorAll('.seat.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('form-seat').value = seatNum;
        document.getElementById('seat-status').innerText = "Selected Seat: " + seatNum;
        document.getElementById('seat-status').style.color = "var(--primary)";
    }

    function openPaymentModal() {
        const seat = document.getElementById('form-seat').value;
        const name = document.getElementById('cust-name').value;
        const phone = document.getElementById('cust-phone').value;

        if (!seat) {
            alert("Please select a seat first!");
            return;
        }
        if (name.trim() === "" || phone.trim() === "") {
            alert("Please enter your Name and Phone Number.");
            return;
        }

        document.getElementById('pay-amount-display').innerText = finalPayableAmount.toFixed(2);
        document.getElementById('paymentModal').style.display = 'flex';
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
    }

    function confirmPaymentAndBook() {
        const btn = document.querySelector('.btn-pay');
        btn.innerText = "Processing...";
        btn.disabled = true;
        document.getElementById('main-booking-form').submit();
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('paymentModal');
        if (event.target == modal) closePaymentModal();
    }
</script>

</body>
</html>