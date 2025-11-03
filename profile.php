<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - PROFILE</title>
</head>
<body class="bg-light">

    <?php 
        require('inc/header.php'); 

        if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
            redirect('index.php');
        }

        $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");

        if(mysqli_num_rows($u_exist)==0){
            redirect('index.php');
        }

        $u_fetch = mysqli_fetch_assoc($u_exist);
    ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">MY PROFILE</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary">></span>
                    <a href="profile.php" class="text-secondary text-decoration-none">PROFILE</a>
                </div>
            </div>

            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3 mb-3">
                            <img src="<?php echo USERS_IMG_PATH.$u_fetch['profile'] ?>" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-person-fill"></i> Name</h6>
                                    <p><?php echo $u_fetch['name'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-envelope"></i> Email</h6>
                                    <p><?php echo $u_fetch['email'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-telephone-fill"></i> Phone Number</h6>
                                    <p><?php echo $u_fetch['phonenum'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-cake-fill"></i> Date of Birth</h6>
                                    <p><?php echo $u_fetch['dob'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-geo-fill"></i> Pincode</h6>
                                    <p><?php echo $u_fetch['pincode'] ?></p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6 class="mb-1"><i class="bi bi-geo-alt-fill"></i> Address</h6>
                                    <p><?php echo $u_fetch['address'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require('inc/footer.php'); ?>

</body>
</html>
