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

// Updated
if($_SESSION['status']=='invalid' || empty($_SESSION['status'])||$_SESSION['status']=='admin_valid'
  || $_SESSION['status']='student_valid' || $_SESSION['status']='teacher_valid'||
   $_SESSION['status']='valid'|| $_SESSION['status']='balik_registration'
){
    $_SESSION['status']='invalid';
}

// Define error variable
$input_error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['login'])) {
        $email = retouch_input($_POST['email']);
        $password = trim($_POST['passwordz']);
        
        if($email == "admin@gmail.comz" && $password == "admin"){
            $_SESSION['status'] = 'admin_valid';
            pathTo('admin_db');
        }
        elseif(empty($email) || empty($password)) {
            $input_error = "*All fields are required, I'm not psychic!";
        } 
        else {
            // Prepare and execute query to get user by email
            $queryValidate = "SELECT * FROM accounts WHERE email = '$email' ";
            $result = $connection->query($queryValidate);
            
            if ($result) {
                // Check if the result contains rows
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    
                    if($row['status'] == 'approved'&& $row['role'] == 'teacher'){
                        // Check password
                        $stored_hashed_password = $row['password'];
                        if(password_verify($password, $stored_hashed_password)){
                            echo "<script>alert('Login successful!')</script>";
                            $_SESSION['status'] = 'teacher_valid';
                            pathTo('teacher_db');
                        } else {
                            $input_error = "*Isure an  password uy!";
                        }
                    }
                    elseif($row['status'] == 'approved'&& $row['role'] == 'student'){
                        // Check password
                        $stored_hashed_password = $row['password'];
                        $retainEmail = $email;
                        if(password_verify($password, $stored_hashed_password)){
                            $queryAccounts_id = "select pi.accounts_id from personal_info pi
                            join accounts ac on pi.accounts_id = ac.id where ac.email = '$retainEmail' ";
                            $resultAccounts_id = $connection->query($queryAccounts_id);
                            if($resultAccounts_id->num_rows > 0) {
                                echo "<script>alert('Login successful!')</script>";
                                $_SESSION['status'] = 'student_valid';
                                pathTo('student_db');
                            }
                            else{
                                $_SESSION['logId']= $retainEmail;
                                echo "<script>alert('Please complete your information')</script>";
                                $_SESSION['status'] = 'valid';
                                pathTo('registration_information');
                            }
                            

                        } else {
                            $input_error = "*Isure an  password uy!";
                        }
                    }
                    elseif($row['role']=='admin'&&$row['status']=='approved'){
                        $stored_hashed_password = $row['password'];
                        if(password_verify($password, $stored_hashed_password)){
                        $_SESSION['status'] = 'admin_valid';
                        pathTo('admin_db');
                        }
                        else{
                            $input_error = "*You are not my master!";
                        }
                    }
                    elseif ($row['status'] == 'pending') {
                        $input_error = "*That account is still pending!";
                    }else {
                        $input_error = "*That account is rejected";
                    }

                } else {
                    $input_error = "*No user found with that email!";
                }
            } else {
                $input_error = "*Error executing query.";
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
            background-color: black;
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
            width: 95%;
            margin-bottom: 10px;
        }

        #submit{
            width: 50%;
            margin-top: 10px;
        }
        
        #login{
            margin-top: 10px;
            border-radius: 20px;
            width: 100%;
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
