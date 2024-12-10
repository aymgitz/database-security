<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal Example</title>
    <style>
        /* Modal container (hidden by default) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black with opacity */
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Could be more or less depending on screen size */
            border-radius: 10px;
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Button to open the modal -->
    <button id="openModalBtn">Open Modal</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modal Header</h2>
            <p>
                <?php
                // PHP to dynamically generate modal content
                echo "This is a dynamically generated message from PHP!";
                ?>
            </p>
        </div>
    </div>

    <script>
        // Get modal and button elements
        const modal = document.getElementById('myModal');
        const btn = document.getElementById('openModalBtn');
        const closeSpan = document.querySelector('.close');

        // Open modal when button is clicked
        btn.onclick = () => {
            modal.style.display = 'block';
        };

        // Close modal when 'x' is clicked
        closeSpan.onclick = () => {
            modal.style.display = 'none';
        };

        // Close modal when clicking outside of it
        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>
