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
        if($_POST["_method"] === "POST")
        {
            $query = 'INSERT INTO items(name, qty, price, unit, category, imageURL) VALUES("' . $_POST["name"] . '", ' . $_POST["qty"] . ', ' . $_POST["price"] . ', "' . $_POST["unit"] . '", "' . $_POST["category"] . '", "' . $_POST["imageURL"] . '")';
            $response = mysqli_query($connection, $query);
        }
        else if($_POST["_method"] === "DELETE")
        {
            $query = 'DELETE FROM items WHERE id=' . $_POST["id"];
            $response = mysqli_query($connection, $query);
        }
        else if($_POST["_method"] === "PUT")
        {
            $query = "UPDATE items SET name='" . $_POST["name"] . "', qty='" . $_POST["qty"] . "', price='" . $_POST["price"] . "', unit='" . $_POST["unit"] . "', category='" . $_POST["category"] . "', imageURL='" . $_POST["imageURL"] . "' WHERE id=" . $_POST["id"];
            $response = mysqli_query($connection, $query);
        }
    }
?>
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
    $data = $ar;
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
                <i class="fa-solid fa-cart-shopping hide" onclick="goToCart()">
                    <div class="circle" id="cart-items">1</div>
                </i>
                <button onclick="logout()">Logout</button>
            </section>
        </nav>
        <section class="modal hide" id="addModal">
            <i class="fa-solid fa-xmark close-btn" aria-hidden="true" onclick="func('addModal')"></i>
            <h2>Add Item</h2>
            <form action="" method="post">
                <input type="text" name="name" id="name" placeholder="Name" required>
                <input type="number" name="qty" id="qty" placeholder="Quantity" required>
                <input type="number" name="price" id="price" placeholder="Price per unit" required>
                <input type="text" name="unit" id="unit" placeholder="Unit" required>
                <input type="text" name="category" id="category" placeholder="Category" required>
                <input type="text" name="imageURL" id="imageURL" placeholder="Image URL" required>
                <input type="text" name="_method" hidden value="POST">
                <input type="submit" value="Add Item">
            </form>
        </section>
        <section class="modal hide" id="editModal">
            <i class="fa-solid fa-xmark close-btn" aria-hidden="true" onclick="func('editModal')"></i>
            <h2>Add Item</h2>
            <form action="" method="post" id="editForm">
                <input type="number" name="id" id="id" hidden>
                <input type="text" name="name" id="name" placeholder="Name" required>
              <input type="text" name="unit" id="unit" placeholder="Unit" required>
              <input type="number" name="qty" id="qty" placeholder="Quantity" required>
                <input type="number" name="price" id="price" placeholder="Price per unit" required>
                    <input type="text" name="category" id="category" placeholder="Category" required>
                <input type="text" name="imageURL" id="imageURL" placeholder="Image URL" required>
                <input type="text" name="_method" hidden value="PUT">
                <input type="submit" value="Update Item">
            </form>
        </section>
        <?php 
            if($_COOKIE["ravi_traders_admin"] === "1")
            {
                $max = 0;
                $min = 0;
                $n = count($data);
                for($i = 1; $i < $n; $i++)
                {
                    if($data[$i]["qty"] > $data[$max]["qty"])
                        $max = $i;
                    if($data[$i]["qty"] < $data[$min]["qty"])
                        $min = $i;
                }
                echo '
                    <section class="stats">
                        <div class="total-items">
                            <h2>Total Items</h2>
                            <p>' . count($data) . '</p>
                        </div>
                        <div class="max-qty">
                            <h2>Item with Maximum Quantity</h2>
                            <p>' . 
                                    $data[$max]["name"] . " - " . $data[$max]["qty"]
                                . '
                            </p>
                        </div>
                        <div class="restock-needed">
                            <h2>Restock Needed</h2>
                            <p>' .
                                
                                    $data[$min]["name"] . " - " . $data[$min]["qty"]
                                . '
                            </p>
                        </div>
                    </section>
                ';
            }
            else
            {
                echo '<section class="stats"></section>';
            }
        ?>
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
                                <p>Quantity: ' . $data[$i]["qty"] . ' ' . $data[$i]["unit"] . '</p>
                                <div class="actions">';
                                if($_COOKIE["ravi_traders_admin"] === "0")
                                {
                                    if($data[$i]["qty"] <= 0)
                                        echo '<span class="not-in-stock">Not in stock</span>';
                                    else
                                        echo '<i class="fa-solid fa-minus hide" aria-hidden="true" data-id="' . $data[$i]["id"] . '" data-qty="' . $data[$i]["qty"] . '" onclick="removeFromCart(event)"></i>
                                    <span>Add to cart</span>
                                    <i class="fa-solid fa-plus" aria-hidden="true" data-id="' . $data[$i]["id"] . '" data-qty="' . $data[$i]["qty"] . '" onclick="addToCart(event)"></i>';
                                }
                                else
                                {
                                    echo ' <i class="fa-solid fa-trash" aria-hidden="true" onclick="deleteItem(' . $data[$i]["id"] . ')"></i>
                                    <i class="fa-solid fa-pen-to-square" aria-hidden="true" onclick="editItem(' . $data[$i]["id"] . ', ' . "'" . $data[$i]["name"] . "'" . ', ' . $data[$i]["qty"] . ', ' . $data[$i]["price"] . ', ' . "'" . $data[$i]["unit"] . "'" . ', ' . "'" . $data[$i]["category"] . "'" . ', ' . "'" . $data[$i]["imageURL"] . "'" . ')"></i>';
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
                    echo '<button onclick="func('. "'addModal'" . ')">Add Item</button>';
            ?>
        </section>
        <form class="hide" method="POST" id="deleteForm">
            <!-- Used to trigger deletion of an item -->
             <input type="number" name="id" id="deleteId">
             <input type="text" name="_method" hidden value="DELETE">
        </form>
        <form class="hide" action="cart.php" method="POST" id="cartForm">
            <!-- Used to trigger going to cart page -->
        </form>
        <!-- Logout -->
        <a hidden id="logoutLink" href="login.php"></a>
        <script>
            let selectedItems = [];

            const func = (id) => {
                const x = document.getElementById(id);
                x.classList.toggle("hide"); 
            };

            const deleteItem = (id) => {
                const element = document.getElementById("deleteId");
                element.value = id;
                document.getElementById("deleteForm").submit();
            };

            const editItem = (id, name, qty, price, unit, category, imageURL) => {
                const form = document.getElementById("editForm");
                form.querySelector("#id").value = id;
                form.querySelector("#name").value = name;
                form.querySelector("#qty").value = qty;
                form.querySelector("#price").value = price;
                form.querySelector("#unit").value = unit;
                form.querySelector("#category").value = category;
                form.querySelector("#imageURL").value = imageURL;
                func("editModal");
            };

            const addToCart = (ev) => {
                let f = -1;
                const id = Number.parseInt(ev.target.getAttribute("data-id"));
                const maxQty = Number.parseInt(ev.target.getAttribute("data-qty"));
                for(let i = 0; i < selectedItems.length; i++)
                {
                    if(selectedItems[i][0] === id)
                    {
                        selectedItems[i][1]++;
                        f = i;
                        break;
                    }
                }
                if(f == -1)
                {
                    selectedItems.push([ id, 1 ]);
                    f = selectedItems.length - 1;
                }
                ev.target.previousElementSibling.innerHTML = selectedItems[f][1];
                document.getElementById("cart-items").innerHTML = selectedItems.length;
                document.getElementById("cart-items").parentElement.classList.remove('hide');
                ev.target.previousElementSibling.previousElementSibling.classList.remove('hide');
                if(selectedItems[f][1] === maxQty)
                    ev.target.style.display = "none";
            };

            const removeFromCart = (ev) => {
                let f = -1;
                const id = Number.parseInt(ev.target.getAttribute("data-id"));
                const maxQty = Number.parseInt(ev.target.getAttribute("data-qty"));
                for(let i = 0; i < selectedItems.length; i++)
                {
                    if(selectedItems[i][0] === id)
                    {
                        selectedItems[i][1]--;
                        f = i;
                        break;
                    }
                }
                ev.target.nextElementSibling.innerHTML = selectedItems[f][1];
                ev.target.nextElementSibling.nextElementSibling.style.display = "inline-block";
                
                if(selectedItems[f][1] === 0)
                {
                    ev.target.nextElementSibling.innerHTML = "Add to cart";
                    ev.target.classList.add('hide');
                    selectedItems.splice(f, 1);
                }

                if(selectedItems.length === 0)
                    document.getElementById("cart-items").parentElement.classList.add('hide');
                document.getElementById("cart-items").innerHTML = selectedItems.length;
            };

            const goToCart = () => {
                const n = selectedItems.length;
                if(n === 0)
                    return;
                const form = document.getElementById("cartForm");
                form.innerHTML = '<input type="text" name="_method" hidden value="CART">';
                for(let i = 0; i < n; i++)
                {
                    form.innerHTML += `\n<input type="number" name="selectedItems[${i}][id]" value=${selectedItems[i][0]}>`;
                    form.innerHTML += `\n<input type="number" name="selectedItems[${i}][qty]" value=${selectedItems[i][1]}>`;
                }
                form.submit();
            };

            const logout = () => {
                document.cookie='ravi_traders_username=';
                document.cookie='ravi_traders_email=';
                document.cookie='ravi_traders_admin=';
                document.getElementById("logoutLink").click();
            };
        </script>
    </body>
</html>