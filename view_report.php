<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expensetrack_db";
$user_id = $_SESSION['user_id'];
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch and sum expenses data based on the provided date range
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromDate = $_POST["fromDate"];
    $toDate = $_POST["toDate"];


    // Calculate total expense for all categories
    $sqlTotalExpense = "SELECT SUM(amount) as totalExpense FROM expenses WHERE user_id = '$user_id' AND date BETWEEN '$fromDate' AND '$toDate'";
    $resultTotalExpense = $conn->query($sqlTotalExpense);


    // Calculate total expense for each category
    $sqlCategoryTotal = "SELECT category, SUM(amount) as totalAmount FROM expenses WHERE user_id = '$user_id' AND date BETWEEN '$fromDate' AND '$toDate' GROUP BY category";
    $resultCategoryTotal = $conn->query($sqlCategoryTotal);
}


// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
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
            text-align: center;
        }
     
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .back {
            position: absolute;
            right:10px;
            height:700px;
            
         }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }


        th {
            background-color: #4CAF50;
            color: white;
        }
       
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color:orange">Expense Report</h1><br><br>
<form method="post">
            <label for="fromDate">From Date:</label>
            <input type="date" name="fromDate" required>


            <label for="toDate">To Date:</label>
            <input type="date" name="toDate" required>


            <button type="submit">Generate Report</button><br><br>
        </form>


        <?php
        if (isset($resultCategoryTotal) && $resultCategoryTotal->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Category</th><th>Total Amount</th></tr>";


            while ($row = $resultCategoryTotal->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["category"] . "</td>";
                echo "<td>" . $row["totalAmount"] . "</td>";
                echo "</tr>";
            }


            // Display total expense for all categories
            if ($resultTotalExpense->num_rows > 0) {
                $totalExpenseRow = $resultTotalExpense->fetch_assoc();
                echo "<tr><th>Total Expense</th><td>" . $totalExpenseRow["totalExpense"] . "</td></tr>";
            }


            echo "</table>";
        } elseif (isset($resultCategoryTotal) && $resultCategoryTotal->num_rows === 0) {
            echo "<p>No expenses found for the selected date range.</p>";
        }
        ?>
    </div>
 <div class="back">         
                    <a href="home.php">Back</a>
                </div>
</body>
</html>