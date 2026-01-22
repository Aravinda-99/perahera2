<?php
// Database Connection
require 'asset/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];

    // 1. Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "This email is already registered!";
    } else {
        // 2. Hash the Password (Security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insert into Database
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        
        if ($stmt->execute()) {
            $success = "Account created successfully! <a href='login.php'>Login Now</a>";
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    }
    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - DreamTour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Noto Sans Sinhala', sans-serif;
            background-color: #f4f4f4;
            background-image: url('assets/images/perahera2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
            line-height: 1.6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            max-width: 450px;
            margin: 40px auto;
            background: #FFFFFF;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-top: 5px solid #FF9933;
        }
        h2 {
            text-align: center;
            color: #D35400;
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #555;
        }
        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus {
            outline: none;
            border-color: #FF9933;
        }
        button {
            width: 100%;
            background: linear-gradient(45deg, #FF9933, #D35400);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(211, 84, 0, 0.4);
        }
        .error {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #FADBD8;
            color: #C0392B;
            border: 1px solid #C0392B;
        }
        .success {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #D4EFDF;
            color: #196F3D;
            border: 1px solid #196F3D;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
        .signup-link a {
            color: #D35400;
            font-weight: 700;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Mobile Styles */
        @media screen and (max-width: 480px) {
            body { padding: 10px; }
            .container { margin: 20px 10px; padding: 20px; width: 100%; }
            h2 { font-size: 24px; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create New Account</h2>
    
    <?php if(!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if(!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="signup.php" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required placeholder="Enter your full name">
        </div>
        
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="name@example.com">
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" required placeholder="07x xxxxxxx">
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Create a password">
        </div>
        
        <button type="submit">Sign Up</button>
    </form>
    
    <div class="signup-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

</body>
</html>