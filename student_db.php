<?php
require('./student_session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's page</title>
</head>

<body>
    <h1>STUDENT'S PORTAL</h1>
    
    <h2>DI NA KINAYA SAN EYEBAG </h2>
    <img src="img/eyebag.jpg" alt="">

    <form action="/rd-folder2/logout.php" method="post">
        <input type="submit" name="logout" value="LOGOUT" />
    </form>
</body>
</html>