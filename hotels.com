<?php
include 'db.php';

// Get search parameters
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';

if (!$location || !$checkin || !$checkout) {
    echo "<p>Please enter destination, check-in and check-out dates.</p>";
    exit;
}

// Search query: find hotels where location LIKE %location%
$sql = "SELECT * FROM hotels WHERE LOWER(location) LIKE ?";
$stmt = $conn->prepare($sql);

$likeLocation = '%' . strtolower($location) . '%';
$stmt->bind_param("s", $likeLocation);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotels in <?= htmlspecialchars($location) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .hotel {
            background: white; 
            padding: 15px; 
            margin-bottom: 15px; 
            border-radius: 6px; 
            box-shadow: 0 0 8px #ccc;
        }
        h2 { margin-bottom: 20px; }
        .btn-book {
            background: #007bff; 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-book:hover { background: #0056b3; }
    </style>
</head>
<body>

<h2>Available Hotels in <?= htmlspecialchars($location) ?></h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($hotel = $result->fetch_assoc()): ?>
        <div class="hotel">
            <h3><?= htmlspecialchars($hotel['name']) ?></h3>
            <p><strong>Location:</strong> <?= htmlspecialchars($hotel['location']) ?></p>
            <p><strong>Price per night:</strong> $<?= number_format($hotel['price'], 2) ?></p>
            <p><?= htmlspecialchars($hotel['description']) ?></p>
            <!-- Book Now button with required GET parameters -->
            <a class="btn-book" href="book.php?id=<?= $hotel['id'] ?>&checkin=<?= urlencode($checkin) ?>&checkout=<?= urlencode($checkout) ?>">Book Now</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No hotels found for your search.</p>
<?php endif; ?>

</body>
</html>
