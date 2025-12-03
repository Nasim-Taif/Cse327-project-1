
<?php
<<<<<<< HEAD

/**
 * Admin Dashboard Page
 *
 * Provides admin overview for turfs, bookings, payments, reviews, and users.
 * Requires an admin login session.
 *
 * @package TurfMate
 */


=======
/**
 * Admin Dashboard Page (admindashboard.php)
 *
 * Displays:
 * - Admin profile
 * - Recent preview of turfs, slots, users, reviews, bookings & payments
 * - Stats counters for dashboard
 *
 * Handles:
 * - Admin authentication
 * - Fetching admin details
 * - Fetching database summaries
 *
 * PHP version 8+
 *
 * @category AdminDashboard
 * @package  TurfMate
 */

>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
session_start();
include "db.php"; // must set $conn = new mysqli(..., "turfmate");

/**
 * Redirect user to login page if not logged in.
 */
if (!isset($_SESSION['admin_id'])) {
  header("Location: adminlogin.php");
  exit();
}
/**
 * Safely escape output for HTML.
 *
 * @param string $s Input text
 * @return string Escaped text safe for HTML display
 */
function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

<<<<<<< HEAD
=======
/**
 * Safely escape string for HTML output
 *
 * @param string|null $s Raw value
 * @return string Escaped HTML-safe value
 */
function e($s)
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d

/**
 * Fetch logged-in admin info
 *
 * @var int $admin_id
 * @var array $admin
 */
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

/**
 * Fetch latest 3 turfs
 *
 * @var mysqli_result $turfs
 */
$turfs = $conn->query("SELECT hall_id, game_name, price, description FROM game_halls ORDER BY hall_id DESC LIMIT 3");

/**
 * Fetch latest 3 slot bookings
 *
 * @var mysqli_result $slots
 */
$slots = $conn->query("SELECT booking_id, hall_id, slot_time, status, booking_date FROM bookings ORDER BY booking_id DESC LIMIT 3");

/**
 * Fetch latest 3 registered users
 *
 * @var mysqli_result $users
 */
$users = $conn->query("SELECT customer_id, full_name, email, contact FROM customers ORDER BY customer_id DESC LIMIT 3");

/**
 * Fetch latest 3 feedback entries with customer info
 *
 * @var mysqli_result $reviews
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
 * Fetch latest 3 bookings (joined data)
 *
 * @var mysqli_result $bookings
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
 * Fetch latest 3 payments
 *
 * @var mysqli_result $payments
 */
$payments = $conn->query("SELECT payment_id, booking_id, amount, payment_date, status FROM payments ORDER BY payment_id DESC LIMIT 3");

/**
 * Dashboard statistics (counts)
 *
 * @var int $total_users
 * @var int $total_turfs
 * @var int $total_bookings
 * @var int $total_feedback
 * @var int $total_payments
 */
