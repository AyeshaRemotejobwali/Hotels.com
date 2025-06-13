<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid hotel ID.");
}

$hotel_id = intval($_GET['id']);
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!$name || !$email || !$phone) {
        $error = "Please fill all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, name, email, phone, checkin, checkout) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $hotel_id, $name, $email, $phone, $checkin, $checkout);

        if ($stmt->execute()) {
            $success = "Booking confirmed! Thank you, $name.";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Fetch hotel details
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel = $stmt->get_result()->fetch_assoc();
if (!$hotel) {
    die("Hotel not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book <?= htmlspecialchars($hotel['name']) ?></title>
    <style>
        body { font-family: Arial; padding: 40px; background: #eee; }
        form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px #aaa; }
        input[type=text], input[type=email], input[type=tel] {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type=submit] {
            background: #007bff; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%;
        }
        input[type=submit]:hover { background: #0056b3; }
        .message { max-width: 400px; margin: 15px auto; padding: 10px; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Book Hotel: <?= htmlspecialchars($hotel['name']) ?></h2>

    <?php if (!empty($error)): ?>
        <div class="message error"><?= $error ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="tel" name="phone" required>

        <input type="submit" value="Confirm Booking">
    </form>
    <?php endif; ?>

</body>
</html>
