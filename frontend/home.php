<?php require('data.php') ?>
<?php 
    if(!isset($_COOKIE["ravi_traders_username"]))
    {
        header("Location: login.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ravi Traders</title>
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="home.module.css">
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
                <button><a href="login.php">Logout</a></button>
            </section>
        </nav>
        <section class="stats">
            <div class="total-items">
                <h2>Total Items</h2>
                <p><?php echo count($data) ?></p>
            </div>
            <div class="max-qty">
                <h2>Item with Maximum Quantity</h2>
                <p>
                    <?php 
                        $max = 0;
                        $n = count($data);
                        for($i = 1; $i < $n; $i++)
                        {
                            if($data[$i]["qty"] > $data[$max]["qty"])
                                $max = $i;
                        }
                        echo $data[$max]["name"] . " - " . $data[$max]["qty"];
                    ?>
                </p>
            </div>
            <div class="restock-needed">
                <h2>Restock Needed</h2>
                <p>
                    <?php 
                        $min = 0;
                        $n = count($data);
                        for($i = 1; $i < $n; $i++)
                        {
                            if($data[$i]["qty"] < $data[$min]["qty"])
                                $min = $i;
                        }
                        echo $data[$min]["name"] . " - " . $data[$min]["qty"];
                    ?>
                </p>
            </div>
        </section>
        <section class="items">
            <h1>Items</h1>
            <section class="items-container">
                <?php 
                    $n = count($data);
                    for($i = 0; $i < $n; $i++)
                    {
                        echo '
                            <section class="card">
                            <div class="badge">' . $data[$i]["category"] . '</div>
                            <img src="' . $data[$i]["imageURL"] . '" alt="item-image">
                            <section class="details">
                                <h2>' . $data[$i]["name"] . '</h2>
                                <p>Price: ' . $data[$i]["price"] . '/' . $data[$i]["unit"] . '</p>
                                <p>Quantity: ' . $data[$i]["qty"] . '</p>
                                <div class="actions">';
                                if($_COOKIE["ravi_traders_admin"] === "0")
                                {
                                    if($data[$i]["qty"] <= 0)
                                        echo '<span class="not-in-stock">Not in stock</span>';
                                    else
                                        echo ' <span>Add to cart</span>
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>';
                                }
                                else
                                {
                                    echo ' <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                    <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>';
                                }
                                echo '</div>
                            </section>
                        </section>
                        ';
                    }
                ?>
            </section>
        </section>
        <section class="add-item">
            <?php 
                if($_COOKIE["ravi_traders_admin"] === "1")
                    echo '<button>Add Item</button>';
            ?>
        </section>
    </body>
</html>