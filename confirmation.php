<?php
include 'db.php';

// Get booking ID from URL
if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    die("Invalid booking ID.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details with hotel info
$stmt = $conn->prepare("
    SELECT b.*, h.name AS hotel_name, h.location 
    FROM bookings b 
    JOIN hotels h ON b.hotel_id = h.id 
    WHERE b.id = ?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 40px; }
        .confirmation { background: white; max-width: 600px; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { color: #28a745; }
        p { font-size: 16px; }
        .details { margin-top: 20px; }
        .details strong { display: inline-block; width: 150px; }
    </style>
</head>
<body>

<div class="confirmation">
    <h2>Booking Confirmed!</h2>
    <p>Thank you, <strong><?= htmlspecialchars($booking['name']) ?></strong>, for booking with us.</p>

    <div class="details">
        <p><strong>Hotel:</strong> <?= htmlspecialchars($booking['hotel_name']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
        <p><strong>Check-in Date:</strong> <?= htmlspecialchars($booking['checkin']) ?></p>
        <p><strong>Check-out Date:</strong> <?= htmlspecialchars($booking['checkout']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
        <p><strong>Booking Date:</strong> <?= htmlspecialchars($booking['booked_at']) ?></p>
    </div>

    <p>We look forward to hosting you!</p>
</div>

</body>
</html>
