<?php
include('config.php');
session_start();
$action = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }
    // Save or Edit Actions
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Initialize $stmt as NULL before any conditional blocks
    $stmt = null;

    if (isset($_POST['save'])) {
        if ($_POST['save'] === "Save") {
            // Prepare the statement for adding a new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $name, $email, $phone, $password);
                $action = 'add';
            } else {
                error_log("Prepare Error: " . $conn->error); // Log prepare error if any
                die("An Error Occurred While Preparing the Add Query.");
            }
        } elseif ($_POST['save'] === "Update" && isset($_POST['id'])) {
            // Prepare the statement for updating the user
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param("ssssi", $name, $email, $phone, $password, $id);
                $action = 'edit';
            } else {
                error_log("Prepare Error: " . $conn->error); // Log prepare error if any
                die("An Error Occurred While Preparing the Update Query.");
            }
        }
    }

    // Execute the query if the $stmt was successfully initialized
    if ($stmt && !$stmt->execute()) {
        error_log("Execution Error: " . $stmt->error);
        die("An Error Occurred While Saving the User.");
    }

    if ($stmt) {
        $stmt->close(); // Close the statement if it was successfully initialized
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    // Delete Action
    if ($_GET['action'] === 'del' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $action = "del";
            } else {
                error_log("Execution Error: " . $stmt->error);
                die("An Error Occurred While Deleting the User.");
            }
            $stmt->close();
        } else {
            error_log("Prepare Error: " . $conn->error); // Log prepare error if any
            die("An Error Occurred While Preparing the Delete Query.");
        }
    }
}

// Fetch all users to display
$users_sql = "SELECT * FROM users";
$all_users = mysqli_query($conn, $users_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Managament</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/toastr.css">
</head>

<body>
    <div class="container">
        <div class="wrapper p-5 m-5">
            <div class="d-flex p-2 mb-2 justify-content-between">
                <h2>All Users</h2>
                <div><a href="add_edit_user.php"><i data-feather="user-plus"></i></a></div>
            </div>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $all_users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td>
                                <div class="d-flex p-2 mb-2 justify-content-around">
                                    <!-- Confirmation for Deletion -->
                                    <i onclick="confirm_delete(<?php echo $user['id']; ?>);" class="text-danger" data-feather="trash-2"></i>
                                    <i onclick="edit(<?php echo $user['id']; ?>);" class="text-success" data-feather="edit"></i>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- Include Bootstrap, Toastr and jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/icons.js"></script>
    <script src="js/toastr.js"></script>
    <script src="js/main.js"></script>
    <?php
    if ($action != false) {
        if ($action == "add") { ?>
            <script>
                show_add();
            </script>
        <?php
        }
    }
    if ($action != false) {
        if ($action == "del") { ?>
            <script>
                show_del();
            </script>
        <?php
        }
    }

    if ($action != false) {
        if ($action == "edit") { ?>
            <script>
                show_update();
            </script>
    <?php
        }
    }
    ?>

    <script>
        feather.replace();
    </script>
</body>

</html>