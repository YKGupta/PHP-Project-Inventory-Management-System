<?php 
    if(!isset($_COOKIE["ravi_traders_username"]) || $_COOKIE["ravi_traders_username"] === "")
    {
        header("Location: login.php");
        die();
    }
?>
<?php require('../backend/db.php') ?>

<?php
    if(isset($_POST["_method"]))
    {
        $query = "INSERT INTO orders(userId, items, totalPrice) VALUES('" . $_COOKIE["ravi_traders_email"] . "', '" . $_POST["orderData"] . "', " . $_POST["orderTotal"] . ")";
        mysqli_query($connection, $query);
    }
?>

<?php
    // Fetch the data
    $query = 'SELECT * FROM orders';
    if($_COOKIE["ravi_traders_admin"] === "0")
        $query .= " WHERE userId='" . $_COOKIE["ravi_traders_email"] . "'";
    $response = mysqli_query($connection, $query);
    $data = [];
    $n = mysqli_num_rows($response);
    $response = mysqli_fetch_all($response);
    for($i = 0; $i < $n; $i++)
    {
        $data[$i] = [
            "id" => $response[$i][0],
            "userId" => $response[$i][1],
            "items" => json_decode($response[$i][2], true),
            "totalPrice" => $response[$i][3],
            "status" => $response[$i][4]
        ];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ravi Traders</title>
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="orders.module.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <nav>
            <section class="left">
                <div class="logo">
                    रवि ट्रेडर्स
                </div>
                <a href="home.php">Home</a>
                <a href="orders.php" class="underline">Orders</a>
            </section>
            <section class="right">
                <p>Hello, <strong><?php echo $_COOKIE["ravi_traders_username"] ?></strong>!</p>
                <button onclick="logout()">Logout</button>
            </section>
        </nav>
        <section class="orders-container">
            <section class="title">
                <h1>Your Orders</h1>
                <p><?php echo $n ?></p>
            </section>
            <section class="order-items">
                <?php 
                    for($i = 0; $i < $n; $i++)
                    {
                        echo '
                            <section class="order-item">
                                <section class="info">
                                    <p>Order Id: ' . $data[$i]["id"] . '</p>
                                    <p>User Id: ' . $data[$i]["userId"] . '</p>
                                </section>
                                <p class="orderQty">✕&nbsp; ' . count($data[$i]["items"]) . ' Items</p>
                                <p class="orderPrice">₹' . $data[$i]["totalPrice"] . '</p>
                            </section>';
                            if($i < $n - 1)
                                echo '<div class="line2"></div>';
                    }
                ?>
            </section>
            <button><a href="home.php">&larr; Back to Shop</a></button>
        </section>
        <!-- Logout -->
        <a hidden id="logoutLink" href="login.php"></a>
        <script>
            const logout = () => {
                document.cookie='ravi_traders_username=';
                document.cookie='ravi_traders_email=';
                document.cookie='ravi_traders_admin=';
                document.getElementById("logoutLink").click();
            };
        </script>
    </body>
</html>