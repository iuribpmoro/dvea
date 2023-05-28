<!DOCTYPE html>
<html>

<head>
    <title>Footprints</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			// Get credit card information and display it on the page
			$.getJSON('card_api.php', function(data) {
				if (data.credit_card) {
					$('#credit-card').text('Credit card: ' + data.credit_card);
				} else {
					$('#credit-card').text('No credit card on file');
				}
			});
		});
	</script>
</head>

<body>
    <img src="./images/logo.png" />
    <h1>Welcome to Footprints, the shoe store where every step counts!</h1>
    <p>Your default payment method:</p>
    <div id="credit-card"></div>
    
    <form method="GET">
        <label>Search:</label>
        <input type="text" name="search">
        <input type="submit" value="Search">
    </form>
    <br>
        
    <ul>
        <?php
        include 'db.php';
        include 'check_auth.php';

        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM products";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li><a href="product_details.php?id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
            }
        } else {
            echo "No products to display";
        }
        ?>
    </ul>
    <br>
    <br>
    <?php
        echo "<a href='order_history.php?user_id=" . $_SESSION['user_id'] . "'>My Order History</a>";
    ?>

    <br><br>
    <a href="logout.php">Logout</a>
    
</body>

</html>