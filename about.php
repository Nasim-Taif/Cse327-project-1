<?php
// Slot time generation
$start = 8;    // 8 AM
$end = 22;     // 10 PM
$interval = 2; // 2-hour each slot

$games = [
    "Football"   => 1200,
    "Badminton"  => 500,
    "Cricket"    => 1000,
    "Swimming"   => 800,
    "Volleyball" => 600
];
?>
<!DOCTYPE html>
<html>
<head>
<title>About TurfMate</title>
<style>
    body { font-family: Arial; margin: 0; padding: 0; background: #f4f4f4; }

    .container { width: 95%; margin: 20px auto; }

    h1 { text-align: center; color: #333; }

    p {
        font-size: 17px; 
        background: white; 
        padding: 20px; 
        border-radius: 10px;
    }

    /* EXACT 3 TABLES PER ROW */
    .tables-wrapper {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .sport-table {
        background: white;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 15px;
    }

    th, td {
        padding: 10px; 
        border: 1px solid #444; 
        text-align: center;
    }

    th { background: #222; color: white; }

    footer {
        background: #222;
        color: white;
        text-align: center;
        padding: 15px;
        margin-top: 30px;
    }

    /* Responsive rules */
    @media (max-width: 900px) {
        .tables-wrapper {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .tables-wrapper {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
</head>
<body>

<div class="container">

    <h1>About TurfMate</h1>

    <p>
        <strong>TurfMate</strong> is an online turf booking system designed to make booking
        sports grounds fast and easy. You can book Football, Cricket, Badminton, Swimming,
        and Volleyball slots instantly. Each slot is 2 hours long and priced per sport.
    </p>

    <div class="tables-wrapper">

        <?php foreach ($games as $game => $price): ?>
        
        <div class="sport-table">
            <h2 style="text-align:center; margin:0;"><?= $game ?> (<?= $price ?> Tk)</h2>

            <table>
                <tr>
                    <th>Slot Time</th>
                    <th>Cost (Tk)</th>
                </tr>

                <?php 
                for ($time = $start; $time < $end; $time += $interval) {
                    $slot_time =
                        date("g A", strtotime("$time:00")) . " - " .
                        date("g A", strtotime(($time + $interval) . ":00"));

                    echo "<tr>
                            <td>$slot_time</td>
                            <td>$price</td>
                          </tr>";
                }
                ?>
            </table>
        </div>

        <?php endforeach; ?>

    </div>

</div>

<footer>
    Contact: turfmate@gmail.com | Phone: 0198767282
</footer>

</body>
</html>
