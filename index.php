<?php
session_start();

// Initialize session variables
if (!isset($_SESSION['queue'])) $_SESSION['queue'] = [];
if (!isset($_SESSION['stack'])) $_SESSION['stack'] = [];

// Handle form submissions
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['queue_enqueue']) || isset($_POST['stack_push'])) {
        $item = isset($_POST['queue_item']) ? $_POST['queue_item'] : $_POST['stack_item'];
        if (!empty($item)) {
            if (isset($_POST['queue_enqueue'])) {
                $_SESSION['queue'][] = $item;
                $message = "Item added to the queue.";
            } else {
                $_SESSION['stack'][] = $item;
                $message = "Item added to the stack.";
            }
        } else {
            $message = "Please enter a valid item.";
        }
    }

    if (isset($_POST['queue_dequeue'])) {
        if (!empty($_SESSION['queue'])) {
            array_shift($_SESSION['queue']);
            $message = "Item removed from the queue.";
        } else {
            $message = "Queue is empty.";
        }
    }

    if (isset($_POST['stack_pop'])) {
        if (!empty($_SESSION['stack'])) {
            array_pop($_SESSION['stack']);
            $message = "Item removed from the stack.";
        } else {
            $message = "Stack is empty.";
        }
    }
}

// Function to display items with bullet points
function displayItems($items) {
    if (empty($items)) return "No items.";
    $output = "<ul>";
    foreach ($items as $item) {
        $output .= "<li>" . htmlspecialchars($item) . "</li>";
    }
    $output .= "</ul>";
    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFO Queue and LIFO Stack</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
        }
        
      
        .container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
            margin: 0 10px;
        }
        
        
        .items {
            background: #e9ecef;
            padding: 10px;
            min-height: 100px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        
        ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 0;
        }

        
        input[type="submit"] {
            width: 100%; 
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: gray; 
            color: white;           
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: darkgray; 
        }

        input[type="text"] {
            width: 95%;
            margin: 5px 0;
            padding: 10px;
        }

        
        .modal {
            display: none;
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000; 
        }
        
        .modal-content {
            background: white;
            margin: 20px auto; 
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 300px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>FIFO Queue and LIFO Stack</h1>
    <div class="container">
        <div class="card">
            <h2>FIFO Queue</h2>
            <div class="items"><?php echo displayItems($_SESSION['queue']); ?></div>
            <form method="post">
                <input type="text" name="queue_item" placeholder="Enter item" />
                <input type="submit" name="queue_enqueue" value="Enqueue" />
                <input type="submit" name="queue_dequeue" value="Dequeue" />
            </form>
        </div>
        <div class="card">
            <h2>LIFO Stack</h2>
            <div class="items"><?php echo displayItems($_SESSION['stack']); ?></div>
            <form method="post">
                <input type="text" name="stack_item" placeholder="Enter item" />
                <input type="submit" name="stack_push" value="Push" />
                <input type="submit" name="stack_pop" value="Pop" />
            </form>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span onclick="closeModal()" style="cursor:pointer;">&times;</span>
            <p id="modalMessage"><?php echo htmlspecialchars($message); ?></p>
        </div>
    </div>

    <script>
       
        window.onload = function() {
            const message = "<?php echo addslashes($message); ?>";
            if (message) {
                document.getElementById("modalMessage").innerText = message;
                document.getElementById("messageModal").style.display = "block";
            }
        };

        
        function closeModal() {
            document.getElementById("messageModal").style.display = "none";
        }

        
        window.onclick = function(event) {
            if (event.target === document.getElementById("messageModal")) {
                closeModal();
            }
        };
    </script>
</body>
</html>
