<?php require("../backend/db.php") ?>
<?php 
    $err = "";
    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        // Check whether user's email exists in the database or not
        $query = "SELECT id, name, admin FROM users WHERE email = '" . $_POST['email'] . "' AND password = '" . $_POST["password"] . "'";
        $response = mysqli_query($connection, $query);
        if(mysqli_num_rows($response) === 0)
        {
            // User already exists with this email id
            $err = "Invalid Credentials!";
        }
        else
        {
            // Successfully logged in
            $x = mysqli_fetch_all($response);
            setcookie("ravi_traders_username", $x[0][1]);
            setcookie("ravi_traders_admin", $x[0][2]);
            setcookie("ravi_traders_email", $_POST["email"]);
            header("Location: home.php");
            die();
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
        <link rel="stylesheet" href="login.module.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <nav>
            <section class="left">
                <div class="logo">
                    रवि ट्रेडर्स
                </div>
                <a href="">Home</a>
                <a href="">Orders</a>
            </section>
            <section class="right">
                <button><a href="login.php">Login</a></button>
                <button><a href="signup.php">Sign Up</a></button>
            </section>
        </nav>
        <section class="form-container">
            <form action="" method="POST" class="login-form">
                <h2>Login</h2>
                <div class="inputs">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <p><?php echo $err ?></p>
                </div>
                <div class="submit">
                    <input type="submit" value="Login">
                    <a href="signup.php">I don't have an account. Register?</a>
                </div>
            </form>
        </section>
    </body>
</html>