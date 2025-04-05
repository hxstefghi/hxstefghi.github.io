<?php
include('includes/header.php');
include('includes/navbar.php');

if(isset($_GET['t']))
{
    $tracking_no = $_GET['t'];

    $orderData = checkTrackingNoValid($tracking_no);
    if(mysqli_num_rows($orderData) < 0)
    {
        ?>
            <h4>Something went wrong</h4>
        <?php
        die();
    }
}
else 
{
    ?>
        <h4>Something went wrong</h4>
    <?php
    die();
}

$data = mysqli_fetch_array($orderData);
?>

<?= alertMessage2() ?>

<div class="container">
    <div class="py-5">
        <div class="row py-3">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header fs-4">
                        View Order
                        <a href="my-orders.php" class="btn btn-danger float-end">Back</a>
                    </div>
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
                                            <th>Size</th>
                                            <th>Sugar</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $userId = validate($_SESSION['loggedInUser']['userId']);

                                            $order_query = "SELECT o.id as oid, o.tracking_no, o.user_id, oi.*, oi.quantity as orderqty, p.* FROM orders o, order_items oi, 
                                            products p WHERE o.user_id='$userId' AND oi.order_id=o.id AND p.id=oi.prod_id AND o.tracking_no='$tracking_no' ";

                                            $order_query_run = mysqli_query($conn, $order_query);

                                            if(mysqli_num_rows($order_query_run) > 0)
                                            {
                                                foreach ($order_query_run as $item)
                                                {
                                                    ?>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <img src="<?= $item['image'];?>" alt="<?= $item['name'];?>" style="width: 50px; height: 50px;">
                                                                <?= $item['name'];?>
                                                            </td>
                                                            <td class="align-middle"><?= $item['size'] ? $item['size'] : 'N/A'; ?></td>
                                                            <td class="align-middle"><?= $item['sugar'] ? $item['sugar'] : 'N/A'; ?></td>
                                                            <td class="align-middle">₱<?= $item['price'];?></td>
                                                            <td class="align-middle"><?= $item['orderqty'];?></td>
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
                                    if($data['status'] == 0){
                                        echo "Pending";
                                    } else if ($data['status'] == 1)
                                    {
                                        echo "Completed";
                                    } else if ($data['status'] == 2)
                                    {
                                        echo "Cancelled";
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
