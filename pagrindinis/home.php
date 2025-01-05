<?php
header('Cache-Control: no-cache, must-revalidate');
session_start(); // Start the session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /index.php');
    exit;
}
// Access the session variables
//echo $_SESSION['username'];
$profileImage = 'profile pictures/test.jpg'; // Default image

// Possible image extensions
$imageExtensions = ['jpg', 'png', 'gif'];

// Check if a custom image exists for the user in any of the supported formats
foreach ($imageExtensions as $ext) {
    $filePath = "profile pictures/{$_SESSION['user_id']}.$ext";
    if (file_exists($filePath)) {
        $profileImage = $filePath;
        break; // Exit the loop once we find a valid image
    }
}
// Retrieve transactions from the database
require_once("config.php");
$user_id = $_SESSION['user_id'];
$transactions = [];

// Retrieve the latest 5 income transactions
$stmt = $conn->prepare("SELECT amount, description, 'Income' AS type FROM income WHERE user_id = ? AND amount != 0 ORDER BY id DESC LIMIT 3");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// Retrieve the latest 5 expense transactions
$stmt = $conn->prepare("SELECT amount, description, category AS type FROM expenses WHERE user_id = ? ORDER BY id DESC LIMIT 3");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAGRINDINIS</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css" />
</head>
<body>
    
    <div class="overlay">
        <!-- virsutinis baras -->
        <div class="top-bar">
            <div class="button-container">
                <a href="home.php">
                <img src = "logo.png" alt="logo" class="logo">
                </a>
                <a href="about.php">
                <button>About</button>
                </a>
            </div>
            <div class="button-container right-buttons">
                <a href="settings.php">
                <button class="btn"><i class="fa fa-cog"></i></button>
                </a>
                <button class="btn"><i class="fa fa-bell"></i></button>
                
                <a href="login.php">
                <form action="destroy_session.php" method="post">
                <button class="btn" type="submit"><i class="fa-solid fa-right-from-bracket"></i> <!-- Destroy session --></button>
                </a>
            </form>
                <div class="navbar-profile">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture" class="profile-img">
                </div>
            </div>
        </div>

        <!-- pagrindas -->
        <div class="container">
            <!-- pasisveikinimas -->
            <div class="greeting">
                <h1>Hello, <?php echo $_SESSION['username']; ?>!</h1>
            </div>

            <!-- 3 dezes -->
            <div class="header">
                <div class="box balance">
                    <h2>Balance</h2>
                    <p id="balanceValue">€0</p>
                </div>
                <div class="box income">
                    <h2>Incomes</h2>
                    <p id="incomeValue">€0</p>
                </div>
                <div class="box expenses">
                    <h2>Expenses</h2>
                    <p id="expenseValue">€0</p>
                     <!--<button class="period-btn" id="selectPeriod">Select Period</button>-->
                </div>
            </div>

            <!-- 3 pagr. mygtukai -->
            <div class="actions buttons-between">
                <button class="action-btn" id="addIncomeBtn">
                    <i class="fa fa-plus-circle"></i>
                    <h4>Add Income</h4>
                    </button>
                <button class="action-btn" id="addExpenseBtn">
                    <i class="fa fa-minus-circle"></i> 
                <h4>Add Expense</h4>
                </button>
            </div>

            <!-- pagrindines sekcijos, kur grafikas ir kiti -->
            <div class="main-section">
                <div class="box-graph">
                    <h2>Expenses by Category</h2>
                    <div id="expensesChart"></div>
                </div>
                <div class="box-transactions">
                    <h2>Last Transactions</h2>
                    <ul id="transactionList">
                    <?php foreach ($transactions as $transaction): ?>
                        <li>
                                €<?php echo number_format($transaction['amount'], 2); ?> - <?php echo htmlspecialchars($transaction['description']); ?> (<?php echo htmlspecialchars($transaction['type']); ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="box-goals">
                    <h2>Goals</h2>
                    <ul id="goalsList">
                    </ul>
                    <div class="goal-input">
                        <input type="text" id="newGoal" placeholder="Add a new task" />
                        <button id="addGoalBtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- islaidu irasymas -->
    <div class="modal-overlay" id="expenseModal">
        <div class="modal">
            <span class="modal-close" id="closeModal">&times;</span>
            <h3>Add Expense</h3>
            <label for="expenseAmount">Amount:</label>
            <input type="number" min="0" id="expenseAmount" placeholder="Enter amount" />
            <label for="expenseDesc">What did you buy?</label>
            <input type="text" id="expenseDesc" placeholder="Description" />
            
            <label for="expenseCategory">Category:</label>
        <select id="expenseCategory">
            <option value="Food">Food</option>
            <option value="Rent">Rent</option>
            <option value="Utilities">Utilities</option>
            <option value="Entertainment">Entertainment</option>
            <option value="Miscellaneous">Miscellaneous</option>
        </select>

            <button onclick="submitExpense()">Submit</button>
        </div>
    </div>

    <!-- pinigu pridejimas -->
<div class="modal-overlay" id="incomeModal">
    <div class="modal">
        <span class="modal-close" id="closeIncomeModal">&times;</span>
        <h3>Add Income</h3>
        <label for="incomeAmount">Amount:</label>
        <input type="number" min="0" id="incomeAmount" placeholder="Enter amount" />
        <label for="incomeDesc">Description:</label>
        <input type="text" id="incomeDesc" placeholder="Income description" />
        <button onclick="submitIncome()">Submit</button>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <script src="home.js"></script>
    <script src="getProfileImage.js"></script>
</body>
</html>
