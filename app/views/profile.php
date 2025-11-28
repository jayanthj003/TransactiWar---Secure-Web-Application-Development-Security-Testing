<?php include_once __DIR__ . "/../url-definitions.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar-container {
            width: 100%;
            background-color: white;
        }

        .profile-container {
            background-color: #d1d1d1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 60%;
            margin-top: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            background-color: black;
            border-radius: 50%;
            margin: 0 auto 15px auto;
        }

        .profile-title {
            font-family: 'Georgia', serif;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .input-group {
            margin-bottom: 10px;
        }

        .edit-btn {
            cursor: pointer;
            background: none;
            border: none;
            color: #555;
            padding: 0 10px;
        }

        .edit-btn:hover {
            color: black;
        }
    </style>

    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="navbar-container">
        <?php require 'navbar.php'; ?>
        <?php
        if (isset($_SESSION["update-success"])) {
            $status = $_SESSION["update-success"];
            unset($_SESSION["update-success"]);
        ?>
            <button class="form-control btn btn-success" disabled="true">
                <?php echo $status; ?>
            </button>
        <?php
        }
        ?>

        <?php
        if (isset($_SESSION["update-error"])) {
            $error = $_SESSION["update-error"];
            unset($_SESSION["update-error"]);
        ?>
            <button class="form-control btn btn-danger" disabled="true">
                <?php echo $error ?>
            </button>
        <?php
        }
        ?>
    </div>

    <div class="profile-container">

        <img class="profile-pic" src=<?php echo $data["profile_picture_path"] ?>>
        <?php if ($data["is_owner"]) { ?>
            <div class="form-group">
                <form action="<?php echo PROFILE_PICTURE_UPDATE_HANDLER ?>" method="post" enctype="multipart/form-data">
                    <input type="file" class="form-control-file btn" name="profile-picture" required>
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                    <input type="submit" class="form-control btn btn-danger mt-1" value="Update Profile Picture">
                </form>
            </div>

            <hr>

            <div class="form-group mt-3">
                <p class="fs-5 fw-bold">Username: <?= $data["username"]; ?></p>
            </div>

            <hr>

            <div class="form-group mt-3">
                <form action="<?php echo EMAIL_UPDATE_HANDLER ?>" method="post">
                    <label for="email" class="fs-5 fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $data["email"]; ?>" required>
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                    <input type="submit" class="form-control btn btn-danger mt-1" value="Update Email">
                </form>
            </div>

            <hr>

            <div class="form-group mt-3">
                <form action="<?= PASSWORD_UPDATE_HANDLER ?>" method="post">
                    <label class="fs-5 fw-bold">Password</label>
                    <input type="password" class="form-control mt-1" placeholder="Old Password" name="old-password" minlength="8" required>
                    <input type="password" class="form-control mt-1" placeholder="New Password" name="new-password" minlength="8" required>
                    <input type="password" class="form-control mt-1" placeholder="Confirm New Password" name="confirm-new-password" minlength="8" required>
                    <div class="form-text fw-thinner m-0">Password should be atleast 8 charachers long</div>
                    <input type="submit" class="form-control btn btn-danger mt-1" value="Update Password">
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                </form>
            </div>

            <hr>

            <div class="form-group mt-3">
                <form action="<?php echo DESCRIPTION_UPDATE_HANDLER ?>" method="post">
                    <label for="description" class="fs-5 fw-bold">Description</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="4" maxlength="200" required> <?php echo $data["description"]; ?></textarea>
                    <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                    <input type="submit" class="form-control btn btn-danger mt-1" value="Update Description">
                </form>
            </div>

        <?php } else { ?>
            <hr>

            <div class="form-group mt-3">
                <p class="fs-5 fw-bold">Username: <?= $data["username"]; ?></p>
            </div>

            <hr>

            <div class="form-group mt-3">
                <label for="email" class="fs-5 fw-bold">Email: <?php echo $data["email"]; ?></label>
            </div>

            <hr>

            <div class="form-group mt-3">
                <label for="description" class="fs-5 fw-bold">Description</label>
                <p type="text" class="form-control form-control-plaintext" id="description" name="description" rows="4" readonly> <?php echo $data["description"]; ?></p>
            </div>
        <?php } ?>
</body>

</html>
