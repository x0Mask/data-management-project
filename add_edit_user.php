<?php
include('config.php');
$title = 'Add';
$name = "";
$email = "";
$phone = "";
$password = "";
$btn_title = "Save";
$action = "";

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $action = "edit";
    $id = intval($_GET['id']);  // Ensure the ID is an integer
    $edit_sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result();
    if ($user->num_rows > 0) {
        $title = 'Update';
        $current_user = $user->fetch_assoc();
        $name = $current_user['name'];
        $email = $current_user['email'];
        $phone = $current_user['phone'];
        $password = $current_user['password'];
        $btn_title = "Update";
    } else {
        die("User not found.");
    }
    $stmt->close();
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="wrapper p-5 m-5">
            <div class="d-flex justify-content-between align-items-center mb-4"> 
                <h2><?php echo htmlspecialchars($title); ?> User</h2>
                <a href="index.php"><i data-feather="corner-down-left"></i></a>
            </div>

            <form action="index.php" method="post" id="userForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter Your Name" name="name" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter Your email" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter Your Phone Number" name="phone" autocomplete="FALSE">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Your Password" name="password" required>
                    <div id="passwordStrength"></div>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Your Password" required>
                </div>

                <?php if ($action == "edit") { ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php } ?>

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div>
                    <button type="submit" class="btn btn-primary" name="save" value="<?php echo htmlspecialchars($btn_title); ?>"><?php echo htmlspecialchars($btn_title); ?></button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/icons.js"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>