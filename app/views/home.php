<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            background-color: #d1d1d1;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .profile-card .profile-pic {
            width: 80px;
            height: 80px;
            background-color: black;
            border-radius: 50%;
        }

        .profile-details {
            font-family: 'Georgia', serif;
        }

        .transactions-card {
            background-color: #d1d1d1;
            border-radius: 15px;
            margin-top: 20px;
            padding: 15px;
        }

        .transactions-card h5 {
            text-align: center;
            font-family: 'Georgia', serif;
        }

        .transaction-item {
            background-color: white;
            border-radius: 10px;
        }
    </style>

    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="navbar-container">
        <?php require __DIR__ . '/navbar.php'; ?>
    </div>

    <!-- Profile Card -->
    <div class="profile-card container mt-3">
        <div class="profile-details container">
            <div class="row">
                <div class="col-md-auto align-self-center">
                    <img class="profile-pic" src=<?php echo $data["profile_picture_path"] ?>>
                </div>
                <div class="col fs-4 fw-bold align-self-center">Username: <?php echo $data["username"] ?></div>
                <div class="col fs-4 fw-bold align-self-center">Balance: <?php echo $data["balance"] ?></div>
            </div>
        </div>
    </div>

    <!-- Transactions History -->
    <div class="transactions-card container mt-3">
        <h5 class="m-3 fw-bold">TRANSACTIONS HISTORY</h5>
        <div class="row transaction-item p-2 m-2 text-center">
            <div class="col fs-5 fw-bold">Date</div>
            <div class="col fs-5 fw-bold">Username</div>
            <div class="col fs-5 fw-bold">Remark</div>
            <div class="col fs-5 fw-bold">Amount</div>
        </div>
        <?php foreach ($data["transactions"] as $transaction) { ?>
            <div class="row transaction-item p-2 m-2 text-center">
                <div class="col align-self-center"><?php echo $transaction["transaction_time"] ?></div>
                <div class="col align-self-center"><?php echo $transaction["username"] ?></div>
                <div class="col align-self-center text-break"><?php echo $transaction["transaction_remark"] ?></div>
                <div class="col align-self-center"><?php echo $transaction["amount"] ?></div>
            </div>
        <?php } ?>
    </div>

</body>

</html>
