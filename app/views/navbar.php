<?php
include_once __DIR__ . "/../url-definitions.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .navbar-custom {
            background-color: #d1d1d1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 30px;
        }

        .circle {
            border-radius: 50%;
        }

        .logo {
            width: 60px;
            /* Bigger for logo */
            height: 60px;
            background-color: red;
        }

        .profile-image {
            width: 40px;
            /* Smaller for user profile */
            height: 40px;
            background-color: black;
        }

        .nav-center {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        .logout {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }

        .nav-center a,
        .logout {
            text-decoration: none;
            color: black;
            margin: 0 15px;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
            font-family: 'Georgia', serif;
        }

        .nav-center a:hover {
            background-color: rgba(0, 0, 0, 0.1);
            /* Light hover effect */
            border-radius: 5px;
        }

        .nav-active {
            background-color: rgba(0, 0, 0, 0.2);
            /* Light hover effect */
            border-radius: 5px;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .bank-name {
            margin-left: 10px;
            font-family: 'Georgia', serif;
            font-size: 18px;
            font-weight: normal;
            /* Not bold */
        }
    </style>
</head>

<body>

    <nav class="navbar-custom">
        <div class="logo-container">
            <span class="bank-name fs-3">Team 6</span>
        </div>
        <div class="nav-center">
            <a <?php if ($_SERVER['REQUEST_URI'] == HOME) {
                ?>
                class="fw-bolder nav-active"
                <?php
                } ?>
                href="<?= HOME ?>">Home</a>
            <a <?php if ($_SERVER['REQUEST_URI'] == SEARCH_USERS) {
                ?>
                class="fw-bolder nav-active"
                <?php
                } ?>
                href="<?= SEARCH_USERS ?>">Search Users</a>
            <a <?php if ($_SERVER['REQUEST_URI'] == TRANSFER) {
                ?>
                class="fw-bolder nav-active"
                <?php
                } ?>
                href="<?= TRANSFER ?>">Transfer</a>
            <a <?php if ($_SERVER['REQUEST_URI'] == MY_PROFILE) {
                ?>
                class="fw-bolder nav-active"
                <?php
                } ?>
                href="<?= MY_PROFILE ?>">Profile</a>
            <form method="post" action="<?= LOGOUT_HANDLER ?>">
                <input class="logout" type="submit" Value="Logout">
                <input type="hidden" name="csrf-token" value="<?= $_SESSION['csrf-token'] ?>">
            </form>
        </div>
    </nav>

</body>

</html>
