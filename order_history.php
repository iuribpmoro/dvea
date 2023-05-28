<!DOCTYPE html>
<html>

<head>
    <title>Order History</title>
    <link rel="stylesheet" type="text/css" href="styles/order_history.css">
</head>

<body>
    <h1>Order History</h1>
    <?php
    include 'db.php';
    include 'check_auth.php';

    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $sql = "SELECT * FROM orders WHERE user_id=$user_id ORDER BY date DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<h3>Date: ' . $row['date'] . '</h3>';
                echo '<table border="1">';
                echo '<tr><th>Name</th><th>Price</th><th>Quantity</th></tr>';

                $order_id = $row['id'];
                $sql = "SELECT * FROM order_details WHERE order_id=$order_id";
                $order_details = mysqli_query($conn, $sql);
                $total = 0;

                while ($order_item = mysqli_fetch_assoc($order_details)) {
                    $product_id = $order_item['product_id'];
                    $sql = "SELECT * FROM products WHERE id=$product_id LIMIT 1";
                    $product = mysqli_query($conn, $sql);
                    $product = mysqli_fetch_assoc($product);

                    echo '<tr>';
                    echo '<td>' . $product['name'] . '</td>';
                    echo '<td>$' . $product['price'] . '</td>';
                    echo '<td>' . $order_item['quantity'] . '</td>';
                    echo '</tr>';

                    $total += $product['price'] * $order_item['quantity'];
                }

                echo '</table>';
                echo '<br>Total: $' . $total;
            }
        } else {
            echo "You haven't placed any orders yet.";
        }
    } else {
        echo "You need to be logged in to view your order history.";
    }
    ?>
    <br>
    <a href="index.php">Continue Shopping</a>
</body>

</html>