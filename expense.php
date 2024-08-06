<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expensetrack_db";
$user_id = $_SESSION['user_id']; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $user_id; 
    $date = $_POST["date"];
    if(!isset($_POST["expenseCategory"]) || !isset($_POST["expenseAmount"])){ echo "<script>alert('Add Expenses to Submit!');</script>"; }
    else{
    $expenseCategories = $_POST["expenseCategory"];
    $expenseAmounts = $_POST["expenseAmount"];
    $expenses = array_combine($expenseCategories, $expenseAmounts);
    foreach ($expenses as $category => $amount) {
        $sqlCheck = "SELECT * FROM expenses WHERE date = '$date' AND user_id = '$userId' AND category = '$category'";
	  $resultCheck = mysqli_query($conn, $sqlCheck);
	if (mysqli_num_rows($resultCheck) > 0) {
    	$sqlUpdate = "UPDATE expenses SET amount = amount + $amount WHERE date = '$date' AND user_id = '$userId' AND category = '$category'";
    	mysqli_query($conn, $sqlUpdate);
	} else {
    	$sqlInsert = "INSERT INTO expenses (date, user_id, category, amount) VALUES ('$date', '$userId', '$category', $amount)";   
      mysqli_query($conn, $sqlInsert);
	}
	}
        if ($_SERVER["REQUEST_METHOD"] == "POST") : 
            echo "<script>alert('Expense Added Successfully');</script>";
        endif; 
      
      mysqli_close($conn);
	}
	}
	?>
     	
       
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 70px;
            height: 70vh; /* Set a specific height */
            overflow-y: auto; /* Make the container vertically scrollable */
        }
       
        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

	  .back {
            position: absolute;
            right:10px;
            height:700px;
            
         }
	   
        button.submit {
            background-color: #4caf50;
            color: #fff;
        }

        .expense {
            position: relative;
            margin-bottom: 20px;
        }

        .delete-button {
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px 10px;
            background-color: #ff5454;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Expense Tracker</h2>
        <form method="post">
            <!-- User details -->
            <label for="date">Date:</label>
            <input type="date" name="date" value="<?php echo isset($date) ? $date : ''; ?>" required>

            <!-- Expenses -->
            <div id="expenses">
                <?php if (isset($expenses)) : ?>
                    <?php foreach ($expenses as $key => $value) : ?>
                        <div class="expense">
                            <!-- Your existing HTML for expense input fields -->
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="expense">
                        <!-- Your existing HTML for expense input fields -->
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" onclick="addExpense()">Add Expense</button>
            <button type="submit" class="submit">Submit Expenses</button>
        </form>

        

        <script>
            let expenseCount = <?php echo isset($expenses) ? count($expenses) : 1; ?>;

function addExpense() {
    expenseCount++;

    const expensesDiv = document.getElementById('expenses');

    const newExpenseDiv = document.createElement('div');
    newExpenseDiv.classList.add('expense');

    newExpenseDiv.innerHTML = `
        <button type="button" class="delete-button" onclick="removeExpense(this)">Delete</button>
        <label for="expenseCategory${expenseCount}">Expense Category:</label>
        <input type="text" name="expenseCategory[]" required>

        <label for="expenseAmount${expenseCount}">Expense Amount:</label>
        <input type="text" name="expenseAmount[]" required>
    `;

    expensesDiv.appendChild(newExpenseDiv);
}

function removeExpense(button) {
    const expenseDiv = button.parentNode;
    const expensesDiv = document.getElementById('expenses');

    expensesDiv.removeChild(expenseDiv);
}
        </script>
    </div>
  
                <div class="back">         
                    <a href="home.php">Back</a>
                </div>
   
</body>
</html>
