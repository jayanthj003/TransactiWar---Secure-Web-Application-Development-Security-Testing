<?php include_once __DIR__ . "/../url-definitions.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transfer</title>
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
			width: 90%;
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
		<?php require __DIR__ . '/navbar.php'; ?>

		<?php
		if (isset($_SESSION["transfer-success"])) {
			unset($_SESSION["transfer-success"]);
		?>
			<button class="form-control btn btn-success" disabled="true">
				Transfer Successful
			</button>
		<?php
		}
		?>

		<?php
		if (isset($_SESSION["transfer-error"])) {
			$error = $_SESSION["transfer-error"];
			unset($_SESSION["transfer-error"]);
		?>
			<button class="form-control btn btn-danger" disabled="true">
				<?php echo $error ?>
			</button>
		<?php
		}
		?>


	</div>

	<div class="profile-container">
		<div class="form-group">
			<form action="<?= CREATE_TRANSACTION ?>" method="post">
				<label for="username" class="fs-5 fw-bold">Receiver Username</label>
				<input type="text" class="form-control" id="username" name="receiver-username" required>
				<label type="number" class="mt-3 fs-5 fw-bold" for="amount">Amount</label>
				<input class="form-control" id="amount" name="amount" type="number" min="1" required>
				<label class="mt-3 fs-5 fw-bold" for="remark">Remark</label>
				<textarea type="text" class="form-control" id="remark" name="remark" rows="4" maxlength="200"></textarea>
				<input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
				<input type="submit" class="form-control btn btn-danger mt-3" value="Send Amount">
			</form>
		</div>

</body>

</html>
