<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamTour - Travel Agency</title>
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


    <section class="popular-locations">
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
    </section>

    <?php include 'asset/footer.php'; ?>

</body>
</html>