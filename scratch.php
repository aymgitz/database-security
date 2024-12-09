<?php
// define variables and set to empty values
$emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['email'])) {
    $emailError = "Email is required";
  } 
  else {
    $email = retouch_input($_POST['email']);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = "Invalid email format";
    }
  }
  
  if (empty($_POST['password'])) {
    $passwordError = "Password is required";
  }    
}
?>




<?php 
  require('./database.php');
session_start(); // Security guard starts here

function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
}

$input_error = "";
if (isset($_POST['register'])) {
    if($_POST['password'] == $_POST['confirm_password'] && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the email already exists in the database
        $queryValidate = "SELECT * FROM accounts WHERE email = ?";
        $stmtValidate = mysqli_prepare($connection, $queryValidate);
        mysqli_stmt_bind_param($stmtValidate, 's', $email);
        mysqli_stmt_execute($stmtValidate);
        $resultValidate = mysqli_stmt_get_result($stmtValidate);

        if (mysqli_num_rows($resultValidate) > 0) {
            // Email already exists
            $input_error = "*Email already in use. Please login.";          
        } else {
            // Email does not exist, proceed to insert the new user
            $queryInsert = "INSERT INTO accounts (email, password) VALUES (?, ?)";
            $stmtInsert = mysqli_prepare($connection, $queryInsert);
            mysqli_stmt_bind_param($stmtInsert, 'ss', $email, $hashed_password);  
            $insertSuccess = mysqli_stmt_execute($stmtInsert);

            if ($insertSuccess) {
                // Registration successful
                echo "<script>alert('Registration successful! Please login.')</script>";
                pathTo('login');
            } else {
                // Insertion failed
                $input_error = "*There was an error during registration. Please try again later.";
            }
        }
    } elseif ($_POST['password'] != $_POST['confirm_password'] && !empty($_POST['email'])) {
        $input_error = "*Passwords do not match!";
    } else {
        $input_error = "*Please fill out all fields!";
    }
}
elseif (isset($_POST['login'])) {
    pathTo('login');
} else {
    $_SESSION['stats'] = 'invalid'; // Security guard
}      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: black;
        }

        .f-container {
            width: 90%;
            max-width: 500px;
            padding: 50px;
            border-radius: 5%;
            background-color: antiquewhite;
            box-shadow: rgba(11, 201, 122, 0.897) 0px 7px 29px 0px;
        }

        input {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        #submit {
            width: 50%;
            margin-top: 10px;
        }
        
        #confirm_password {
            margin-top: 5px;
        }

        #register {
            margin-top: 10px;
            border-radius: 30px;
        }

        #register:hover {
            background-color: greenyellow;
        }

        #login {
            color: blue;
            border: none;
            background-color: transparent;
            margin-top: 40px;
            text-decoration: underline;
        }

        #login:hover {
            font-weight: 600;
        }

        .input_error {
            color: red;
        }

        /* Modal (floating window) styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.4); /* Black with transparency */
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            text-align: center;
        }

        .modal button {
            background-color: greenyellow;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal button:hover {
            background-color: yellow;
        }
    </style>
</head>

<body>

    <div class="f-container">
        <form action="/rd-folder2/registration.php" method="post">          
            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" placeholder="Enter your email"/><br>

            <label for="password">Password</label><br>
            <input id="password" type="password" name="password" placeholder="Enter your password"/><br>

            <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm your password"/> <br>
           
            <input id="register" type="submit" name="register" value="REGISTER"/>
            <input id="login" type="submit" name="login" value="LOGIN"/>    
            
            <div class="error_container">
                <span class="input_error"><?php echo "$input_error"; ?></span>
            </div>
        </form>
    </div>

    <!-- Modal for error display -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <p id="errorMessage"></p>
            <button onclick="closeModal()">Okay</button>
        </div>
    </div>

    <script>
        // Function to show the modal
        function showModal(errorMessage) {
            document.getElementById("errorMessage").textContent = errorMessage;
            document.getElementById("errorModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("errorModal").style.display = "none";
        }

        // Check if there's an error to show
        <?php if (!empty($input_error)) { ?>
            showModal("<?php echo $input_error; ?>");
        <?php } ?>
    </script>
</body>
</html>




//////////////////////////////
// define variables and set to empty values
/*$emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['email'])) {
    $emailError = "Email is required";
  } 
  else {
    $email = retouch_input($_POST['email']);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = "Invalid email format";
    }
  }
  
  if (empty($_POST['password'])) {
    $passwordError = "Password is required";
  }    
}*/
////////////////////////////////////

<?php
require('./database.php');
session_start();

function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
}

if ($_SESSION['status'] == 'invalid' || empty($_SESSION['status'])) {  
    $_SESSION['status'] = 'balik_registration'; 
} elseif ($_SESSION['status'] == 'valid') {
    pathTo('home');
}

$input_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Validate the form data
        if ($_POST['password'] == $_POST['confirm_password'] && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['role'])) {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role = trim($_POST['role']);

            // Check if the email already exists in the database
            $queryValidate = "SELECT * FROM accounts WHERE email = ?";
            $stmtValidate = mysqli_prepare($connection, $queryValidate);
            mysqli_stmt_bind_param($stmtValidate, 's', $email);
            mysqli_stmt_execute($stmtValidate);
            $resultValidate = mysqli_stmt_get_result($stmtValidate);

            if (mysqli_num_rows($resultValidate) > 0) {
                // Email already exists
                $input_error = "*Email already in use. Please login.";
            } else {
                // If email does not exist, proceed to insert the new user
                $queryInsert = "INSERT INTO accounts (email, password, role) VALUES (?, ?, ?)";
                $stmtInsert = mysqli_prepare($connection, $queryInsert);
                mysqli_stmt_bind_param($stmtInsert, 'sss', $email, $hashed_password, $role);
                $insertSuccess = mysqli_stmt_execute($stmtInsert);

                // Registration successful
                if ($insertSuccess) {
                    echo "<script>alert('Registration successful! Please login.')</script>";
                    pathTo('login');
                } else {
                    // Insertion failed
                    $input_error = "*There was an error during registration. Please try again later.";
                }
            }
        } elseif ($_POST['password'] != $_POST['confirm_password']) {
            $input_error = "*Passwords do not match!";
        } else {
            $input_error = "*Please fill out all fields!";
        }
    } elseif (isset($_POST['login'])) {
        pathTo('login');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: black;
        }

        .f-container {
            width: 90%;
            max-width: 500px;
            padding: 50px;
            border-radius: 5%;
            background-color: antiquewhite;
            box-shadow: rgba(11, 201, 122, 0.897) 0px 7px 29px 0px;
        }

        input {
            padding: 10px;
            width: 95%;
            margin-bottom: 10px;
        }

        #register {
            margin-top: 10px;
            border-radius: 30px;
            width: 100%;
        }

        #register:hover {
            background-color: greenyellow;
        }

        #login {
            color: blue;
            border: none;
            background-color: transparent;
            margin-top: 40px;
            text-decoration: underline;
        }

        #login:hover {
            font-weight: 600;         
        }

        .input_error {
            color: red;
        }

        #roles {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="f-container">
        <form action="/rd-folder2/registration.php" method="post">          

            <label for="roles">Role</label>
            <select id="roles" name="role" required>
                <option value="" selected disabled hidden>Choose a role</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>

            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" placeholder="Enter your email" required /><br>

            <label for="password">Password</label><br>
            <input id="password" type="password" name="password" placeholder="Enter your password" required /><br>

            <label for="confirm_password">Confirm Password</label><br>
            <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm your password" required /><br>
           
            <input id="register" type="submit" name="register" value="REGISTER"/>
            <input id="login" type="submit" name="login" value="LOGIN"/>    
            
            <div class="error_container">
                <span class="input_error"><?php echo $input_error; ?></span>
            </div>
        </form>
    </div>
