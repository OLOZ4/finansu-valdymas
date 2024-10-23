google.charts.load('current', {'packages':['corechart']});

let expenseCategories = {
    'Food': 0,
    'Rent': 0,
    'Utilities': 0,
    'Entertainment': 0,
    'Miscellaneous': 0
};

google.charts.setOnLoadCallback(updateExpenseChart);


function updateExpenseChart() {

    let data = new google.visualization.DataTable();
    data.addColumn('string', 'Category');
    data.addColumn('number', 'Amount');
    
    Object.keys(expenseCategories).forEach(function(category) {
        data.addRow([category, expenseCategories[category]]);
    });

    // charto nustatymai
    let options = {
        pieHole: 0.4, 
        backgroundColor: 'transparent', 
        legend: { position: 'bottom' }, 
        pieSliceText: 'value', 
        colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
        width: 300, 
        height: 300 
    };

    // cia dive sukuriam pati charta
    let chart = new google.visualization.PieChart(document.getElementById('expensesChart'));
    chart.draw(data, options);
}

// padarom kad butu 0
function initializeValues() {
    document.getElementById('incomeValue').innerText = '$0.00';
    document.getElementById('expenseValue').innerText = '$0.00';
    document.getElementById('balanceValue').innerText = '$0.00';
}

// atnaujinimo funckija, kuri reaguos i mygtukus
function updateBalance() {
    let currentIncome = parseFloat(document.getElementById("incomeValue").innerText.replace('$', ''));
    let currentExpense = parseFloat(document.getElementById("expenseValue").innerText.replace('$', ''));
    let balance = currentIncome - currentExpense;
    document.getElementById("balanceValue").innerText = '$' + balance.toFixed(2);
}

// modal incomui
document.getElementById('addIncomeBtn').addEventListener('click', function() {
    document.getElementById('incomeModal').style.display = 'flex';
});
// modal islaidom
document.getElementById('addExpenseBtn').addEventListener('click', function() {
    document.getElementById('expenseModal').style.display = 'flex';
});

// uzdarom incomo modal
document.getElementById('closeIncomeModal').addEventListener('click', function() {
    document.getElementById('incomeModal').style.display = 'none';
});

// uzdarom expense modal
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('expenseModal').style.display = 'none';
});
function submitIncome() {
    const amount = parseFloat(document.getElementById('incomeAmount').value);
    const description = document.getElementById('incomeDesc').value;

    if (!isNaN(amount) && description.trim() !== "") {
        // income i tranzakcijas
        const transactionList = document.getElementById('transactionList');
        const li = document.createElement('li');
        li.textContent = `$${amount.toFixed(2)} - ${description} (Income)`;
        transactionList.appendChild(li);

        // upatatinam incoma
        let currentIncome = parseFloat(document.getElementById("incomeValue").innerText.replace('$', ''));
        currentIncome += amount;
        document.getElementById("incomeValue").innerText = '$' + currentIncome.toFixed(2);

        // updatinam balansa
        updateBalance();

        // modalo uzdarymas
        document.getElementById('incomeAmount').value = '';
        document.getElementById('incomeDesc').value = '';
        document.getElementById('incomeModal').style.display = 'none';
    } else {
        alert("Please enter a valid amount and description.");
    }
}

// cia su expense modalu
function submitExpense() {
    const amount = parseFloat(document.getElementById('expenseAmount').value);
    const description = document.getElementById('expenseDesc').value;
    const category = document.getElementById('expenseCategory').value; // leidzia pasirinkti expense type

    if (!isNaN(amount) && description.trim() !== "") {

        if (expenseCategories[category]) {
            expenseCategories[category] += amount;
        } else {
            expenseCategories[category] = amount; 
        }

        // nauja tranzakcija
        const transactionList = document.getElementById('transactionList');
        const li = document.createElement('li');
        li.textContent = `$${amount.toFixed(2)} - ${description} (${category})`;
        transactionList.appendChild(li);

        // updatas
        let currentExpense = parseFloat(document.getElementById("expenseValue").innerText.replace('$', ''));
        currentExpense += amount;
        document.getElementById("expenseValue").innerText = '$' + currentExpense.toFixed(2);

        // dar vienas
        updateBalance();

        document.getElementById('expenseAmount').value = '';
        document.getElementById('expenseDesc').value = '';
        document.getElementById('expenseModal').style.display = 'none';

        // charto upadatas
        updateExpenseChart();
    } else {
        alert("Please enter a valid amount, description, and category.");
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeValues(); // cia nulis
    updateExpenseChart(); // tuscias chartas
});

document.getElementById('addGoalBtn').addEventListener('click', function() {
    const goalText = document.getElementById('newGoal').value;

    if (goalText.trim() !== "") {
      
        const newGoalItem = document.createElement('li');
        newGoalItem.classList.add('goal-item');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('goal-checkbox');

        const goalDescription = document.createElement('span');
        goalDescription.classList.add('goal-text');
        goalDescription.textContent = goalText;

        // remove goals
        const removeButton = document.createElement('button');
        removeButton.classList.add('remove-goal-btn');
        removeButton.innerHTML = '&times;'; // X symbol for remove

        newGoalItem.appendChild(checkbox);
        newGoalItem.appendChild(goalDescription);
        newGoalItem.appendChild(removeButton);

        // naujas goals
        document.getElementById('goalsList').appendChild(newGoalItem);

        document.getElementById('newGoal').value = '';

        // checkboxas 
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                goalDescription.style.textDecoration = 'line-through'; // pazymim gatavas
                goalDescription.style.color = 'green'; // padarom zaliu
            } else {
                goalDescription.style.textDecoration = 'none'; 
                goalDescription.style.color = ''; 
            }
        });

        removeButton.addEventListener('click', function() {
            newGoalItem.remove();
        });
    } else {
        alert("Please enter a goal.");
    }
});