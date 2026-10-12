<?php
$host="localhost";  //server name
$user="root";    //اسم المستخدم لقاعدة البيانات
$pass="";  // كلمة المرور غالبا فارغه اكسزامب 
$dbname="cake_store"; //اسم قاعدة البيانات اللي انشئتها 

//ربط مع قاعدة البيانات
$conn= new mysqli($host, $user, $pass, $dbname);
 //تحقق من الإتصال
if($conn ->connect_error){
    die("connection failed".$conn->connect_error);
}

// Search handling
$search = "";
if(isset($_POST['search'])){
    $search = $conn->real_escape_string($_POST['search']);
}

// Fetch products
$sql = "SELECT * FROM product";
if($search != ""){
    $sql .= " WHERE LOWER(name) LIKE LOWER('%$search%')";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cake Store</title>
<style>
/* ====== CSS كامل ومباشر ====== */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Gill Sans', Calibri, sans-serif; background: radial-gradient(circle, rgba(174,213,238,1) 0%, rgba(245,245,245,1) 100%); text-align:center; }
.navbar { display:flex; justify-content:space-between; align-items:center; padding:15px; border-bottom:1px solid white; background-color:#f5f5f5; position:relative; }
.navbar .logo { font-size:24px; font-weight:bold; color: rgb(20,162,227);}
.nav-links a { margin:0 10px; color: rgb(35,196,245); text-decoration:none; }
.login, .Signup { background-color: rgb(20,162,227); border:none; padding:5px 10px; border-radius:7px; color:#e8f0f1; cursor:pointer; }
.search-box { position:absolute; left:50%; transform:translateX(-50%); }
.search-box input { padding:5px; border:1px solid #ccc; border-radius:5px; }
.search-box button { padding:5px 10px; background-color: rgb(20,162,227); color:white; border-radius:5px; border:none; cursor:pointer; }

/* Hero Section */
.Herosection { background-image:url('img/bluecake.jpg'); background-size:cover; background-position:center; height:100vh; display:flex; flex-direction:column; justify-content:center; text-align:center; color:azure; }
.hero-content h1 { font-size:48px; color:#7c0aa5; text-shadow:2px 2px 6px rgba(54,7,225,0.984);}
.hero-description { font-size:18px; color:#ae51ebd5; max-width:600px; margin:0 auto; text-shadow:2px 2px 6px rgba(160,25,154,0.6); }

/* Products */
.container { margin-top:30px; }
.custom-card { border:1px solid azure; border-radius:10px; background-color:#d2e3ed7c; box-shadow:0 8px 24px rgba(146,93,163,0.728); transition:0.3s; padding:10px; }
.custom-card:hover { transform:translateY(-6px); box-shadow:0 14px 36px rgba(8,8,235,0.16);}
.custom-card img { width:100%; height:200px; object-fit:cover; border-radius:6px;}
.info-cake { font-size:18px; color:#0a2a8b; padding:8px 0;}
.cake-desciption { font-size:14px; color: rgb(140,57,234); padding:5px 0;}
.price-container { font-weight:700; color:rgba(236,5,5,0.941);}
.Buy { background-color:#ff4d6d; color:#fff; border:none; padding:7px; border-radius:8px; cursor:pointer; transition:0.3s; }
.Buy:hover { background-color:#160abdb8; color:#e875c0; }

/* Cart Sidebar */
#cart-sidebar { position:fixed; top:0; right:-320px; width:300px; height:100%; background-color:#78c7e9da; padding:20px; transition:right 0.3s; overflow-y:auto; z-index:2000;}
#cart-sidebar.active { right:0; }
#cart-sidebar h2 { display:flex; justify-content:space-between; margin-bottom:22px; font-size:20px; color:white; }
#cart-list { list-style:none; padding:0; color: rgb(148,21,21);}
#cart-list li { display:flex; justify-content:space-between; margin-bottom:10px; background-color: rgba(137,183,233,0.687); padding:5px 10px; border-radius:3px;}
.qty-btn { background-color:#0f7cab; border:none; color:#fff; padding:2px; cursor:pointer; border-radius:3px; }
.cart-header { display:flex; justify-content:space-between; align-items:center; color:white; margin-bottom:15px; }
.close-Btn { background-color:transparent; border:none; color:white; font-size:20px; cursor:pointer; transition:0.3s; }
.close-Btn:hover { color:#ff4d6d; }

/* Footer */
footer { margin-top:30px; padding:10px; background-color: rgb(20,162,227); color:#fff;}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <div class="logo">My Cake Shop</div>
  <div class="search-box">
    <form method="post" action="index.php">
      <input type="text" name="search" placeholder="Search your product..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit">Search</button>
    </form>
  </div>
  <div class="nav-links">
    <a href="#menuecake">Menu Cake</a>
    <a href="#" id="cart-icon">🛒 Cart (<span id="cart-count">0</span>)</a>
    <a href="login.php"><button class="login">Login</button></a>
    <a href="signup.php"><button class="Signup">Sign up</button></a>
  </div>
</nav>

<!-- Hero -->
<div class="Herosection">
  <div class="hero-content"><h1>Cake Store</h1></div>
  <div class="hero-description"><h4>Freshly developed cake store offering the finest desserts.</h4></div>
</div>

<!-- Products -->
<div class="container" id="menuecake">
  <div class="row justify-content-center g-4">
    <?php
    if($result && $result->num_rows>0){
        while($row = $result->fetch_assoc()){
            echo '<div class="col-md-3 col-sm-6">';
            echo '<div class="custom-card">';
            echo "<img src='{$row['image']}' alt='cake'>";
            echo "<div class='info-cake'><h2>{$row['name']}</h2></div>";
            echo "<div class='cake-desciption'><p>{$row['price']} SAR</p></div>";
            echo "<button class='Buy' onclick=\"addProductToCart('{$row['name']}',{$row['price']})\">Add to Cart</button>";
            echo '</div></div>';
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
  </div>
</div>

<!-- Cart Sidebar -->
<div id="cart-sidebar">
  <div class="cart-header">
    <h2>Your Cart 🛒 <span id="cart-count-sidebar">0</span></h2>
    <button id="close-cart" class="close-Btn">❌</button>
  </div>
  <ul id="cart-list"></ul>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 My Cake Store</p>
</footer>

<!-- JS -->
<script>
let cart = [];

function addProductToCart(name, price){
  let existing = cart.find(item => item.name === name);
  if(existing) existing.qty++;
  else cart.push({name, price, qty:1});
  updateCart();
  document.getElementById('cart-sidebar').classList.add('active');
}

function updateCart(){
  let list = document.getElementById('cart-list');
  list.innerHTML='';
  let totalQty = 0;
  cart.forEach(item => {
    totalQty += item.qty;
    let li = document.createElement('li');
    li.innerHTML = `${item.name} - ${item.price} SAR 
      <div>
        <button class="qty-btn" onclick="changeQty('${item.name}',-1)">-</button>
        ${item.qty}
        <button class="qty-btn" onclick="changeQty('${item.name}',1)">+</button>
      </div>`;
    list.appendChild(li);
  });
  document.getElementById('cart-count').textContent = totalQty;
  document.getElementById('cart-count-sidebar').textContent = totalQty;
}

function changeQty(name, delta){
  let item = cart.find(i=>i.name===name);
  if(!item) return;
  item.qty += delta;
  if(item.qty <=0) cart = cart.filter(i=>i.name!==name);
  updateCart();
}

document.getElementById('cart-icon').addEventListener('click', e=>{
  e.preventDefault();
  document.getElementById('cart-sidebar').classList.toggle('active');
});
document.getElementById('close-cart').addEventListener('click', ()=>{
  document.getElementById('cart-sidebar').classList.remove('active');
});
</script>
</body>
</html>