</body>
</html>
///////////////////////////////////////////////////////////////////
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
} elseif ($_SESSION['status'] == 'valid') {
    pathTo('home');
} elseif ($_SESSION['status'] == 'admin_valid') {
    // Admin access logic
}

$filterStatus = isset($_GET['status']) ? $_GET['status'] : 'pending'; // Default to 'pending'

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $updateStatusQuery = "UPDATE accounts SET status = 'approved' WHERE id = ?";
    } elseif ($action == 'reject') {
        $updateStatusQuery = "UPDATE accounts SET status = 'rejected' WHERE id = ?";
    }

    // Prepare and execute the update query
    if ($stmt = mysqli_prepare($connection, $updateStatusQuery)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }
}

// Fetch accounts based on the selected status
$query = "SELECT id, email, role, status FROM accounts WHERE status = '$filterStatus'";
$result = mysqli_query($connection, $query);
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
        <h2><?php echo ucfirst($filterStatus); ?> Accounts</h2>
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
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
////////////////////////////////////////////////////////
original login <code>
<?php 
require('./database.php');

session_start();

function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
}

function retouch_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//pathTo('registration');
if($_SESSION['status']=='balik_registration' || empty($_SESSION['status'])){
    $_SESSION['status']='invalid';
    
}
elseif ($_SESSION['status'] == 'valid') {
    pathTo('home');
  }
