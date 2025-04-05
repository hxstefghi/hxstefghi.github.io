<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 55vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 500px; width: 100%; background-color: #ffffff;">

        <div class="col-md-12 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Profile</h3>
            </div>
            <?= alertMessage2() ?>
            <?php


                $paramResult = checkParamId('id');
                if(!is_numeric($paramResult)){
                echo '<h5>'.$paramResult.'</h5>';
                return false;
                }

                $user = getById('users',checkParamId('id'));
                if($user['status'] == 200)
                {
                ?>

                <form action="user-code.php" method="POST">

                    <input type="hidden" name="userId" value="<?= $user['data']['id'] ?>">

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label" style="font-weight: 500; color: #555;">Username</label>
                        <input type="text" name="username" id="username" value="<?= $user['data']['username']?>" class="form-control form-control-md" disabled>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstname" class="form-label" style="font-weight: 500; color: #555;">Firstname</label>
                                <input type="text" name="firstname" id="firstname" value="<?= $user['data']['fname']?>" class="form-control form-control-md">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastname" class="form-label" style="font-weight: 500; color: #555;">Lastname</label>
                                <input type="text" name="lastname" id="lastname" value="<?= $user['data']['lname']?>" class="form-control form-control-md">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="font-weight: 500; color: #555;">Email</label>
                        <input type="text" name="email" id="email" value="<?= $user['data']['email']?>" class="form-control form-control-md">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label" style="font-weight: 500; color: #555;">Phone</label>
                        <input type="text" name="phone" id="phone" value="<?= $user['data']['phone']?>" class="form-control form-control-md">
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="my-profile.php?id=<?= $user['data']['id'];?>" class="btn btn-link" style="font-weight: 500; color: #312922;">Back</a>
                        <button type="submit" class="btn text-white" name="updateProfile" style="font-weight: 500; background-color: #312922;">Update</button>
                    </div>

                </form>

                    <?php
                }
                else 
                {
                  ?>
                    <td colspan="9">No Record Found</td>
                  <?php
                }

              ?>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>