<?php 
require('./database.php');
session_start();
function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
  }

if($_SESSION['status']=='invalid' || empty($_SESSION['status'])){  
    $_SESSION['status']='balik_registration'; 
}
elseif($_SESSION['status']=='valid'){
    pathTo('home');
}

$input_error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['register'])) {
        
        if($_POST['password']==$_POST['confirm_password']&&!empty($_POST['email']&&$_POST['password'])&&!empty($_POST['confirm_password'])&&!empty($_POST['role'])){
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role =  trim($_POST['role']);

            // Check if the email already exists in the database
            $queryValidate = "SELECT * FROM accounts WHERE email = ?";
            $stmtValidate = mysqli_prepare($connection, $queryValidate);
            mysqli_stmt_bind_param($stmtValidate, 's', $email);  // 's' means string type diri SANAOL
            mysqli_stmt_execute($stmtValidate);
            $resultValidate = mysqli_stmt_get_result($stmtValidate);

            if (mysqli_num_rows($resultValidate) > 0) {
                // Email already exists
                $input_error = "*Email already in use. Please login.";          
            
            }
            else {
                // If email does not exist, proceed to insert the new user
                $queryInsert = "INSERT INTO accounts (email, password,role) VALUES (?, ?, ?)";
                $stmtInsert = mysqli_prepare($connection, $queryInsert);
                mysqli_stmt_bind_param($stmtInsert, 'sss', $email, $hashed_password, $role );  // 'sss' means insurance (string parameters)
                $insertSuccess = mysqli_stmt_execute($stmtInsert);

                // Registration successful
                if ($insertSuccess) {
                    echo "<script>alert('Registration successful! Please login.')</script>";
                    pathTo('login');
                } 
                else {
                    // Insertion failed
                    $input_error = "*There was an error during registration. Please try again later.";
                }
            }
        }
        elseif($_POST['password']!=$_POST['confirm_password']&&!empty($_POST['email'])&&!empty($_POST['confirm_password']&&$_POST['password'])){
            $input_error = "* Magkaiba an password!!!";
        }   
        else{
            $_SESSION['status'] = 'invalid';
            $input_error = "* Fillupan mo intero uy!";
        }
    }
    elseif(isset($_POST['login'])){
        pathTo('login');
    } 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration page</title>
    
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

        input{
            padding: 10px;
            width: 95%;
            margin-bottom: 10px;
        }

        #submit{
            width: 50%;
            margin-top: 10px;
        }
        
        #confirm_password{
            margin-top: 5px;
        }

        #register{
            margin-top: 10px;
            border-radius: 30px;
            width: 100% ;
        }

        #register:hover{
            background-color: greenyellow;
        }

        #login{
            color: blue;
            border: none;
            background-color: transparent;
            margin-top: 40px;
            text-decoration: underline;
        }

        #login:hover{
            font-weight: 600;         
        }
        .input_error{
            color: red;
        }
        #roles{
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>


<body>

    <div class="f-container">
        <form action="/rd-folder2/registration.php" method="post">          

            <!--<label for="roles">Role</label>-->
            <select id="roles" name="role">
            <option value=""selected disabled hidden>Choose a role</option>
            <option  value="admin">Admin</option>
            <option  value="teacher">Teacher</option>
            <option  value="student">Student</option>
            </select>

            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" placeholder="Enter your email"/><br>

            <label for="password">Password</label><br>
            <input id="password" type="password" name="password" placeholder="Enter your password"/><br>

            <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm your password"/> <br>
           
            <input id="register" type="submit" name="register" value="REGISTER"/>
            <input id="login" type="submit" name="login" value="LOGIN"/>    
            
            <div class="error_container">
                <span class="input_error"><?php echo"$input_error"  ?></span>
            </div>
        </form>
    </div>
</body>
</html>