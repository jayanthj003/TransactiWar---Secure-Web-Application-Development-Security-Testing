<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank Login Form</title>
    <style>
        body {
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
        }

        .outer-box {
            width: 50%;
            /* Increased width */
            height: 520px;
            /* Increased height */
            background-color: #ddd;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .inner-form {
            width: 90%;
            /* Keep inner form same width */
            text-align: center;
            position: relative;
        }

        .top-section {
            position: relative;
            top: -30px;
            /* Keeping the bank name & image shifted upwards */
        }


        .image-placeholder {
            width: 80px;
            height: 80px;
            background-color: red;
            border-radius: 50%;
            margin: 10px auto 20px auto;
            /* Space between image and username field */
        }

        .form-control {
            background-color: #f2f2f2;
            border: none;
            height: 40px;
            border-radius: 5px;
        }

        .form-control::placeholder {
            color: #c0c0c0;
            font-weight: 500;
        }

        .btn-custom {
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            border-radius: 25px;
            padding: 6px 25px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #f0f0f0;
        }

        .register-text {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-text a {
            color: black;
            font-weight: bold;
            text-decoration: none;
        }

        .register-text a:hover {
            text-decoration: underline;
        }
    </style>

    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="outer-box">
            <div class="inner-form">
                <div class="top-section">
                    <h3>Team 6</h3>
                </div>
                <div class="top-section">
                    <h4>Login</h4>
                </div>

                <?php
                if (isset($_SESSION["signup-success"])) {
                    unset($_SESSION["signup-success"]);
                ?>
                    <button class="form-control btn btn-success mt-3 mb-3" disabled="true">
                        <?php echo htmlspecialchars("Signup successful"); ?>
                    </button>
                <?php
                }
                ?>

                <?php
                if (isset($_SESSION["login-error"])) {
                    $error = $_SESSION["login-error"];
                    unset($_SESSION["login-error"]);
                ?>
                    <button class="form-control btn btn-danger mb-3 mt-3" disabled="true">
                        <?php echo $error ?>
                    </button>
                <?php
                }
                ?>

                <form method="post" action="/api/login-handler">
                    <div class="form-group m-2">
                        <input type="text" class="form-control" placeholder="Username" name="username" pattern="^[a-zA-Z0-9_]+" required>
                    </div>
                    <div class="form-group m-2">
                        <input type="password" class="form-control mt-2" placeholder="Password" name="password" required>
                    </div>
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                    <div class="text-center m-2">
                        <button type="submit" class="btn btn-success">LOGIN</button>
                    </div>
                </form>
                <div class="register-text">
                    Don’t have an account? <a href="/signup">Register</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
