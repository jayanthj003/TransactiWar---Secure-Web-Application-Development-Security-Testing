<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank Signup Form</title>
    <style>
        body {
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
        }

        .outer-box {
            width: 50%;
            /* Increased width */
            height: 600px;
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
            /* Keep inner form exactly the same */
            position: relative;
        }

        .top-section {
            position: relative;
            top: -30px;
            /* Keeps the image & bank name shifted upwards */
        }

        h3 {
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .image-placeholder {
            width: 80px;
            height: 80px;
            background-color: red;
            border-radius: 50%;
            margin: 10px auto 20px auto;
            /* Space between image and fields */
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
                <div class="top-section text-center">
                    <h3>Team 6</h3>
                </div>
                <div class="top-section text-center">
                    <h4>Signup</h4>
                </div>

                <?php
                if (isset($_SESSION["signup-error"])) {
                    $error = $_SESSION["signup-error"];
                    unset($_SESSION["signup-error"]);
                ?>
                    <button class="form-control btn btn-danger mb-3 mt-3 mb-3" disabled="true">
                        <?php echo $error ?>
                    </button>
                <?php
                }
                ?>

                <form method="post" action="/api/signup-handler">
                    <div class="form-group">
                        <input type="text" class="form-control mb-0" placeholder="Username" name="username" maxlength="40" pattern="^[a-zA-Z0-9_]+" required>
                        <div class="form-text fw-thinner m-0">Only character from [a-z, A-Z, 0-9, _] are allowed</div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control mt-4" placeholder="Email" name="email" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control mt-4" placeholder="Password" name="password" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control mt-2" placeholder="Confirm Password" name="confirm-password" minlength="8" required>
                        <div class="form-text fw-thinner m-0">Password should be atleast 8 charachers long</div>
                    </div>
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">SIGNUP</button>
                    </div>
                </form>
                <div class="register-text text-center">
                    Already have an account? <a href="/login">Login</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
