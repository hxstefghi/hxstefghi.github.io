<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 55vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 800px; width: 100%; background-color: #ffffff;">
        <div class="col-md-12 p-4">
            <div class="text-center mb-5">
                <h3 class="fw-bold" style="color: #333;">Activity Log</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped text-center">
                    <thead class="table">
                        <tr>
                            <th>Activity</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $userId = validate($_SESSION['loggedInUser']['userId']);
                        $query = "SELECT * FROM activity_log WHERE user_id='$userId' ORDER BY timestamp DESC";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $formattedDate = date("F j, Y, g:i a", strtotime($row['timestamp']));
                                echo "<tr>
                                        <td>{$row['action']}</td>
                                        <td>{$formattedDate}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No activities found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>