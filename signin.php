<?php
 // Required for using $_SESSION

include('include/header.php');

// ✅ Make sure this file exists and defines $connection


$emailError = $passwordError = $submitError = "";

if (isset($_POST['SignIn'])) {
    
    // Email Input Check
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
    } else {
        $emailError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fill the Email field.</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
    }

    // Password Input Check
    if (!empty($_POST['password'])) {
        $password = md5(trim($_POST['password'])); // ✅ Encrypt using MD5
    } else {
        $passwordError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fill the Password field.</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
    }

    // Login Query - only if both fields are filled
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM donor WHERE email='$email' AND password='$password'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['save_life_date'] = $row['save_life_date'];
            header('Location: user/index.php');
            
        } else {
            $submitError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Invalid Email or Password.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
        }
    }
}
?>

<style>
    .size {
        min-height: 0px;
        padding: 60px 0 60px 0;
    }
    h1 {
        color: white;
    }
    .form-group {
        text-align: left;
    }
    h3 {
        color: #e74c3c;
        text-align: center;
    }
    .red-bar {
        width: 25%;
    }
    .form-container {
        background-color: white;
        border: .5px solid #eee;
        border-radius: 5px;
        padding: 20px 10px 20px 30px;
        -webkit-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
        -moz-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
        box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
    }
</style>

<div class="container-fluid red-background size">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center">SignIn</h1>
            <hr class="white-bar">
        </div>
    </div>
</div>

<div class="container size">
    <div class="row">
        <div class="col-md-6 offset-md-3 form-container">
            <h3>SignIn</h3>
            <hr class="red-bar">
            <?php if (!empty($submitError)) echo $submitError; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Email" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>" required>
                    <?php if (!empty($emailError)) echo $emailError; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                    <?php if (!empty($passwordError)) echo $passwordError; ?>
                </div>

                <div class="form-group">
                    <button class="btn btn-danger btn-lg center-aligned" type="submit" name="SignIn">SignIn</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
