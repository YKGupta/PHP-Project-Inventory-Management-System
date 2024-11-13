<?php 
    if(!isset($_COOKIE["ravi_traders_username"]) || $_COOKIE["ravi_traders_username"] === "")
    {
        header("Location: login.php");
        die();
    }
?>
<?php require('../backend/db.php') ?>
<?php
    // Fetch the data
    $query = 'SELECT * FROM items';
    $response = mysqli_query($connection, $query);
    $ar = [];
    $n = mysqli_num_rows($response);
    $response = mysqli_fetch_all($response);
    for($i = 0; $i < $n; $i++)
    {
        $ar[$i] = [
            "id" => $response[$i][0],
            "name" => $response[$i][1],
            "qty" => $response[$i][2],
            "price" => $response[$i][3],
            "unit" => $response[$i][4],
            "category" => $response[$i][5],
            "imageURL" => $response[$i][6]
        ];
    }
    $data = [];
    $k = 0;
    if(isset($_POST["_method"]))
    {
        if($_POST["_method"] === "CART")
        {
            $temp = $_POST["selectedItems"];
            $selectedItems = [];
            $x = count($temp);
            $total = 0;
            for($i = 0; $i < $x; $i++)
            {
                for($j = 0; $j < $n; $j++)
                {
                    if($temp[$i]["id"] === $ar[$j]["id"])
                    {
                        $total += $temp[$i]["qty"] * $ar[$j]["price"];
                        $data[$k] = $ar[$j];
                        $data[$k++]["qty"] = $temp[$i]["qty"];
                        break;
                    }
                }
            }
            $n = count($data);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ravi Traders</title>
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="cart.module.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <script src="https://kit.fontawesome.com/0a72b2ed27.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav>
            <section class="left">
                <div class="logo">
                    रवि ट्रेडर्स
                </div>
                <a href="home.php" class="underline">Home</a>
                <a href="orders.php">Orders</a>
            </section>
            <section class="right">
                <p>Hello, <strong><?php echo $_COOKIE["ravi_traders_username"] ?></strong>!</p>
                <button onclick="logout()">Logout</button>
            </section>
        </nav>
        <section class="cart">
            <section class="cart-left">
                <section class="cart-left-top">
                    <h1>Shopping Cart</h1>
                    <p><?php echo $n ?> item<?php if($n > 1) echo "s" ?></p>
                </section>
                <section class="cart-items">
                    <?php 
                        for($i = 0; $i < $n; $i++)
                        {
                            echo '<section class="cart-item">
                                    <img src="' . $data[$i]["imageURL"] . '" alt="img">
                                    <div class="item-name">
                                        <p>' . $data[$i]["category"] . '</p>
                                        <p>' . $data[$i]["name"] . '</p>
                                    </div>
                                    <p class="item-qty">✕&nbsp; ' . $data[$i]["qty"] . ' ' . $data[$i]["unit"] . '</p>
                                    <p class="item-price">₹' . $data[$i]["price"] . '/' . $data[$i]["unit"] . '</p>
                                </section>';
                            if($i < $n - 1)
                                echo '<div class="line2"></div>';
                        }
                    ?>
                </section>
                <button><a href="home.php">&larr; Back to Shop</a></button>
            </section>
            <section class="cart-right">
                <h2>Summary</h2>
                <div class="line"></div>
                <div class="cart-info">
                    <p>ITEMS <?php echo $n ?></p>
                    <p>₹<?php echo $total ?></p>
                </div>
                <button>Place Your Order &rarr;</button>
            </section>
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