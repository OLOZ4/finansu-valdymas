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
        data.addRow([category,parseFloat(expenseCategories[category])]);
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
    document.getElementById('incomeValue').innerText = '€0.00';
    document.getElementById('expenseValue').innerText = '€0.00';
    document.getElementById('balanceValue').innerText = '€0.00';
}

// atnaujinimo funckija, kuri reaguos i mygtukus
function updateBalance() {
    let currentIncome = parseFloat(document.getElementById("incomeValue").innerText.replace('€', ''));
    let currentExpense = parseFloat(document.getElementById("expenseValue").innerText.replace('€', ''));
    let balance = currentIncome - currentExpense;
    document.getElementById("balanceValue").innerText = '€' + balance.toFixed(2);
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

    console.log(amount); // for testing
    if (!isNaN(amount) && description.trim() !== "" && amount > 0) {

        //***************************************** */ BACKEND
        try {
            fetch("write_income.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `amount=${amount}&description=${description}`
            })
            .then(response => response.text())
            .then(data => console.log(data))
            .catch(error => console.error(error));
        } catch (error) {
            console.error("Error submitting expense:", error);
        }

        //***************************************** */
        // income i tranzakcijas
        const transactionList = document.getElementById('transactionList');
        const li = document.createElement('li');
        li.textContent = `€${amount.toFixed(2)} - ${description} (Income)`;
        transactionList.appendChild(li);

        // upatatinam incoma
        //let currentIncome = parseFloat(document.getElementById("incomeValue").innerText.replace('$', ''));
        //currentIncome += amount;
        //document.getElementById("incomeValue").innerText = '€' + currentIncome.toFixed(2);

        // updatinam balansa
        updateBalance();

        // modalo uzdarymas
        document.getElementById('incomeAmount').value = '';
        document.getElementById('incomeDesc').value = '';
        document.getElementById('incomeModal').style.display = 'none';
    } else {
        alert("Please enter a valid amount and description.");
    }
    update_all();
}

// cia su expense modalu
function submitExpense() {
    const amount = parseFloat(document.getElementById('expenseAmount').value);
    const description = document.getElementById('expenseDesc').value;
    const category = document.getElementById('expenseCategory').value; // leidzia pasirinkti expense type
    
    console.log(category); // for testing
    if (!isNaN(amount) && description.trim() !== "" && amount > 0) {

        //***************************************** */ BACKEND
        try {
            fetch('write_expenses.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `amount=${amount}&category=${category}&description=${description}`
              })
              .then(response => response.text())
              .then(data => console.log(data))
              .catch(error => console.error(error));
        } catch (error) {
            console.error("Error submitting expense:", error);
        }
        //***************************************** */ 
        /*
        if (expenseCategories[category]) {
            expenseCategories[category] += amount;
        } else {
            expenseCategories[category] = amount; 
        }
        */
        // nauja tranzakcija
        const transactionList = document.getElementById('transactionList');
        const li = document.createElement('li');
        li.textContent = `€${amount.toFixed(2)} - ${description} (${category})`;
        transactionList.appendChild(li);

        // updatas
       // let currentExpense = parseFloat(document.getElementById("expenseValue").innerText.replace('$', ''));
        //currentExpense += amount;
        //document.getElementById("expenseValue").innerText = '€' + currentExpense.toFixed(2);

        // dar vienas
        updateBalance();
        update_all();
        update_category(expenseCategories);
        document.getElementById('expenseAmount').value = '';
        document.getElementById('expenseDesc').value = '';
        document.getElementById('expenseModal').style.display = 'none';

        // charto upadatas
        updateExpenseChart();
    } else {
        alert("Please enter a valid amount, description, and category.");
    }
}

function update_category(array) {
    for (var category in array) {
        var value = array[category];
        //console.log(category + ': ' + value);
        $.ajax({
            type: 'POST',
            url: 'update_category.php',
            data: { category: category, value: value },
            dataType: 'json',
            async: false,
            success: function(data) {
                //console.log('Received response:', data);
                var category = data.category;
                var value_new = data.value;
                array[category] = value_new.toString().replace(/\,/g, '');
                //console.log(category + ': ' + array[category]);
                
            }
        });
        console.log(category + ': ' + array[category]);
    }
    updateExpenseChart();
}

function update_all(){
    try {
        fetch('update_all.php')
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            const incomeSum = data.income_sum;
            const expenseSum = data.expense_sum;
            document.getElementById("incomeValue").innerText = `€${incomeSum.toFixed(2)}`;
            document.getElementById("expenseValue").innerText = `€${expenseSum.toFixed(2)}`;
            let balance = incomeSum - expenseSum;
            document.getElementById("balanceValue").innerText = '€' + balance.toFixed(2);
            //document.getElementById("balanceValue").innerText = '€' + balance.toFixed(2);
          })
          .catch(error => {
            console.error('Error:', error);
          });
      } catch (error) {
        console.error('Error submitting expense:', error);
      }
      
      updateBalance();
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


update_all();
update_category(expenseCategories);