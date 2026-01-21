<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>peraheara festival 2026</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
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
            <p class="hero-content-p">Reserve your seats online and witness Sri Lanka’s most revered cultural procession, where tradition and devotion come together.</p>
           
        </div>
    </section>

    <section class="search-container">
        <div class="search-card">

        <h1 class="search-card-h1">Select Your Location To Book Your Ticket</h1>
    </div>
</section>


    <!-- <section class="popular-locations">
        <div class="section-header">
            <h2><u>Popular</u> Locations</h2>
            <p>Connecting Needs with Offers for the Professional Flight Services, Book your next flight appointment with ease.</p>
        </div>
        <div class="location-grid">
            <div class="location-card" style="background-image: url('https://images.unsplash.com/photo-1513622470522-26c3c8a854bc?q=80&w=400')">
                <span>Location A</span>
            </div>
            <div class="location-card" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                <span>Location B</span>
            </div>
            <div class="location-card" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                <span>Location C</span>
            </div>
            <div class="location-card" style="background-image: url('https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=400')">
                <span>Location D</span>
            </div>
        </div>
    </section> -->

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

<section id="booking-panel" class="booking-panel">
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
                <form action="process_booking.php" method="POST" class="booking-form" onsubmit="return validateForm()">
                    <input type="hidden" name="location_id" id="form-location-id">
                    <input type="hidden" name="seat_number" id="form-seat">
                    
                    <label>Full Name</label>
                    <input type="text" name="full_name" required placeholder="Enter Name">
                    
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" required placeholder="07x xxxxxxx">
                    
                    <button type="submit" class="btn-submit">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</section>

    <?php include 'asset/footer.php'; ?>

    <script>
    function toggleBookingSection(locationId) {
        const panel = document.getElementById('booking-panel');
        const title = document.getElementById('panel-title');
        const formLocId = document.getElementById('form-location-id');
        const seatContainer = document.getElementById('seat-container');
        
        // 1. Highlight Logic (Select karapu card eka highlight karanna)
        document.querySelectorAll('.location-card').forEach(card => card.classList.remove('active-loc'));
        // NOTE: Methana 'this' wada karanne nathi nisa, click event eken pass karanna oni element eka
        // Eth saralawama highlight nathiwa wada karamu mulin.
        
        // 2. Open Panel & Scroll
        panel.style.display = 'block';
        
        // Smooth scroll to the booking panel
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // 3. Set Values
        formLocId.value = locationId;
        seatContainer.innerHTML = "<p>Loading available seats...</p>";
        title.innerText = "Checking availability...";

        // 4. AJAX Request (Parana widihamai)
        const formData = new FormData();
        formData.append('location_id', locationId);

        fetch('get_seat_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            title.innerText = "Book Seats for " + data.location_name;
            generateSeats(data.total_seats, data.booked_seats);
        })
        .catch(error => {
            console.error('Error:', error);
            seatContainer.innerHTML = "<p style='color:red'>Error loading seats.</p>";
        });
    }

    function closeBookingPanel() {
        document.getElementById('booking-panel').style.display = 'none';
        // Scroll back up to locations
        document.querySelector('.popular-locations').scrollIntoView({ behavior: 'smooth' });
    }

    // Seat Generation (Parana code ekamai, podi CSS class change ekak witarai)
    function generateSeats(totalSeats, bookedSeats) {
        const container = document.getElementById('seat-container');
        container.innerHTML = ""; 

        for (let i = 1; i <= totalSeats; i++) {
            let seatDiv = document.createElement('div');
            seatDiv.innerText = i;
            seatDiv.classList.add('seat');

            if (bookedSeats.includes(i.toString()) || bookedSeats.includes(i)) {
                seatDiv.classList.add('booked');
                seatDiv.title = "Already Booked";
            } else {
                seatDiv.onclick = function() {
                    selectSeat(this, i);
                };
            }
            container.appendChild(seatDiv);
        }
    }

    function selectSeat(element, seatNum) {
        document.querySelectorAll('.seat.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        
        document.getElementById('form-seat').value = seatNum;
        document.getElementById('seat-status').innerText = "Selected Seat: No " + seatNum;
        document.getElementById('seat-status').style.color = "var(--primary)";
        document.getElementById('seat-status').style.fontWeight = "bold";
    }

    function validateForm() {
        const seat = document.getElementById('form-seat').value;
        if (!seat) {
            alert("Please select a seat first!");
            return false;
        }
        return true;
    }
</script>
</body>
</html>