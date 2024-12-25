document.addEventListener('DOMContentLoaded', (event) => {
    const links = document.querySelectorAll('.navbar ul li a');

    links.forEach(link => {
        link.addEventListener('click', function() {
            links.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
// JavaScript to handle the charts and data updates
document.addEventListener('DOMContentLoaded', function() {
    fetch('getData.php')
        .then(response => response.json())
        .then(data => {
            // Memperbarui info cards
            document.getElementById('total-customers').textContent = data.totalCustomers;
            document.getElementById('total-orders').textContent = data.totalOrders;
            document.getElementById('total-expenses').textContent = `$${data.totalExpenses}`;
            document.getElementById('total-revenue').textContent = `$${data.totalRevenue}`;

            // Memperbarui order history
            let orderTable = document.querySelector('.order-history table');
            orderTable.innerHTML = `
                <tr>
                    <th>Tracking ID</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                </tr>
            `;
            data.orderHistory.forEach(order => {
                orderTable.innerHTML += `
                    <tr>
                        <td>${order.tracking_id}</td>
                        <td>${order.recipient}</td>
                        <td>${order.status}</td>
                        <td>${order.payment_method}</td>
                    </tr>
                `;
            });

            // Memperbarui charts
            var ctx = document.getElementById('sales-chart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sun'],
                    datasets: [{
                        label: 'Incomes',
                        borderColor: '#007bff',
                        data: [150, 200, 150, 100, 200, 250, 150, 200]
                    }, {
                        label: 'Expense',
                        borderColor: '#ffc107',
                        data: [100, 150, 100, 50, 100, 150, 100, 150]
                    }]
                },
                options: {}
            });

            var ctx2 = document.getElementById('earnings-chart').getContext('2d');
            var earningsChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Earning', 'Pending'],
                    datasets: [{
                        backgroundColor: ['#4caf50', '#f44336'],
                        data: [750, 200]
                    }]
                },
                options: {}
            });
        });
});
const infoCards = document.querySelectorAll('.info-card');
const modal = document.getElementById('viewModal');
const modalData = document.getElementById('modalData');

infoCards.forEach(card => {
    card.addEventListener('click', () => {
        const viewName = card.getAttribute('data-view');
        fetchViewData(viewName);
    });
});

function fetchViewData(viewName) {
    // Fetch data from PHP
    fetch(`fetch_view_data.php?view=${viewName}`)
        .then(response => response.text())
        .then(data => {
            modalData.innerHTML = data;
            modal.style.display = 'flex';
        })
        .catch(error => console.error('Error fetching data:', error));
}

function closeModal() {
    modal.style.display = 'none';
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
// Fetch data from PHP
fetch(`view.php?view=${viewName}`)
    .then(response => response.text())
    .then(data => {
        modalData.innerHTML = data;
        modal.style.display = 'flex';
    })
    .catch(error => console.error('Error fetching data:', error));

// Function to update total payment
function updateTotalPayment(totalPayment) {
    document.getElementById('totalPayment').textContent = 'Total Payment: ' + totalPayment;
}

// Fetch data from PHP using XMLHttpRequest
function fetchDataFromPHP() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'admin.php?view=IncomeDetails', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.totalPayment !== undefined) {
                updateTotalPayment(data.totalPayment);
            } else {
                console.error('Undefined total payment received.');
            }
        } else {
            console.error('Error fetching data. Status:', xhr.status);
        }
    };
    xhr.send();
}

// Call function to fetch and update data on page load
window.onload = fetchDataFromPHP;
