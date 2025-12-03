<?php
session_start();
include "db.php"; // must set $conn = new mysqli(..., "turfmate");

/**
 * Redirect to admin login page if the user is not logged in
 */
if (!isset($_SESSION['admin_id'])) {
  header("Location: adminlogin.php");
  exit();
}

/**
 * Safe escape function for output, preventing XSS attacks
 * @param string $s The string to escape
 * @return string The escaped string
 */
function e($s)
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/**
 * Fetch the admin information using the session's admin ID
 */
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

/* --- Short previews (latest 3 each) --- */

/**
 * Fetch the latest 3 turfs from the game_halls table
 */
$turfs = $conn->query("SELECT hall_id, game_name, price, description FROM game_halls ORDER BY hall_id DESC LIMIT 3");

/**
 * Fetch the latest 3 bookings from the bookings table
 */
$slots = $conn->query("SELECT booking_id, hall_id, slot_time, status, booking_date FROM bookings ORDER BY booking_id DESC LIMIT 3");

/**
 * Fetch the latest 3 users from the customers table
 */
$users = $conn->query("SELECT customer_id, full_name, email, contact FROM customers ORDER BY customer_id DESC LIMIT 3");

/**
 * Fetch the latest 3 reviews from the feedback table, including customer details
 */
$reviews = $conn->query("
  SELECT f.feedback_id, f.rating, f.comment, f.created_at, c.full_name
  FROM feedback f
  JOIN bookings b ON f.booking_id = b.booking_id
  JOIN customers c ON b.customer_id = c.customer_id
  ORDER BY f.feedback_id DESC
  LIMIT 3
");

/**
 * Fetch the latest 3 bookings with customer and game information
 */
$bookings = $conn->query("
  SELECT b.booking_id, b.booking_date, c.full_name, gh.game_name, b.slot_time, b.status
  FROM bookings b
  JOIN customers c ON b.customer_id = c.customer_id
  JOIN game_halls gh ON b.hall_id = gh.hall_id
  ORDER BY b.booking_id DESC
  LIMIT 3
");

/**
 * Fetch the latest 3 payments from the payments table
 */
$payments = $conn->query("SELECT payment_id, booking_id, amount, payment_date, status FROM payments ORDER BY payment_id DESC LIMIT 3");

/* --- Small stats (optional) --- */

/**
 * Fetch the total number of users from the customers table
 */
$total_users = $conn->query("SELECT COUNT(*) AS c FROM customers")->fetch_assoc()['c'];

/**
 * Fetch the total number of turfs from the game_halls table
 */
$total_turfs = $conn->query("SELECT COUNT(*) AS c FROM game_halls")->fetch_assoc()['c'];

/**
 * Fetch the total number of bookings from the bookings table
 */
$total_bookings = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'];

/**
 * Fetch the total number of feedback entries from the feedback table
 */
$total_feedback = $conn->query("SELECT COUNT(*) AS c FROM feedback")->fetch_assoc()['c'];

/**
 * Fetch the total number of successful payments from the payments table
 */
$total_payments = $conn->query("SELECT COUNT(*) AS c FROM payments WHERE status='successful'")->fetch_assoc()['c'];
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard - TurfMate</title>

  <style>
    :root {
      --bg: #f4f7fb;
      --card: #ffffff;
      --muted: #6c757d;
      --accent: #1a73e8;
      --radius: 12px;
      --shadow: 0 10px 30px rgba(17, 24, 39, 0.07);
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
      background: var(--bg);
      color: #222
    }

    .nav {
      background: #111827;
      color: #fff;
      padding: 14px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .nav .brand {
      font-weight: 700
    }

    .nav .links a {
      color: #fff;
      text-decoration: none;
      margin-left: 14px;
      font-size: 14px;
      padding: 8px 10px;
      border-radius: 8px
    }

    .nav .links a:hover {
      background: rgba(255, 255, 255, 0.06)
    }

    .wrap {
      max-width: 1200px;
      margin: 24px auto;
      padding: 0 18px
    }

    .profile {
      background: linear-gradient(90deg, #fff9c4, #f1fff7);
      border-radius: var(--radius);
      padding: 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow);
      border: 2px solid #ffe082;
    }

    .profile .info p {
      margin: 6px 0;
      font-size: 15px
    }

    .profile .actions a {
      display: inline-block;
      background: var(--accent);
      color: #fff;
      padding: 8px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px
    }

    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
      margin-top: 22px
    }

    .grid-4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin-top: 18px
    }

    .card {
      background: var(--card);
      border-radius: var(--radius);
      padding: 16px;
      height: 280px;
      position: relative;
      box-shadow: var(--shadow);
      cursor: pointer;
      overflow: auto;
      transition: transform .16s ease, box-shadow .16s ease
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 22px 50px rgba(17, 24, 39, 0.12)
    }

    .scroll-area {
      max-height: 190px;
      overflow: auto;
      padding-right: 6px
    }

    .title {
      font-weight: 700;
      text-align: center;
      margin-bottom: 12px
    }

    .small-item {
      background: rgba(255, 255, 255, 0.9);
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 10px;
      border: 1px solid rgba(0, 0, 0, 0.04)
    }

    .muted {
      color: var(--muted);
      font-size: 13px;
      margin-top: 6px
    }

    .badge {
      display: inline-block;
      background: rgba(0, 0, 0, 0.06);
      padding: 6px 10px;
      border-radius: 20px;
      font-weight: 600;
      margin-top: 8px
    }

    .footer-note {
      color: var(--muted);
      font-size: 13px;
      text-align: center;
      margin-top: 18px
    }
  </style>
</head>

<body>

  <header class="nav">
    <div class="brand">TurfMate — Admin Dashboard</div>
    <div class="links">
      <a href="admindashboard.php">Home</a>
      <a href="logout.php">Logout</a>
    </div>
  </header>

  <main class="wrap">

    <!-- PROFILE -->
    <section class="profile">
      <div class="info">
        <h3 style="margin:0 0 6px 0">Admin Profile</h3>
        <p><strong>Name:</strong> <?= e($admin['username'] ?? '') ?></p>
        <p><strong>Email:</strong> <?= e($admin['email'] ?? '') ?></p>
        <p><strong>Admin ID:</strong> <?= e($admin['admin_id'] ?? '') ?></p>
        <p><strong>Joined:</strong> <?= e($admin['created_at'] ?? '') ?></p>
      </div>
      <div class="actions">
        <a href="edit_admin.php?admin_id=<?= e($admin['admin_id']) ?>">Edit Profile</a>
      </div>
    </section>

    <!-- FIRST ROW -->
    <section class="grid-3">

    <!-- TURF LIST -->
<a class="card turfs" href="manage_turfs.php"> <!-- 🔧 UPDATED -->
    <div class="title">Turf List <span class="badge"><?= e($total_turfs) ?></span></div>
    <div class="scroll-area">
      <?php while ($t = $turfs->fetch_assoc()): ?>
        <div class="small-item">
          <div style="font-weight:700"><?= e($t['game_name']) ?>
            <span style="float:right;font-weight:600;color:var(--muted)">ID: <?= e($t['hall_id']) ?></span>
          </div>
          <div class="muted">Price: <?= e($t['price']) ?> Tk</div>

          <?php if (!empty($t['description'])): ?>
            <div class="muted">
              <?= e(substr($t['description'], 0, 120)) ?><?= (strlen($t['description']) > 120 ? '...' : '') ?>
            </div>
          <?php endif; ?>

        </div>
      <?php endwhile; ?>
    </div>
    <div class="note">Click to view, add, edit or delete turfs</div>
</a>

      <!-- SLOTS -->
      <a class="card slots" href="manage_slots.php">
        <div class="title">Manage Slots <span class="badge"><?= e($total_bookings) ?></span></div>
        <div class="scroll-area">
          <?php while ($s = $slots->fetch_assoc()): ?>
            <div class="small-item">
              <div><strong>Booking #<?= e($s['booking_id']) ?></strong></div>
              <div class="muted"><?= e($s['slot_time']) ?> • <?= e($s['booking_date']) ?></div>
              <div class="muted">Status: <?= e($s['status']) ?></div>
            </div>
          <?php endwhile; ?>
        </div>
        <div class="note">Click to see all slots and their booking status</div>
      </a>

      <!-- MANUAL BOOKING -->
      <a class="card manual" href="select_game.php">
        <div class="title">Manual Booking</div>
        <div style="text-align:center;margin-top:24px;color:var(--muted)">
          Create a booking on behalf of a customer
        </div>
        <div class="note">Click to make a manual booking</div>
      </a>

    </section> <!-- ✅ FIXED: closing grid-3 -->

    <!-- SECOND ROW -->
    <section class="grid-4">

      <!-- USERS -->
      <a class="card users" href="manage_users.php">
        <div class="title">Users <span class="badge"><?= e($total_users) ?></span></div>
        <div class="scroll-area">
          <?php while ($u = $users->fetch_assoc()): ?>
            <div class="small-item">
              <div style="font-weight:700"><?= e($u['full_name']) ?>
                <span style="float:right;color:var(--muted)">ID: <?= e($u['customer_id']) ?></span>
              </div>
              <div class="muted"><?= e($u['email']) ?> • <?= e($u['contact']) ?></div>
            </div>
          <?php endwhile; ?>
        </div>
        <div class="note">Click to manage users (edit/delete)</div>
      </a>

    
     <!-- BOOKINGS (VIEW ONLY) -->
<a class="card bookings" href="view_bookings.php">
    <div class="title">
        Recent Bookings <span class="badge"><?= e($total_bookings) ?></span>
    </div>
    <div class="scroll-area">
        <?php while ($b = $bookings->fetch_assoc()): ?>
            <div class="small-item">
                <div style="font-weight:700;">
                    Booking #<?= e($b['booking_id']) ?>
                    <span style="float:right;color:<?= ($b['status']=='confirmed'?'green':'red'); ?>;">
                        <?= e(ucfirst($b['status'])) ?>
                    </span>
                </div>
                <div class="muted">
                    <?= e($b['full_name']) ?> • <?= e($b['game_name']) ?>
                </div>
                <div class="muted">
                    <?= e($b['slot_time']) ?> • <?= e($b['booking_date']) ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="note">Click to view all bookings (read-only)</div>
</a>

      <!-- PAYMENTS -->
      
<a class="card payments" href="view_payments.php">
    <div class="title">
        Recent Payments <span class="badge"><?= e($total_payments) ?></span>
    </div>
    <div class="scroll-area">
        <?php while ($p = $payments->fetch_assoc()): ?>
            <div class="small-item">
                <div style="font-weight:700;">
                    Payment #<?= e($p['payment_id']) ?>
                    <span style="float:right;color:<?= ($p['status']=='successful'?'green':'red'); ?>">
                        <?= e(ucfirst($p['status'])) ?>
                    </span>
                </div>
                <div class="muted">
                    Amount: <?= e($p['amount']) ?> Tk 
                </div>
                <div class="muted">
                    Booking: <?= e($p['booking_id']) ?> • <?= e($p['payment_date']) ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="note">Click to view payment history (read-only)</div>
</a>


    </section>

    <div class="footer-note">
      Dashboard • Quick preview shows recent 3 items per section • Click any card to open full management page
    </div>

  </main>

</body>

</html>
