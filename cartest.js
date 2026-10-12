// تحميل السلة وعرضها
function loadCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // تحديث عداد السلة
    updateCartCount();

    // عرض المنتجات في صفحة order
    let container = document.getElementById("cart-items");
    if (!container) return; // لو الصفحة ما فيها cart-items ما ينكسر الكود
    container.innerHTML = "";

    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.qty;
        container.innerHTML += `
        <div class="custom-card">
            <img src="${item.image}" alt="${item.name}">
            <div class="info-cake">
            <h2>${item.name}</h2>
            </div>

             <div class="cake-desciption">
             <p>${item.description || ""}</p>
             </div>

             <div class="price-container">
            <p>Price: ${item.price} SAR</p>
             </div>
 
            <div class="quantity-box">
            <button class="qty-btn"  onclick="updateQty(${index},${item.qty-1})">-</button>
            <input type="number" min="1" value="${item.qty}" onchange="updateQty(${index}, this.value)">
             <button class="qty-btn"  onclick="updateQty(${index},${item.qty+1})">+</button>
            </div>

           
            <button onclick="removeItem(${index})">Delete</button>
        </div>
        `;
    });

    // تحديث التوتال فقط إذا العنصر موجود
    let totalElement = document.getElementById("total");
    if (totalElement) totalElement.innerText = total;
}

// إضافة منتج للسلة
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({...product, qty:1});
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
}

// تحديث كمية المنتج
function updateQty(index, qty) {
    let cart = JSON.parse(localStorage.getItem("cart"));
    cart[index].qty = Number(qty);
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
}

// حذف عنصر من السلة
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart"));
    cart.splice(index,1);
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
}

// تحديث عداد السلة
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = 0;
    cart.forEach(item => count += item.qty);

    let countElement = document.getElementById("cart-count");
    if(countElement) countElement.innerText = count;
}

// الذهاب لصفحة الدفع / هنا ممكن بعدين نربطه بالباك اند
function checkout() {
    window.location.href = "payment.html";
}

// تشغيل عند تحميل الصفحة فقط بعد التأكد من DOM
document.addEventListener("DOMContentLoaded", function() {
    loadCart();
});



// ====== فلترة الكروت حسب البحث ======
const searchInput = document.getElementById('searchInput');
const cards = document.querySelectorAll('.custom-card');
if(searchInput){
searchInput.addEventListener('input', () => {
  const text = searchInput.value.toLowerCase().trim();

  cards.forEach(card => {
    const title = card.querySelector('h2').textContent.toLowerCase();
    if(title.includes(text)){
      card.parentElement.style.display = "block";
    } else {
      card.parentElement.style.display = "none";
    }
  });
});
}

