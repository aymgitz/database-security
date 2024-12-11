<?php 
require('./database.php');
require('./registration_info_session.php');


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['submitz'])){
        $retainEmail = $_SESSION['logId'];

        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
    
        $queryValidate = "select id from accounts where email = '$retainEmail' ";
        $resultValidate = $connection->query($queryValidate);
        $row = $resultValidate-> fetch_assoc();
        $logId = $row['id'];

        $queryInsert = "insert into personal_info(accounts_id,firstname,middlename,lastname,birthdate,address,gender)
                        values($logId, '$firstname', '$middlename', '$lastname', '$birthdate', '$address', '$gender') ";
        $resultInsert = $connection->query($queryInsert);

        if($resultInsert){
            echo"<script>alert('Updated successfully')</script>";
            unset($_SESSION['logId']);
            pathTo('student_db');
            
        }
        else{
            echo"<script>alert('There's a problem in inserting data')</script>";
        }
    }
    elseif(isset($_POST['back'])){
        unset($_SESSION['logId']);
        $_SESSION['status']='invalid';
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
            background-color: white;
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

        #submitz,#back{
            margin-top: 10px;
            border-radius: 30px;
            width: 40%;
        }

        #submitz:hover,#back:hover{
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
        #genders{
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        #back_button{
            margin-top: 10px;
            padding: 10px;
            border-radius: 10px;
        }
        .btn-container{
            display: flex;
            gap: 100px; 
            justify-content: center; 
        }
    </style>
</head>


<body>

    <div class="f-container">
        <form action="/rd-folder2/registration_information.php" method="post">          

            <!--<label for="roles">Role</label>-->
            <select id="genders" name="gender">
            <option value=""selected disabled hidden>What's your gender</option>
            <option  value="male">Male</option>
            <option  value="female">Female</option>
            <option  value="other">Other</option>
            </select>

            <label for="firstname">Firstname</label><br>
            <input id="firstname" type="text" name="firstname" placeholder="Enter your firstname"/><br>

            <label for="middlename">Middlename</label><br>
            <input id="middlename" type="text" name="middlename" placeholder="Enter your middlename"/><br>

            <label for="lastname">Lastname</label><br>
            <input id="lastname" type="text" name="lastname" placeholder="Enter your lastname"/><br>

            <label for="address">Address</label><br>
            <input id="address" type="text" name="address" placeholder="Enter your address"/><br>

            <label for="birthdate">Birthdate</label>
            <input id="birthdate" type="date" name="birthdate" placeholder="Enter your birthdate"><br>
          
            <div class="btn-container">
           <input id="back" type="submit" name="back" value="BACK"/>
           <input id="submitz" type="submit" name="submitz" value="SUBMIT"/>
           </div>
            <!--<input id="register" type="submit" name="register" value="REGISTER"/>
            <input id="login" type="submit" name="login" value="LOGIN"/>    
            
            <div class="error_container">
                <span class="input_error"><?php echo"$input_error"  ?></span>
            </div>-->
        </form>
    </div>
</body>
</html>