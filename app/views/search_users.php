<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search Users</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
        }

        .main-container {
            background-color: #d1d1d1;
            padding: 30px;
            max-width: 90%;
            margin: 30px auto;
            border-radius: 15px;
        }

        .main-title {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            background-color: white;
        }

        .contact-list {
            width: 100%;
        }

        .contact-item {
            background-color: white;
            border-radius: 10px;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .contact-buttons button {
            background-color: black;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-left: 5px;
            transition: background-color 0.2s;
        }

        .contact-buttons button:hover {
            background-color: #444;
        }

        h5 {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>

    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="navbar-container">
        <?php require_once __DIR__ . '/navbar.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <form method="get" action="<?= SEARCH_USERS ?>">
            <input type="text" pattern="^[a-zA-Z0-9_]+" class="search-bar" name="username"
                <?php if (isset($data['search-query']) && $data['search-query'] != '') {
                    echo "value=" .  $data['search-query'];
                }  ?>
                placeholder="🔍 Search Users">
        </form>

        <h5>All Users</h5>
        <div class="contact-list">
            <?php
            if (!isset($data["users"])) {
            ?>
                <div class="contact-item" style="justify-content: center;">
                    <span>No users Found/Invalid Username</span>
                </div>
                <?php
            } else {
                foreach ($data["users"] as $user) { ?>
                    <div class="contact-item">
                        <span><?= $user ?></span>
                        <div class="contact-buttons">
                            <a class="btn btn-primary" href="<?= OTHER_USER_PROFILE . $user ?>">Profile</a>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>

</body>

</html>
