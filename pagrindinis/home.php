<?php



   
session_start(); // Start the session

// Access the session variables
//echo $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAGRINDINIS</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css" />
</head>
<body>
    
    <div class="overlay">
        <!-- virsutinis baras -->
        <div class="top-bar">
            <div class="button-container">
                <a href="main.html">
                <img src = "logo.png" alt="logo" class="logo">
                </a>
                <a href="about.html">
                <button>About</button>
                </a>
            </div>
            <div class="button-container right-buttons">
                <a href="settings.html">
                <button class="btn"><i class="fa fa-cog"></i></button>
                </a>
                <button class="btn"><i class="fa fa-bell"></i></button>
                
                <a href="login.php">
                <form action="destroy_session.php" method="post">
                <button class="btn" type="submit"><i class="fa-solid fa-right-from-bracket"></i> <!-- Destroy session --></button>
                </a>
            </form>
                <div class="navbar-profile">
                    <img src="profile-pic.jpg" alt="Profile Picture" class="profile-img">
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
                    <button class="period-btn" id="selectPeriod">Select Period</button>
                </div>
            </div>

            <!-- 3 pagr. mygtukai -->
            <div class="actions buttons-between">
                <button class="action-btn" id="addIncomeBtn"><i class="fa fa-plus-circle"></i> Add Income</button>
                <button class="action-btn" id="addExpenseBtn"><i class="fa fa-minus-circle"></i> Add Expense</button>
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
            <input type="number" id="expenseAmount" placeholder="Enter amount" />
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
        <input type="number" id="incomeAmount" placeholder="Enter amount" />
        <label for="incomeDesc">Description:</label>
        <input type="text" id="incomeDesc" placeholder="Income description" />
        <button onclick="submitIncome()">Submit</button>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
