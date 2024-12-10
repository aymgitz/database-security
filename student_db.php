<?php
require('./session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>

<!---->
<style>
        /* Modal container */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
        }

        /* Modal content */
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input, button {
            margin-top: 5px;
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
        
</style>
<!---->

</head>

<body>
    <header>
      <h1>Student's portal</h1>
    </header>
    

<!---->
<?php
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $birthdate = htmlspecialchars($_POST['birthdate']);
        $address = htmlspecialchars($_POST['address']);

        /*echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin: 20px 0;'>
                <strong>Success!</strong> Form submitted successfully.<br>
                <strong>Name:</strong> $name<br>
                <strong>Email:</strong> $email<br>
                <strong>Birthdate:</strong> $birthdate<br>
                <strong>Address:</strong> $address
              </div>";*/
              echo"<script>alert('submitted successfully')</script>";
    }
    ?>

    <!-- Button to open the modal -->
    <button id="openModalBtn">Enter Personal Information</button>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Personal Information Form</h2>
            <!-- Personal Information Form -->
            <form method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" required>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // Get modal and button elements
        const modal = document.getElementById('myModal');
        const btn = document.getElementById('openModalBtn');
        const closeBtn = document.querySelector('.close');

        // Open modal when button is clicked
        btn.onclick = () => {
            modal.style.display = 'block';
        };

        // Close modal when 'x' is clicked
        closeBtn.onclick = () => {
            modal.style.display = 'none';
        };

        // Close modal when clicking outside the modal
        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>


<!---->
    

    <form action="/rd-folder2/logout.php" method="post">
        <input type="submit" name="logout" value="LOGOUT" />
    </form>
</body>
</html>