<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container" style="min-height: 50vh;">
    <h1 class="text-center mb-5 mt-5" style="font-size: 2.5rem; font-weight: bold; color: #333;">Track Your Order</h1>
    <div class="row">
        <div class="col-md-12">
            <form action="" method="post" id="trackOrderForm">
                <div class="form-group mb-3">
                    <label for="tracking_no" class="mb-2">Enter your tracking number:</label>
                    <input type="text" class="form-control" id="tracking_no" name="tracking_no" placeholder="Tracking number" required>
                    <input type="hidden" id="qr_code_scanned" name="qr_code_scanned" value="0">
                </div>
                <button type="submit" name="track_order" class="btn mb-4 text-white" style="background-color: #312922;">Track Order</button>
            </form>
            <div class="form-group mb-3">
                <label for="qr_code" class="mb-2">Or scan your QR code:</label>
                <video id="preview" style="width: 100%; height: 280px;"></video> <!-- Adjusted size -->
            </div>
            <?php
            if (isset($_POST['track_order']) || isset($_POST['qr_code_scanned'])) {
                $tracking_no = validate($_POST['tracking_no']);
                $order_query = "SELECT * FROM orders WHERE tracking_no='$tracking_no'";
                $order_query_run = mysqli_query($conn, $order_query);
                if (mysqli_num_rows($order_query_run) > 0) {
                    $data = mysqli_fetch_array($order_query_run);
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Delivery Details</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Name</label>
                                            <div class="border p-1">
                                                <?= $data['name']; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Email</label>
                                            <div class="border p-1">
                                                <?= $data['email']; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Phone</label>
                                            <div class="border p-1">
                                                <?= $data['phone']; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Tracking No.</label>
                                            <div class="border p-1">
                                                <?= $data['tracking_no']; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Address</label>
                                            <div class="border p-1">
                                                <?= $data['address']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Order Details</h5>
                                    <hr>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $order_items_query = "SELECT * FROM order_items WHERE order_id='$data[id]'";
                                            $order_items_query_run = mysqli_query($conn, $order_items_query);
                                            if (mysqli_num_rows($order_items_query_run) > 0) {
                                                foreach ($order_items_query_run as $item) {
                                                    $product_query = "SELECT * FROM products WHERE id='$item[prod_id]'";
                                                    $product_query_run = mysqli_query($conn, $product_query);
                                                    $product_data = mysqli_fetch_array($product_query_run);
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <img src="<?= $product_data['image']; ?>" alt="<?= $product_data['name']; ?>" style="width: 50px; height: 50px;">
                                                            <?= $product_data['name']; ?>
                                                        </td>
                                                        <td class="align-middle">₱<?= number_format($item['price'], 2); ?></td>
                                                        <td class="align-middle"><?= $item['quantity']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <h5>Total Price: <span class="float-end fw-bold">₱<?= number_format($data['total_price'], 2); ?></span></h5>
                                    <hr>
                                    <label class="fw-bold">Payment Mode</label>
                                    <div class="border p-1 mb-2">
                                        <?= $data['payment_mode']; ?>
                                    </div>
                                    <label class="fw-bold">Status</label>
                                    <div class="border p-1 mb-2">
                                        <?php
                                        if ($data['status'] == 0) {
                                            echo "Pending";
                                        } else if ($data['status'] == 1) {
                                            echo "Completed";
                                        } else if ($data['status'] == 2) {
                                            echo "Cancelled";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger mt-4">
                        <strong>Invalid tracking number!</strong>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
scanner.addListener('scan', function (content) {
    console.log(content);
    document.getElementById('tracking_no').value = content.split('\n')[1].split(': ')[1]; // Extract tracking number
    document.getElementById('qr_code_scanned').value = 1;
    document.getElementById('trackOrderForm').submit();
    scanner.stop(); // Stop the scanner after a successful scan
});
Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        scanner.start(cameras[0]);
    } else {
        console.error('No cameras found.');
    }
}).catch(function (e) {
    console.error(e);
});
</script>

<?php
include('includes/footer.php');
?>