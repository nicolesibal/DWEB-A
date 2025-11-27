<?php
// NICOLE MARGARETH SIBAL
// CYB-201
// 27/11/2025

// Orange store data
$orange_types = [
    '🍊 Tangerine' => 25,
    '🟠 Kiat-Kiat' => 18,
    '🍊 Sweet Orange' => 22
];

// Customer input
$chosen_orange = 'Tangerine';  // Changed to a valid orange type
$quantity = 4;  // Kilograms
$budget = 120;  // Customer budget
$stock = 10;  // Stock available for this orange

// Arithmetic Operators: calculate total
$price_per_kilo = isset($orange_types[$chosen_orange]) ? $orange_types[$chosen_orange] : 0;

// Add $2 to the price per kilo
$price_per_kilo += 2;

// Recalculate total price after adding $2
$total_price = $price_per_kilo * $quantity;

// Assignment Operator: apply discount if total > budget
$discount = 0;
if ($total_price > $budget) {
    $discount = $total_price * 0.10;  // 10% discount
    $total_price -= $discount;  // Apply discount
}

// Increment Operator: customer adds 1 more kilo
$quantity++;

// Comparison + Logical Operators: check if purchase is allowed
$can_buy = ($quantity <= $stock) && ($total_price <= $budget) ? 'Yes' : 'No';

// Switch Statement for checking orange type
switch ($chosen_orange) {
    case 'Tangerine':
        $orange_description = "Tangerine - Sweet and juicy!";
        break;
    case 'Kiat-Kiat':
        $orange_description = "Kiat-Kiat - Small and tangy!";
        break;
    case 'Sweet Orange':
        $orange_description = "Sweet Orange - Refreshingly sweet!";
        break;
    default:
        $orange_description = "Unknown orange type!";
        break;
}

// Match Expression for discount eligibility
$discount_status = match (true) {
    $total_price > $budget => "You got a discount!",
    default => "No discount available.",
};

// Additional IF-ELSEIF-ELSE statement
$purchase_message = '';
if ($quantity > $stock) {
    $purchase_message = "Sorry, not enough stock for your order.";
} elseif ($total_price > $budget) {
    $purchase_message = "Your total exceeds your budget.";
} else {
    $purchase_message = "You can proceed with your purchase!";
}

// If statement for stock update
$stock_update_message = '';
if ($quantity <= $stock) {
    $stock -= $quantity;
    $stock_update_message = "Stock updated!";
} else {
    $stock_update_message = "Not enough stock!";
}

// For-Each Loop to display all orange types and their prices
$orange_list = '';
foreach ($orange_types as $type => $price) {
    $orange_list .= "<li>$type - \$$price per kilo</li>";
}

// For Loop to display total price breakdown
$total_price_breakdown = '';
for ($i = 1; $i <= $quantity; $i++) {
    $total_price_breakdown .= "Kilo $i: \$" . $price_per_kilo . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orange Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>🍊 Orange Store</h1>
        <table>
            <tr>
                <th>Orange Type</th>
                <th>Price per Kilo</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Discount</th>
                <th>Can Buy?</th>
            </tr>
            <tr>
                <td><?php echo $chosen_orange; ?></td>
                <td>$<?php echo $price_per_kilo; ?></td>
                <td><?php echo $quantity; ?> Kilos</td>
                <td>$<?php echo $total_price; ?></td>
                <td>$<?php echo $discount; ?></td>
                <td><?php echo $can_buy; ?></td>
            </tr>
        </table>

        <h3>Description: <?php echo $orange_description; ?></h3>
        <p><?php echo $discount_status; ?></p>
        <p><?php echo $stock_update_message; ?></p>
        <p><?php echo $purchase_message; ?></p>

        <h3>Orange Types Available:</h3>
        <ul>
            <?php echo $orange_list; ?>
        </ul>

        <h3>Total Price Breakdown:</h3>
        <p><?php echo $total_price_breakdown; ?></p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
