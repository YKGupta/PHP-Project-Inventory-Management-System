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
            <form action="" class="login-form">
                <h2>Sign Up</h2>
                <div class="inputs">
                    <input type="text" name="name" id="name" placeholder="Name">
                    <input type="email" name="email" id="email" placeholder="Email">
                    <input type="password" name="password" id="password" placeholder="Password">
                </div>
                <div class="submit">
                    <input type="submit" value="Sign Up">
                    <a href="login.php">Already have an account? Login</a>
                </div>
            </form>
        </section>
    </body>
</html>