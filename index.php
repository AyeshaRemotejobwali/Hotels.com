<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Hotel Booking</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 30px; }
        h2 { color: #333; }
        form { background: white; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type=text], input[type=date] {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type=submit] {
            background-color: #28a745; color: white; padding: 10px; border: none; border-radius: 4px;
            cursor: pointer; width: 100%;
        }
        input[type=submit]:hover { background-color: #218838; }
    </style>
</head>
<body>

    <h2>Search Hotels</h2>
    <form action="hotels.php" method="GET">
        <label>Location:</label>
        <input type="text" name="location" required>
        <label>Check-in Date:</label>
        <input type="date" name="checkin" required>
        <label>Check-out Date:</label>
        <input type="date" name="checkout" required>
        <input type="submit" value="Search">
    </form>

</body>
</html>
