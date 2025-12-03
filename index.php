<?php
declare(strict_types=1);
session_start(); // start session

// 🍊 Orange Store Inventory
if (!isset($_SESSION['orange_store'])) {
    $_SESSION['orange_store'] = [
        "Tangerine"    => ["price" => 25, "stock" => 50],
        "Kiat-Kiat"    => ["price" => 18, "stock" => 80],
        "Sweet Orange" => ["price" => 22, "stock" => 40],
        "Blood Orange" => ["price" => 28, "stock" => 60],
        "Mandarin"     => ["price" => 20, "stock" => 70],
        "Clementine"   => ["price" => 30, "stock" => 25],
        "Valencia"     => ["price" => 35, "stock" => 15],
        "Seville"      => ["price" => 27, "stock" => 10]
    ];
}

$store = &$_SESSION['orange_store'];
$tax_rate = 12; // 12% global tax rate

$result = "";
$categoryMsg = "";

// -----------------
// ORDER PROCESSING
// -----------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['orange_type'], $_POST['qty'])) {
    $choice = $_POST['orange_type'];
    $qty = intval($_POST['qty']);

    if (!isset($store[$choice])) {
        $result .= "<p class='error'>❌ Error: Orange not found.</p>";
    } elseif ($qty <= 0) {
        $result .= "<p class='error'>⚠️ Invalid quantity.</p>";
    } elseif ($qty > $store[$choice]['stock']) {
        $result .= "<p class='error'>⚠️ Not enough stock. Only {$store[$choice]['stock']} left.</p>";
    } else {
        $store[$choice]['stock'] -= $qty;
        $price = $store[$choice]['price'];
        $total = $qty * $price;

        $discountMsg = ($qty >= 10)
            ? "<p>🎉 You received a 10% discount (-₱" . number_format($total * 0.1, 2) . ")</p>"
            : "<p>No discount applied.</p>";

        if ($qty >= 10) $total *= 0.9;

        $result .= "
            <h2>🧾 Order Summary</h2>
            <p>You ordered <b>$qty</b> pcs of <b>$choice</b>.</p>
            <p>Price each: ₱$price</p>
            $discountMsg
            <h3>💰 Total: ₱" . number_format($total, 2) . "</h3>
        ";
    }

    // Category message using SWITCH
    switch ($choice) {
        case "Tangerine":
        case "Kiat-Kiat":
            $categoryMsg = "<p><b>$choice</b> is a 🍊 Small Citrus Fruit.</p>";
            break;
        default:
            $categoryMsg = "<p><b>$choice</b> is a 🍊 Medium Citrus Fruit.</p>";
    }
}

// -----------------
// FUNCTIONS FOR STOCK MONITORING
// -----------------
function get_reorder_message(int $stock): string {
    return ($stock < 10) ? "Yes" : "No";
}

function get_total_value(float $price, int $quantity): float {
    return $price * $quantity;
}

function get_tax_due(float $price, int $quantity, int $tax_percent = 0): float {
    return get_total_value($price, $quantity) * ($tax_percent / 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🍊 N&N's Orange Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="store-page">

<!-- Navigation -->
<nav>
    <h1>🍊 N&N's Orange Store</h1>
    <a href="home.php">Home</a>
    <a href="index.php">Store</a>
</nav>

<div class="container">
    <h1>🍊 N&N's Orange Store</h1>

    <!-- ORDER FORM -->
    <form method="POST">
        <label>Choose Orange:</label>
        <select name="orange_type" required>
            <?php foreach ($store as $type => $details): ?>
                <option value="<?= $type ?>"><?= $type ?></option>
            <?php endforeach; ?>
        </select>

        <label>Quantity:</label>
        <input type="number" name="qty" min="1" required>

        <button type="submit">Buy Now</button>
    </form>

    <hr>

    <!-- DISPLAY RESULT -->
    <?= $result ?>
    <?= $categoryMsg ?>

    <!-- UPDATED INVENTORY TABLE -->
    <hr>
    <h2>📦 Store Inventory & Stock Monitoring</h2>
    <table class="stock-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price (₱)</th>
            <th>Stock</th>
            <th>Reorder?</th>
            <th>Total Value</th>
            <th>Tax Due</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($store as $product_name => $data): ?>
            <tr>
                <td><?= $product_name ?></td>
                <td>₱<?= number_format($data['price'], 2) ?></td>
                <td><?= $data['stock'] ?></td>
                <td><?= get_reorder_message($data['stock']) ?></td>
                <td>₱<?= number_format(get_total_value($data['price'], $data['stock']), 2) ?></td>
                <td>₱<?= number_format(get_tax_due($data['price'], $data['stock'], $tax_rate), 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <!-- ORDER PREPARATION -->
    <hr>
    <h2>🔁 Preparing Oranges from Your Order</h2>
    <?php
    if (isset($qty) && $qty > 0 && isset($choice)) {
        $actions = ["remain whole 🍊", "turn into juice 🧃", "peel the orange 🍊➡️🍊", "slice the orange 🔪🍊"];
        for ($i = 1; $i <= $qty; $i++) {
            $action = $actions[array_rand($actions)];
            echo "<p class='prep animation'>🍊 Orange $i of <b>$choice</b> is being prepared... <br>👉 Customer wants to <b>$action</b>.</p>";
        }
    }
    ?>
</div>

<!-- FOOTER -->
<footer>
    <p>© 2025 Nicole Margareth Sibal | CYB-201</p>
</footer>

</body>
</html>
