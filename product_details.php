<!DOCTYPE html>
<html>

<head>
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="styles/product_details.css">
</head>

<body>
    <?php
    include 'db.php';
    session_start();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM products WHERE id=$id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo '<div class="product_container"><h1>' . $row['name'] . '</h1>';
        echo '<img src="image_getter.php?filename=' . $row['image'] . '" width="500">';
        echo '<p>' . $row['description'] . '</p>';
        echo '<p>$' . $row['price'] . '</p>';

        echo '<br><br><form method="POST" action="cart.php">';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="number" name="quantity" min="1" max="10" value="1">';
        echo '<input type="submit" name="add_to_cart" value="Add to Cart">';
        echo '</form></div>';
        
        echo '<br><hr><br>';

        // Form to submit a comment (Part 1 of the Persistent XSS)
        echo '<form method="POST" enctype="multipart/form-data" action="product_details.php?id=' . $row['id'] . '">';
        echo '<label>Leave a review:</label><br>';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="text" name="name" placeholder="Name"><br>';
        echo '<textarea name="content" placeholder="Review"></textarea><br>';
        echo '<input type="file" name="image"><br><br><br>';
        echo '<input type="submit" name="submit_review" value="Submit Review">';
        echo '</form>';

        if (isset($_POST['submit_review'])) {
            $product_id = $_POST['product_id'];
            $name = $_POST['name'];
            $content = $_POST['content'];
            
            $product_id = mysqli_real_escape_string($conn, $product_id);
            $name = mysqli_real_escape_string($conn, $name);
            $content = mysqli_real_escape_string($conn, $content);

            // Check if file was uploaded
            if (isset($_FILES['image'])) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_size = $_FILES['image']['size'];
                $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

                // Move the uploaded file to the images folder
                move_uploaded_file($image_tmp, 'uploads/' . $image_name);
                $sql = "INSERT INTO reviews (name, content, image, product_id) VALUES ('$name', '$content', '$image_name', '$product_id')";
                mysqli_query($conn, $sql);
                echo '<br>Review submitted successfully';
            } else {
                $sql = "INSERT INTO reviews (name, content, product_id) VALUES ('$name', '$content', '$product_id')";
                mysqli_query($conn, $sql);
                echo '<br>Review submitted successfully';
            }
        }

        echo '<br><br><h3>Reviews:</h3>';
        $sql = "SELECT * FROM reviews WHERE product_id=$id";
        $result = mysqli_query($conn, $sql);

        // Showing comments on the page (VULNERABLE TO XSS)!!!!!
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<p>Review by ' . $row['name'] . '</p>';
                echo '<p>' . $row['content'] . '</p>';
                if ($row['image']) {
                    echo '<img src="/dvea/uploads/' . $row['image'] . '" width="200">';
                }
                echo '<br><br>';
            }
        } else {
            echo "No reviews to display";
        }

    } else {
        echo "Invalid product ID";
    }
    ?>
    <br>
    <a href="index.php" class="go-back">Go Back</a>
</body>

</html>