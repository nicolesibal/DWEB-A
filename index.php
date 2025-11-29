<?php
session_start();

$orange_store = [
    "Tangerine" => ["price" => 25, "stock" => 50],
    "Kiat-Kiat" => ["price" => 18, "stock" => 80],
    "Sweet Orange" => ["price" => 22, "stock" => 40],
    "Dalandan" => ["price" => 15, "stock" => 100],
];

if (!isset($_SESSION['orange_store'])) {
    $_SESSION['orange_store'] = $orange_store;
}

$store = &$_SESSION['orange_store'];

$result = "";
$categoryMsg = "";

// IF STATEMENT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $choice = $_POST['orange_type'];
    $qty = intval($_POST['qty']);

    // IF-ELSE-IF-ELSE STATEMENT
    if (!isset($store[$choice])) {
        $result .= "<p class='error'>❌ Error: Orange not found.</p>";
    } elseif ($qty <= 0) {
        $result .= "<p class='error'>⚠️ Invalid quantity.</p>";
    } elseif ($qty > $store[$choice]['stock']) {
        $result .= "<p class='error'>⚠️ Not enough stock. Only {$store[$choice]['stock']} left.</p>";
    } else {
        // DEDUCT STOCK
        $store[$choice]['stock'] -= $qty;
        $price = $store[$choice]['price'];
        $total = $qty * $price;

        // DISCOUNT
        if ($qty >= 10) {
            $discount = $total * 0.10;
            $total -= $discount;
            $discountMsg = "<p>🎉 You received a 10% discount (-₱" . number_format($discount,2) . ")</p>";
        } else {
            $discountMsg = "<p>No discount applied.</p>";
        }

        // ORDER SUMMARY
        $result .= "
            <h2>🧾 Order Summary</h2>
            <p>You ordered <b>$qty</b> pcs of <b>$choice</b>.</p>
            <p>Price each: ₱$price</p>
            $discountMsg
            <h3>💰 Total: ₱" . number_format($total,2) . "</h3>
        ";
    }

   // SWITCH STATEMENT
    switch ($choice) {
        case "Tangerine":
        case "Kiat-Kiat":
            $categoryMsg = "<p><b>$choice</b> is a 🍊 Small Citrus Fruit.</p>";
            break;
        case "Sweet Orange":
        case "Dalandan":
            $categoryMsg = "<p><b>$choice</b> is a 🍊 Medium Citrus Fruit.</p>";
            break;
        default:
            $categoryMsg = "<p>Unknown fruit category.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>N&N's Orange Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1 class="centered">🍊 N&N's Orange Store</h1>

    <!-- ORDER FORM -->
    <form method="POST">
        <label>Choose Orange:</label>
        <select name="orange_type" required>
            <?php foreach ($store as $type => $details) {
                echo "<option value='$type'>$type</option>";
            } ?>
        </select>

        <label>Quantity:</label>
        <input type="number" name="qty" min="1" required>

        <button type="submit">Buy Now</button>
    </form>

    <hr>

    <!-- DISPLAY RESULT -->
    <?php
    echo $result;
    echo $categoryMsg;

    // UPDATED INVENTORY 
    echo "<hr><h2>📦 Updated Store Inventory</h2>";
    foreach ($store as $type => $details) {
        echo "<p>$type – ₱{$details['price']} | Stock: {$details['stock']}</p>";
    }

    // FOR LOOP STATEMENT
    
    if (isset($qty) && $qty > 0 && isset($choice)) {
        echo "<hr><h2>🔁 Preparing Each Orange from Your Order</h2>";
        for ($i = 1; $i <= $qty; $i++) {
            echo "<p>🍊 Orange $i of <b>$choice</b> is being prepared for the customer.</p>";
        }
    }

   // FOR LOOP STATEMENT
    echo "<hr><h2>🍊 Preparing Oranges...</h2>";
    $choices = ["remain whole 🍊", "turn into juice 🧃", "peel the orange 🍊➡️🍊", "slice the orange 🔪🍊"];
    for ($i = 1; $i <= 5; $i++) {
        $action = $choices[array_rand($choices)];
        echo "<p class='prep animation'>🍊 Orange $i is being prepared... <br>👉 Customer wants to <b>$action</b>.</p>";
    }
    ?>
</div>
<?php include "footer.php"; ?>
</body>
</html>
