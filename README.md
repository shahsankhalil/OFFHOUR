# OffHour Watches — Plain PHP + MySQL (XAMPP)

This is your "OffHour" frontend converted to a working PHP + MySQL website
(no Laravel/framework — plain PHP, exactly as taught in your Web Development
course with XAMPP).

## Features
- **Home page** — shows all watches from the database, with a collection
  filter (PRX, Gentleman, Seastar, Ballade), using your original design.
- **Add to Cart** — every watch card has a working "ADD TO CART" button
  (uses PHP sessions, no login required).
- **Cart page** — shows all watches in the cart, lets you change quantity,
  remove items, and select multiple watches with checkboxes.
- **Checkout** — clicking "Order Now" on selected items shows an order
  summary, a shipping address form, and payment method (Cash on Delivery).
- **Order placement** — saves the order to the database, removes ordered
  watches from the cart, and shows an order confirmation page.

---

## 1. Folder Structure

```
offhours-php/
├── css/
│   └── csscode.css          <- original styles + new cart/checkout styles
├── js/
│   └── script.js
├── includes/
│   ├── db.php                <- database connection (PDO)
│   ├── functions.php          <- helper functions (formatPrice, cart helpers)
│   ├── header.php             <- shared page header/nav
│   └── footer.php              <- shared page footer
├── index.php                  <- Home page (shows all watches)
├── cart.php                    <- Cart page
├── cart_add.php                 <- Handles "Add to Cart"
├── cart_update.php               <- Handles quantity update
├── cart_remove.php                 <- Handles removing an item
├── checkout.php                     <- Shipping address + payment form
├── place_order.php                   <- Saves the order to DB
├── order_success.php                  <- Order confirmation page
└── database.sql                        <- Run this in phpMyAdmin first
```

---

## 2. Setup Steps (XAMPP)

### Step 1 — Copy the project into htdocs

Copy the entire `offhours-php` folder into your XAMPP `htdocs` directory:

- Windows: `C:\xampp\htdocs\offhours-php`
- Mac: `/Applications/XAMPP/htdocs/offhours-php`
- Linux: `/opt/lampp/htdocs/offhours-php`

### Step 2 — Start Apache & MySQL

Open the XAMPP Control Panel and start **Apache** and **MySQL**.

### Step 3 — Create the database

1. Go to **http://localhost/phpmyadmin**
2. Click the **Import** tab (or open the **SQL** tab on any database).
3. Select the file **`database.sql`** from this project and click **Go**.

This will:
- Create a database called `offhours_watches`
- Create three tables: `watches`, `orders`, `order_items`
- Insert 12 Tissot-themed watches (PRX, Gentleman, Seastar, Ballade)

> If you'd rather do it manually: create a database named `offhours_watches`,
> then open the SQL tab inside it and paste the contents of `database.sql`.

### Step 4 — Check the database connection settings

Open `includes/db.php` and confirm these match your XAMPP setup (defaults
shown below work for almost all XAMPP installs out of the box):

```php
$host = 'localhost';
$db   = 'offhours_watches';
$user = 'root';
$pass = ''; // default XAMPP password is empty
```

### Step 5 — Open the site

Go to: **http://localhost/offhours-php/**

That's it — no `composer install`, no build step. Just plain PHP files
served directly by Apache.

---

## 3. How Each Feature Works

### Home page (`index.php`)
- Connects to the database and runs `SELECT * FROM watches`.
- If `?collection=PRX` (or Gentleman/Seastar/Ballade) is in the URL, it
  filters watches by that collection — used by the nav links and footer.
- Each watch card has a small `<form>` that POSTs the watch's `id` to
  `cart_add.php`.

### Add to Cart (`cart_add.php`)
- Reads `watch_id` from `$_POST`.
- Stores cart contents in `$_SESSION['cart']` as `[watch_id => quantity]`.
- If the watch is already in the cart, increments its quantity.
- Redirects back to the page the user came from.
- The cart icon badge (`#cart-count`) shows `array_sum($_SESSION['cart'])`.

### Cart page (`cart.php`)
- Reads `$_SESSION['cart']`, fetches matching rows from `watches`, and
  displays them in a table with:
  - A checkbox per row (plus a "select all" checkbox)
  - A quantity update form (`cart_update.php`)
  - A remove button (`cart_remove.php`)
  - Cart total
- The **"Order Now"** button submits the checked rows (`selected[]`) to
  `checkout.php`.

### Checkout (`checkout.php`)
- Receives the selected watch IDs, looks them up in the database, and shows
  an order summary plus a form for:
  - Full Name, Phone, Street Address, City
  - Payment Method (Cash on Delivery)
- Saves the selected IDs in `$_SESSION['checkout_selected']` so the
  selection survives if there's a validation error.
- Submits to `place_order.php`.

### Place Order (`place_order.php`)
1. Validates the shipping form (name, phone, address, city required).
2. If validation fails, redirects back to `checkout.php` with error
   messages (stored in `$_SESSION['form_errors']`).
3. On success:
   - Inserts a row into `orders` (shipping info, payment method, total).
   - Inserts a row into `order_items` for each watch (snapshot of
     title/price/quantity).
   - **Removes the ordered watches from `$_SESSION['cart']`.**
   - Redirects to `order_success.php?order_id=...`.

### Order Success (`order_success.php`)
- Fetches the order and its items from the database and displays a
  confirmation with order number, shipping details, payment method, and an
  itemized summary.

---

## 4. Database Tables

**watches**
| Column | Type |
|---|---|
| id | INT, PK, auto-increment |
| brand | VARCHAR |
| title | VARCHAR |
| collection_name | VARCHAR (PRX / Gentleman / Seastar / Ballade) |
| price | DECIMAL |
| image | VARCHAR (image URL) |
| description | TEXT |

**orders**
| Column | Type |
|---|---|
| id | INT, PK, auto-increment |
| full_name | VARCHAR |
| phone | VARCHAR |
| address | TEXT |
| city | VARCHAR |
| payment_method | VARCHAR (default 'cod') |
| total_amount | DECIMAL |
| status | VARCHAR (default 'pending') |

**order_items**
| Column | Type |
|---|---|
| id | INT, PK, auto-increment |
| order_id | FK -> orders |
| watch_id | FK -> watches (nullable) |
| title | VARCHAR (snapshot) |
| price | DECIMAL (snapshot) |
| quantity | INT |

---

## 5. Adding More Watches

Open phpMyAdmin → `offhours_watches` → `watches` table → **Insert**, and
add a new row with `brand`, `title`, `collection_name`, `price`, `image`
(a URL works fine), and `description`.

---

## 6. Notes for your viva / presentation

- Uses **PDO with prepared statements** for all database queries (protects
  against SQL injection — good to mention if asked).
- Cart is **session-based** (`$_SESSION`) — no login required, which keeps
  the project focused on core PHP/MySQL concepts: forms, sessions, CRUD,
  and database relationships (`orders` ↔ `order_items` ↔ `watches`).
- All user input is escaped with `htmlspecialchars()` via the `e()` helper
  function before being output, to prevent XSS.
- The original HTML/CSS design (black/gold Tissot theme) is fully preserved
  — only the structure was converted to PHP includes and connected to MySQL.

Good luck with your final term submission!