elseif($_SESSION['status']=='admin_valid'){
    
    $_SESSION['status']='invalid';
    pathTo('login');
    //pathTo('admin_db');
  }


$input_error = "";//defining error
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['login'])) {
        $email = retouch_input($_POST['email']);
        $password = trim($_POST['passwordz']);
        
        ////////////////////////
        if($email== "admin@gmail.comz" && $password == "admin"){
            $_SESSION['status']= 'admin_valid';
            pathTo('admin_db');

        }
        elseif(empty($email) || empty($password)) {
            $input_error = "*All fields are required!";
        } 
        else {
            // Prepare and execute query to get user by email
            $queryValidate = "SELECT * FROM accounts WHERE email = '$email' ";
            $result = $connection->query($queryValidate);
            $row = $result->fetch_assoc();

            if($result->num_rows>0 && $row['status'] == 'approved' ){
                //$row = $result->fetch_assoc();
                $stored_hashed_password = $row['password'];

                if(password_verify($password, $stored_hashed_password)){
                    echo"<script>alert('Login successful!')</script>";
                    $_SESSION['status'] = 'valid';
                    pathTo('home');
                }
                else{
                    $input_error = "*Invalid password!";              
                }
            }
            elseif ($row['status'] == 'pending') {
                $input_error = "*That account is still pending!";
            }
            else{
                $input_error = "*No user found with that email!";
            }
        }
    }
    elseif(isset($_POST['register'])){
        pathTo('registration');
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>
    
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: white;
        }

       .f-container{
            width: 90%;
            max-width: 500px;
            padding: 50px;
            border-radius: 5%;
            background-color: antiquewhite;
            box-shadow: rgba(11, 201, 122, 0.897) 0px 7px 29px 0px;
        }

        input{
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        #submit{
            width: 50%;
            margin-top: 10px;
        }
        
        #login{
            margin-top: 10px;
            border-radius: 20px;
        }

        #login:hover{
            background-color: greenyellow;
        }

        #register{
            color: blue;
            border: none;
            background-color: transparent;
            margin-top: 40px;
            text-decoration: underline;
        }

        #register:hover{
            font-weight: 600;         
        }

        .input_error{
            color: red;
        }
    </style>
</head>

<body>
    <div class="f-container">
        <form action="/rd-folder2/login.php" method="post">
            
            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" placeholder="Enter your email"/><br>

            <label for="password">Password</label><br>
            <input id="password" type="password" name="passwordz" placeholder="Enter your password"/><br>

            <input id="login" type="submit" name="login" value="LOGIN"/>
            <input id="register" type="submit" name="register" value="REGISTER"/><br>
            
            <div class="error_container">
                <span class="input_error"><?php echo"$input_error"  ?></span>
            </div>
        </form>
    </div>
</body>
</html>
</code>