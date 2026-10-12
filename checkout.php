<?php
include "db.php";
session_start();

$message = "";

if(isset($_POST['checkout'])){

    // معلومات المستخدم
    $user_id = $_SESSION['user_id'] ?? 0;
    $name    = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone   = $_POST['phone'] ?? '';
    $total   = floatval($_POST['total'] ?? 0);

    // المنتجات من الفورم
    $items   = $_POST['items'] ?? '';

    // نحفظ الطلب مباشرة
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, name, address, phone, items, total)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issssd", $user_id, $name, $address, $phone, $items, $total);

    if($stmt->execute()){
        unset($_SESSION['cart']); // تفريغ السلة
        header("Location: cartest.html");
        exit;
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<link rel="stylesheet" href="cartest.css">
</head>
<body>
<div class="form-container">
<h2>Checkout</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form method="POST">
  <input type="text" name="name" placeholder="Name" required><br>
  <input type="text" name="address" placeholder="Address" required><br>
  <input type="text" name="phone" placeholder="Phone" required><br>
  <input type="hidden" name="total" value="<?php echo $total; ?>">
  <button type="submit" name="checkout">Pay</button>
</form>
</div>
</body>
</html>