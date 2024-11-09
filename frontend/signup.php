<?php require("../backend/db.php") ?>
<?php 
    $err = "";
    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        // Check whether user's email exists in the database or not
        $query = "SELECT id FROM users WHERE email = '" . $_POST['email'] . "'";
        $response = mysqli_query($connection, $query);
        if(mysqli_num_rows($response) > 0)
        {
            // User already exists with this email id
            $err = "Users already exists!";
        }
        else
        {
            // Create a new user
            $query = "INSERT INTO users(name, email, password) VALUES('" . $_POST["name"] . "', '" . $_POST["email"] . "', '" . $_POST["password"] . "')";
            $response = mysqli_query($connection, $query);
            if($response)
            {
                setcookie("ravi_traders_username", $_POST["name"]);
                setcookie("ravi_traders_admin", "0");
                setcookie("ravi_traders_email", $_POST["email"]);
                header("Location: home.php");
                die();
            }
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
        <link rel="stylesheet" href="signup.module.css">
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
                <h2>Sign Up</h2>
                <div class="inputs">
                    <input type="text" name="name" id="name" placeholder="Name" required>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <p><?php echo $err ?></p>
                </div>
                <div class="submit">
                    <input type="submit" value="Sign Up">
                    <a href="login.php">Already have an account? Login</a>
                </div>
            </form>
        </section>
    </body>
</html>