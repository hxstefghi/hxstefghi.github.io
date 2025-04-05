<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<?= alertMessage2() ?>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="receiptModalLabel">Order Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div id="receiptContent">
                    <!-- Receipt will be dynamically populated here -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn text-white" style="background: #312922;" onclick="printReceipt()">
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="py-5">
        <div class="row py-3" style="min-height: 50vh;">
            <div class="col-md-12 mb-3">
                <div class="card text-start">
                    <div class="card-body">
                        <h4 class="card-title">My Orders</h4>
                        <div class="table-responsive-md table-responsive-lg table-responsive-xl">
                        <table class="table text-center table-striped overflow-scroll">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0)">ID <i class="fa fa-sort"></i></th>
                                <th onclick="sortTable(1)">Tracking No. <i class="fa fa-sort"></i></th>
                                <th onclick="sortTable(2)">Price <i class="fa fa-sort"></i></th>
                                <th onclick="sortTable(3)">Date <i class="fa fa-sort"></i></th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="cart-items">
                            <?php

                                $orders = getOrders();
                                if(mysqli_num_rows($orders) > 0) {
                                    foreach($orders as $item) {
                            ?>
                                    <tr>
                                        <td><?= $item['id'];?></td>
                                        <td><?= $item['tracking_no'];?></td>
                                        <td>₱<?= number_format($item['total_price'], 2); ?></td>
                                        <td><?= $item['created_at'];?></td>
                                        <td>
                                            <a href="view-orders.php?t=<?= $item['tracking_no'];?>" style="background: #312922; border:none;" class="btn btn-primary mx-2 mb-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="#" onclick="showReceipt('<?= $item['tracking_no']; ?>')" class="btn btn-outline-danger mb-2">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="9">No orders yet</td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sortTable(columnIndex) {
    const table = document.querySelector("table tbody");
    const rows = Array.from(table.rows);
    const isAscending = table.getAttribute("data-sort-order") === "asc";
    const direction = isAscending ? 1 : -1;

    rows.sort((a, b) => {
        const aText = a.cells[columnIndex].innerText.trim();
        const bText = b.cells[columnIndex].innerText.trim();

        if (!isNaN(aText) && !isNaN(bText)) {
            return direction * (parseFloat(aText) - parseFloat(bText));
        } else {
            return direction * aText.localeCompare(bText);
        }
    });

    table.innerHTML = "";
    rows.forEach(row => table.appendChild(row));
    table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");

    // Update the sort icons
    updateSortIcons(columnIndex, isAscending);
}

function updateSortIcons(columnIndex, isAscending) {
    const headers = document.querySelectorAll("th i");
    headers.forEach((icon, index) => {
        if (index === columnIndex) {
            icon.className = isAscending ? "fa fa-sort-asc" : "fa fa-sort-desc";
        } else {
            icon.className = "fa fa-sort";
        }
    });
}

function showReceipt(trackingNo) {
    fetch('fetch-receipt.php?t=' + trackingNo)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const receipt = `
                    <div class="container">
                        <!-- Company Details -->
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Cup of Faith</h4>
                            <p class="text-muted">
                                659 St. Joseph Avenue Brgy 186, Caloocan, Philippines<br>
                                +63 992-367-7119 | cupoffaith.mktg@gmail.com
                            </p>
                        </div>

                        <!-- Order Details -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Order Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Date:</strong> ${data.order.created_at}</p>
                                    <p class="mb-1"><strong>Receipt #:</strong> ${data.order.tracking_no}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Customer:</strong> ${data.order.name}</p>
                                    <p class="mb-1"><strong>Delivery Address:</strong> ${data.order.address}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Itemized List -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.order.products.map(product => `
                                        <tr>
                                            <td>${product.name}</td>
                                            <td>${product.quantity}</td>
                                            <td>₱${parseFloat(product.price).toFixed(2)}</td>
                                            <td>₱${(product.quantity * product.price).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="row justify-content-end">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span>₱${parseFloat(data.order.total_price).toFixed(2)}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="text-muted">Thank you for your order! We hope you enjoy your drinks.</p>
                        </div>
                    </div>
                `;

                // Inject receipt into modal body
                document.getElementById('receiptContent').innerHTML = receipt;

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
                modal.show();
            } else {
                alert('Failed to fetch receipt details.');
            }
        })
        .catch(err => console.error('Error fetching receipt:', err));
}

function printReceipt() {
    const receiptContent = document.getElementById('receiptContent').innerHTML;

    // Open a new window for the print page
    const printWindow = window.open('', '', 'width=800,height=600');

    // Write the receipt content and styles into the new window
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Receipt</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    margin: 20px;
                }
                .receipt {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background: #fdfdfd;
                }
                .receipt-header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .receipt-header img {
                    max-width: 80px;
                }
                .receipt-header h4 {
                    font-size: 1.5rem;
                    margin: 10px 0;
                }
                .receipt-header p {
                    margin: 0;
                    color: #555;
                }
                .receipt-details p {
                    margin: 5px 0;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .table th, .table td {
                    border: 1px solid #ddd;
                    text-align: left;
                    padding: 8px;
                }
                .table th {
                    background-color: #f5f5f5;
                }
                .totals {
                    text-align: right;
                    margin-top: 10px;
                }
                .totals p {
                    font-size: 16px;
                    margin: 5px 0;
                }
                .receipt-footer {
                    text-align: center;
                    margin-top: 20px;
                    color: #555;
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                ${receiptContent}
            </div>
        </body>
        </html>
    `);

    printWindow.document.close(); // Close the document to render the content
    printWindow.print(); // Open the print dialog
}
</script>

<?php
include('includes/footer.php');
?>