$total_users = $conn->query("SELECT COUNT(*) AS c FROM customers")->fetch_assoc()['c'];
$total_turfs = $conn->query("SELECT COUNT(*) AS c FROM game_halls")->fetch_assoc()['c'];
$total_bookings = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'];
$total_feedback = $conn->query("SELECT COUNT(*) AS c FROM feedback")->fetch_assoc()['c'];
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
<<<<<<< HEAD

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
=======

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

    .profile .actions a:hover {
      opacity: .95
    }

    /* grid for first row (3 cards) */
    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
      margin-top: 22px
    }

    @media (max-width:980px) {
      .grid-3 {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media (max-width:640px) {
      .grid-3 {
        grid-template-columns: 1fr
      }
    }

    /* grid for second row (4 cards) */
    .grid-4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin-top: 18px
    }

    @media (max-width:1100px) {
      .grid-4 {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media (max-width:640px) {
      .grid-4 {
        grid-template-columns: 1fr
      }
    }

    /* card base */
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

    .card .title {
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

    .small-item .muted {
      color: var(--muted);
      font-size: 13px;
      margin-top: 6px
    }

    /* colors for each card */
    .card.turfs {
      background: linear-gradient(180deg, #d9e8ff, #ffffff);
      border: 1px solid rgba(26, 115, 232, 0.25);
    }

    .card.slots {
      background: linear-gradient(180deg, #d9ffe3, #ffffff);
      border: 1px solid rgba(34, 197, 94, 0.25);
    }

    .card.manual {
      background: linear-gradient(180deg, #ffe5c2, #ffffff);
      border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .card.users {
      background: linear-gradient(180deg, #dce6ff, #ffffff);
      border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .card.reviews {
      background: linear-gradient(180deg, #d8ffd8, #ffffff);
      border: 1px solid rgba(34, 197, 94, 0.25);
    }

    .card.bookings {
      background: linear-gradient(180deg, #ffe8cc, #ffffff);
      border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .card.payments {
      background: linear-gradient(180deg, #f0dbff, #ffffff);
      border: 1px solid rgba(168, 85, 247, 0.25);
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
    }

    .grid-4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin-top: 18px
    }

<<<<<<< HEAD
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

=======
    /* bottom note inside card */
    .card .note {
      position: absolute;
      left: 16px;
      right: 16px;
      bottom: 12px;
      text-align: center;
      color: var(--muted);
      font-size: 13px
    }

    /* stat badge */
    .badge {
      display: inline-block;
      background: rgba(0, 0, 0, 0.06);
      padding: 6px 10px;
      border-radius: 20px;
      font-weight: 600;
      margin-top: 8px
    }

    /* table like layout in manage pages link */
    .manage-link {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background: #111827;
      color: #fff;
      border-radius: 10px;
      text-decoration: none
    }

    .manage-link:hover {
      opacity: .95
    }

    /* responsive small scroll area */
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
    .scroll-area {
      max-height: 190px;
      overflow: auto;
      padding-right: 6px
    }

<<<<<<< HEAD
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

    .card.turfs {
      background: linear-gradient(180deg, #d9e8ff, #ffffff);
      border: 1px solid rgba(26, 115, 232, 0.25);
    }

    .card.slots {
      background: linear-gradient(180deg, #d9ffe3, #ffffff);
      border: 1px solid rgba(34, 197, 94, 0.25);
    }

    .card.manual {
      background: linear-gradient(180deg, #ffe5c2, #ffffff);
      border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .card.users {
      background: linear-gradient(180deg, #dce6ff, #ffffff);
      border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .card.reviews {
      background: linear-gradient(180deg, #d8ffd8, #ffffff);
      border: 1px solid rgba(34, 197, 94, 0.25);
    }

    .card.bookings {
      background: linear-gradient(180deg, #ffe8cc, #ffffff);
      border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .card.payments {
      background: linear-gradient(180deg, #f0dbff, #ffffff);
      border: 1px solid rgba(168, 85, 247, 0.25);
    }

    .note {
      position: absolute;
      left: 16px;
      right: 16px;
      bottom: 12px;
      text-align: center;
      color: var(--muted);
      font-size: 13px
    }

=======
    /* footer small */
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
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

<<<<<<< HEAD
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
=======
    <!-- FIRST ROW: TURFS | SLOTS | MANUAL BOOKING -->
    <section class="grid-3" aria-label="quick actions">
      <!-- TURF LIST -->
      <a class="card turfs" href="manage_turfs.php" title="Manage Turfs">
        <div class="title">Turf List <span class="badge"><?= e($total_turfs) ?></span></div>
        <div class="scroll-area">
          <?php while ($t = $turfs->fetch_assoc()): ?>
            <div class="small-item">
              <div style="font-weight:700"><?= e($t['game_name']) ?> <span
                  style="float:right;font-weight:600;color:var(--muted)">ID: <?= e($t['hall_id']) ?></span></div>
              <div class="muted">Price: <?= e($t['price']) ?> Tk</div>
              <?php if (!empty($t['description'])): ?>
                <div class="muted">
                  <?= e(substr($t['description'], 0, 120)) ?>    <?= (strlen($t['description']) > 120 ? '...' : '') ?></div>
              <?php endif; ?>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
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
<<<<<<< HEAD
      <a class="card manual" href="select_game.php">
=======
      <a class="card manual" href="select_game.php" title="Create Manual Booking">
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
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
<<<<<<< HEAD
              <div style="font-weight:700"><?= e($u['full_name']) ?>
                <span style="float:right;color:var(--muted)">ID: <?= e($u['customer_id']) ?></span>
              </div>
=======
              <div style="font-weight:700"><?= e($u['full_name']) ?> <span style="float:right;color:var(--muted)">ID:
                  <?= e($u['customer_id']) ?></span></div>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
              <div class="muted"><?= e($u['email']) ?> • <?= e($u['contact']) ?></div>
            </div>
          <?php endwhile; ?>
        </div>
        <div class="note">Click to manage users (edit/delete)</div>
      </a>

      <!-- REVIEWS -->
      <a class="card reviews" href="manage_reviews.php">
        <div class="title">Reviews <span class="badge"><?= e($total_feedback) ?></span></div>
        <div class="scroll-area">
          <?php while ($r = $reviews->fetch_assoc()): ?>
            <div class="small-item">
              <div style="font-weight:700"><?= e($r['full_name']) ?></div>
              <div class="muted">Rating: <?= e($r['rating']) ?>/5 • <?= e($r['created_at']) ?></div>
<<<<<<< HEAD

              <?php if (!empty($r['comment'])): ?>
                <div class="muted">
                  <?= e(substr($r['comment'], 0, 100)) ?><?= (strlen($r['comment']) > 100 ? '...' : '') ?>
                </div>
              <?php endif; ?>

=======
              <?php if (!empty($r['comment'])): ?>
                <div class="muted"><?= e(substr($r['comment'], 0, 100)) ?><?= (strlen($r['comment']) > 100 ? '...' : '') ?>
                </div><?php endif; ?>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
            </div>
          <?php endwhile; ?>
        </div>
        <div class="note">Click to view & delete inappropriate reviews</div>
      </a>

<<<<<<< HEAD
     <!-- BOOKINGS (VIEW ONLY) -->
<a class="card bookings" href="view_bookings.php">
    <div class="title">
        Recent Bookings <span class="badge"><?= e($total_bookings) ?></span>
    </div>
    <div class="scroll-area">
        <?php while ($b = $bookings->fetch_assoc()): ?>
=======
      <!-- BOOKINGS -->
      <a class="card bookings" href="view_bookings.php" title="Manage Bookings">
        <div class="title">Bookings <span class="badge"><?= e($total_bookings) ?></span></div>
        <div class="scroll-area">
          <?php while ($b = $bookings->fetch_assoc()): ?>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
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
<<<<<<< HEAD
      
<a class="card payments" href="view_payments.php">
    <div class="title">
        Recent Payments <span class="badge"><?= e($total_payments) ?></span>
    </div>
    <div class="scroll-area">
        <?php while ($p = $payments->fetch_assoc()): ?>
=======
      <a class="card payments" href="view_payments.php" title="Manage Payments">
        <div class="title">Payments <span class="badge"><?= e($total_payments) ?></span></div>
        <div class="scroll-area">
          <?php while ($p = $payments->fetch_assoc()): ?>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d
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

<<<<<<< HEAD
    <div class="footer-note">
      Dashboard • Quick preview shows recent 3 items per section • Click any card to open full management page
    </div>
=======
    <div class="footer-note">Dashboard • Quick preview shows recent 3 items per section • Click any card to open full
      management page</div>
>>>>>>> ac9561b7fc04309ba4baf9c76361373ac515854d

  </main>

</body>

</html>
