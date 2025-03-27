<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Update Profile
if (isset($_POST['ChangeProfile'])) {
    $staff_id = $_SESSION['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $Qry = "UPDATE rpos_staff SET staff_name =?, staff_email =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($Qry);
    //bind paramaters
    $rc = $postStmt->bind_param('ssi', $staff_name, $staff_email, $staff_id);
    $postStmt->execute();
    //declare a variable which will be passed to alert function
    if ($postStmt) {
        $success = "Account Updated" && header("refresh:1; url=dashboard.php");
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
if (isset($_POST['changePassword'])) {
    //Change Password
    $error = 0;
    if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
        $old_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['old_password']))));
    } else {
        $error = 1;
        $err = "Old Password Cannot Be Empty";
    }
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }
    if (!$error) {
        $staff_id = $_SESSION['staff_id'];
        $sql = "SELECT * FROM rpos_staff WHERE staff_id = '$staff_id'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($old_password != $row['staff_password']) {
                $err =  "Please Enter Correct Old Password";
            } elseif ($new_password != $confirm_password) {
                $err = "Confirmation Password Does Not Match";
            } else {
                $new_password  = sha1(md5($_POST['new_password']));
                $query = "UPDATE rpos_staff SET staff_password =? WHERE staff_id =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('si', $new_password, $staff_id);
                $stmt->execute();
                if ($stmt) {
                    $success = "Password Changed" && header("refresh:1; url=dashboard.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
    }
}
require_once('partials/_head.php');
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .form-control {
        border-radius: 8px;
        padding: 12px;
    }
    .btn-success {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }
    .btn-success:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: scale(1.05);
    }
    .header {
        position: relative;
        height: 600px;
        background: url('../admin/assets/img/theme/restro00.jpg') no-repeat center center;
        background-size: cover;
        border-radius: 10px;
    }
    .mask {
        background: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
    }
    .profile-image img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <div class="profile-image">
                    <img src="../admin/assets/img/theme/user-a-min.png" alt="Profile Picture">
                </div>
                <h3 class="mt-3">Ronalyn Perez</h3>
                <p class="text-muted">ronalynperez033@gmail.com</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4">
                <h4>Update Profile</h4>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="staff_name" class="form-control" placeholder="Enter your full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="staff_email" class="form-control" placeholder="Enter your email">
                    </div>
                    <button type="submit" name="ChangeProfile" class="btn btn-success w-100">Update Profile</button>
                </form>
                <hr>
                <h4>Change Password</h4>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" name="old_password" class="form-control" placeholder="Enter old password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                    </div>
                    <button type="submit" name="changePassword" class="btn btn-success w-100">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
