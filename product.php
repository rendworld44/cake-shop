<?php
include 'db.php'; // الاتصال بقاعدة البيانات
$search = isset($_GET['search']) ? $_GET['search'] : '';

if($search != ''){
    $stmt = $conn->prepare("SELECT * FROM product WHERE name LIKE ?");
    $like = "%".$search."%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM product");
}

while($row = $result->fetch_assoc()):
?>
    <div class="custom-card">
        <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
        <div class="info-cake">
            <h2><?= $row['name'] ?></h2>
        </div>
        <div class="cake-desciption">
            <p><?= $row['description'] ?></p>
        </div>
        <div class="price-container">
            <span><?= $row['price'] ?> SAR</span>
        </div>
        <button class="Buy btn btn-sm" onclick="addProductToCart('<?= $row['name'] ?>', <?= $row['price'] ?>)">Add to Cart</button>
    </div>
<?php endwhile; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <!-- ربط ملف CSS -->
    <link rel="stylesheet" href="cartest.css">
</head>
<body>

</body>
</html>