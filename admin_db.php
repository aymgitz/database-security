<?php  
require('./database.php');
session_start();

function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
}

if ($_SESSION['status'] == 'invalid' || empty($_SESSION['status'])) {
    $_SESSION['status'] = 'invalid';
    pathTo('login');
} elseif ($_SESSION['status'] == 'balik_registration') {
    pathTo('registration');
} elseif ($_SESSION['status'] == 'student_valid') {
    pathTo('student_db');
} elseif ($_SESSION['status'] == 'teacher_valid') {
    pathTo('teacher_db');
}

$filterStatus = isset($_GET['status']) ? $_GET['status'] : 'pending'; // Default to 'pending'

// Validate filterStatus to prevent SQL injection
$validStatuses = ['pending', 'approved', 'rejected'];
if (!in_array($filterStatus, $validStatuses)) {
    $filterStatus = 'pending'; // Default to 'pending' if invalid
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $updateStatusQuery = "UPDATE accounts SET status = 'approved' WHERE id = ?";
    }
    elseif ($action == 'reject') {
        $updateStatusQuery = "UPDATE accounts SET status = 'rejected' WHERE id = ?";
    }

    // Prepare and execute the update query
    if ($stmt = mysqli_prepare($connection, $updateStatusQuery)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            // Success
        } else {
            // Handle error
            echo "Error updating status: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch accounts based on the selected status
$query = "SELECT id, email, role, status FROM accounts WHERE status = ?";
if ($stmt = mysqli_prepare($connection, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $filterStatus);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} 
else {
    // Handle query error
    echo "Error fetching accounts: " . mysqli_error($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Accounts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .table-container {
            width: 80%;
            margin: 0 auto;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            padding: 5px 10px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }
        .reject {
            background-color: red;
        }
        .button:hover {
            opacity: 0.8;
        }
        .filter-buttons {
            margin-bottom: 20px;
        }
        .filter-buttons a {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .filter-buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="filter-buttons">
        <a href="?status=pending" class="button">Pending Accounts</a>
        <a href="?status=approved" class="button">Approved Accounts</a>
        <a href="?status=rejected" class="button">Rejected Accounts</a>
    </div>

    <div class="table-container">
        <h2><?php echo ucfirst(htmlspecialchars($filterStatus)); ?> Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display accounts based on status
                while ($row = mysqli_fetch_assoc($result)) {
                    if($row['status']!='approved'&&$row['status']!='rejected'){
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='action' value='approve'>
                                    <button type='submit' class='button'>Approve</button>
                                </form>
                                <form method='POST' action=''>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='action' value='reject'>
                                    <button type='submit' class='button reject'>Reject</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    elseif($row['status']=='rejected'){
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='action' value='approve'>
                                    <button type='submit' class='button'>Approve</button>
                                </form>                               
                            </td>";
                        echo "</tr>";
                    }
                    else{
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>                              
                                <form method='POST' action=''>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='action' value='reject'>
                                    <button type='submit' class='button reject'>Reject</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <form action="/rd-folder2/logout.php" method="post">
        <input type="submit" name="logout" value="LOGOUT" />
    </form>
</body>
</html>
