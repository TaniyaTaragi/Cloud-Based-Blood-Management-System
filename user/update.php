<?php 
include 'include/header.php';

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    include 'include/sidebar.php';

    // Handle Profile Update - COMPLETE FIXED VERSION
    if (isset($_POST['update_profile'])) {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $blood_group = mysqli_real_escape_string($connection, $_POST['blood_group']);
        $gender = mysqli_real_escape_string($connection, $_POST['gender']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $contact_no = mysqli_real_escape_string($connection, $_POST['contact_no']);
        $city = mysqli_real_escape_string($connection, $_POST['city']);
        $location = mysqli_real_escape_string($connection, $_POST['location']);
        
        // FIXED: Proper location data capture
        $latitude = isset($_POST['latitude']) ? mysqli_real_escape_string($connection, $_POST['latitude']) : '';
        $longitude = isset($_POST['longitude']) ? mysqli_real_escape_string($connection, $_POST['longitude']) : '';
        
        $day = mysqli_real_escape_string($connection, $_POST['day']);
        $month = mysqli_real_escape_string($connection, $_POST['month']);
        $year = mysqli_real_escape_string($connection, $_POST['year']);
        
        $dob = $year . '-' . $month . '-' . $day;
        
        // FIXED: Complete SQL query with location fields
        $sql = "UPDATE donor SET 
            name = '$name', 
            blood_group = '$blood_group', 
            gender = '$gender', 
            email = '$email', 
            contact_no = '$contact_no', 
            city = '$city', 
            dob = '$dob',
            location = '$location',
            latitude = '$latitude',
            longitude = '$longitude'
            WHERE id = '{$_SESSION['user_id']}'";

        if(mysqli_query($connection, $sql)) {
            $_SESSION['name'] = $name;
            
            // Check if location was saved
            $location_status = '';
            if (!empty($latitude) && !empty($longitude)) {
                $location_status = ' Location data saved successfully!';
            }
            
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Profile updated successfully!$location_status',
                    icon: 'success',
                    confirmButtonColor: '#dc3545',
                    timer: 3000
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update profile. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            </script>";
        }
    }

    // Handle Password Update
    if (isset($_POST['update_password'])) {
        $old_password = md5($_POST['old_password']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password !== $confirm_password) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'New password and confirm password do not match!',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            </script>";
        } else {
            $check_sql = "SELECT password FROM donor WHERE id = '{$_SESSION['user_id']}' AND password = '$old_password'";
            $result = mysqli_query($connection, $check_sql);
            
            if(mysqli_num_rows($result) > 0) {
                $new_password_hash = md5($new_password);
                $update_sql = "UPDATE donor SET password = '$new_password_hash' WHERE id = '{$_SESSION['user_id']}'";
                
                if(mysqli_query($connection, $update_sql)) {
                    echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Password updated successfully!',
                            icon: 'success',
                            confirmButtonColor: '#dc3545'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Current password is incorrect!',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                </script>";
            }
        }
    }

    // Handle Account Deletion
    if (isset($_POST['delete_account'])) {
        $account_password = md5($_POST['account_password']);
        
        $check_sql = "SELECT password FROM donor WHERE id = '{$_SESSION['user_id']}' AND password = '$account_password'";
        $result = mysqli_query($connection, $check_sql);
        
        if(mysqli_num_rows($result) > 0) {
            $delete_sql = "DELETE FROM donor WHERE id = '{$_SESSION['user_id']}'";
            if(mysqli_query($connection, $delete_sql)) {
                session_destroy();
                echo "<script>
                    Swal.fire({
                        title: 'Account Deleted!',
                        text: 'Your account has been deleted successfully.',
                        icon: 'success',
                        confirmButtonColor: '#dc3545'
                    }).then(() => {
                        window.location.href = '../index.php';
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Password is incorrect!',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            </script>";
        }
    }

    // Handle Image Upload
    if (isset($_POST['upload_image']) && isset($_FILES['profile_pic'])) {
        $uploadDir = 'uploads/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = basename($_FILES['profile_pic']['name']);
        $fileSize = $_FILES['profile_pic']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedExts)) {
            if ($fileSize < 2 * 1024 * 1024) {
                $newFileName = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExt;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $_SESSION['profile_image'] = $destPath;
                    
                    $update_img_sql = "UPDATE donor SET profile_image = '$destPath' WHERE id = '{$_SESSION['user_id']}'";
                    mysqli_query($connection, $update_img_sql);
                    
                    echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Image uploaded successfully!',
                            icon: 'success',
                            confirmButtonColor: '#dc3545'
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to upload image.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'File too large. Maximum size is 2MB.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid file type. Only JPG, PNG, GIF allowed.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            </script>";
        }
    }

    // Fetch current user data
    $user_sql = "SELECT * FROM donor WHERE id = '{$_SESSION['user_id']}'";
    $user_result = mysqli_query($connection, $user_sql);
    $user_data = mysqli_fetch_assoc($user_result);
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
    }
    
    /* Sidebar Fixes - WHITE COLOR */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: #ffffff;
        z-index: 1000;
        transition: all 0.3s ease;
        overflow-y: auto;
        border-right: 2px solid #e9ecef;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar .nav-link {
        color: #2c3e50;
        padding: 15px 20px;
        display: block;
        text-decoration: none;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .sidebar .nav-link:hover {
        background: #dc3545;
        color: #fff;
        padding-left: 25px;
        border-left: 4px solid #c82333;
    }
    
    .sidebar .nav-link.active {
        background: #dc3545;
        color: #fff;
        border-left: 4px solid #c82333;
    }
    
    /* Main Content with Sidebar */
    .main-content {
        margin-left: 250px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }
    
    .container-fluid {
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #f5f7fa 100%);
        min-height: 100vh;
    }
    
    .main-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #dc3545;
    }
    
    .page-header p {
        font-size: 1.1rem;
        color: #6c757d;
        margin: 0;
    }
    
    .form-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .form-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .form-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 20px 25px;
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-content {
        padding: 30px;
    }
    
    .form-group {
        margin-bottom: 25px;
        text-align: left;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #fff;
        font-family: inherit;
        min-height: 45px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        transform: translateY(-1px);
    }
    
    .form-control:hover {
        border-color: #dc3545;
    }
    
    /* FIXED Form Row Layout */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: -10px;
        align-items: flex-start;
    }
    
    .form-row .form-group {
        padding: 0 10px;
        margin-bottom: 25px;
        min-width: 0;
    }
    
    .form-row .col-2 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .form-row .col-3 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        min-width: 120px;
    }
    
    .form-row .col-4 {
        flex: 0 0 25%;
        max-width: 25%;
        min-width: 100px;
    }
    
    .form-row .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    /* Blood Group and Gender Row Fix */
    .blood-gender-row {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        width: 100%;
    }
    
    .blood-gender-row .form-group {
        flex: 1;
        margin-bottom: 25px;
        min-width: 200px;
    }
    
    /* Date of Birth Row Fix */
    .dob-row {
        display: flex;
        gap: 15px;
        width: 100%;
    }
    
    .dob-row .form-group {
        flex: 1;
        min-width: 100px;
    }
    
    /* Live Location Box */
    .live-location-box {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
        border: 2px solid #28a745;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    
    .live-location-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #28a745, #20c997, #17a2b8);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .live-location-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .live-location-header h5 {
        color: #155724;
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .location-status {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        padding: 10px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 8px;
    }
    
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-indicator.active {
        background: #28a745;
    }
    
    .status-indicator.inactive {
        background: #6c757d;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .coordinates-display {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .coordinate-item {
        background: rgba(255, 255, 255, 0.9);
        padding: 12px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }
    
    .coordinate-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .coordinate-value {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        color: #155724;
        font-weight: 600;
        word-break: break-all;
    }
    
    .get-location-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-family: inherit;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .get-location-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }
    
    .get-location-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .location-accuracy {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: center;
        margin-top: 10px;
        font-style: italic;
    }
    
    /* Hidden inputs for coordinates */
    .hidden-coordinate {
        display: none;
    }
    
    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        position: relative;
        overflow: hidden;
        font-family: inherit;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn:hover::before {
        left: 100%;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        transform: translateY(-2px);
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -15px;
    }
    
    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 15px;
    }
    
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 15px;
    }
    
    #map {
        height: 350px;
        width: 100%;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .image-upload-area {
        border: 3px dashed #dc3545;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
        position: relative;
    }
    
    .image-upload-area:hover {
        border-color: #c82333;
        background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
        transform: scale(1.02);
    }
    
    .image-upload-area.dragover {
        border-color: #c82333;
        background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
        transform: scale(1.05);
    }
    
    .upload-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #dc3545;
    }
    
    .image-preview {
        position: relative;
        margin-bottom: 20px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .image-preview img {
        max-width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    
    .image-preview:hover img {
        transform: scale(1.05);
    }
    
    .remove-image {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        transition: all 0.3s ease;
    }
    
    .remove-image:hover {
        background: #c82333;
        transform: scale(1.1);
    }
    
    .location-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        font-size: 13px;
        color: #6c757d;
        border-left: 4px solid #dc3545;
    }
    
    .radio-group {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    
    .radio-item {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .radio-item input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #dc3545;
    }
    
    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #dc3545;
    }
    
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #dc3545;
        margin-top: 2px;
        flex-shrink: 0;
    }
    
    .current-location-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        font-family: inherit;
    }
    
    .current-location-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }
    
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #dc3545;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 10px auto;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .col-md-8, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .form-row .col-2,
        .form-row .col-3,
        .form-row .col-4,
        .form-row .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }
        
        .blood-gender-row {
            flex-direction: column;
            gap: 0;
        }
        
        .dob-row {
            flex-direction: column;
            gap: 0;
        }
        
        .coordinates-display {
            grid-template-columns: 1fr;
        }
        
        .radio-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .container-fluid {
            padding: 15px;
        }
        
        .form-content {
            padding: 20px;
        }
    }
    
    @media (max-width: 480px) {
        .form-row {
            margin: -5px;
        }
        
        .form-row .form-group {
            padding: 0 5px;
        }
        
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .form-header {
            padding: 15px 20px;
            font-size: 1.1rem;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="main-container">
            <div class="page-header">
                <h1>🩸 Update Your Profile</h1>
                <p>Keep your donor information up to date to help save more lives</p>
            </div>
            
            <div class="row">
                <!-- Left Column - Forms -->
                <div class="col-md-8">
                    <!-- Profile Update Form -->
                    <div class="form-section">
                        <h3 class="form-header">👤 Personal Information</h3>
                        <div class="form-content">
                            <!-- FIXED: Added proper form opening tag -->
                            <form action="" method="post">
                                
                                <!-- FIXED: Added Live Location Box -->
                                <div class="live-location-box">
                                    <div class="live-location-header">
                                        <h5>📍 Live Location (Recommended)</h5>
                                    </div>
                                    
                                    <div class="location-status">
                                        <span class="status-indicator <?php echo (!empty($user_data['latitude']) && !empty($user_data['longitude'])) ? 'active' : 'inactive'; ?>"></span>
                                        <span><?php echo (!empty($user_data['latitude']) && !empty($user_data['longitude'])) ? 'Location Saved' : 'Click button to capture your current location'; ?></span>
                                    </div>
                                    
                                    <div id="coordinates-display" class="coordinates-display" style="display: <?php echo (!empty($user_data['latitude']) && !empty($user_data['longitude'])) ? 'grid' : 'none'; ?>;">
                                        <div class="coordinate-item">
                                            <div class="coordinate-label">Latitude</div>
                                            <div class="coordinate-value" id="display-latitude"><?php echo !empty($user_data['latitude']) ? $user_data['latitude'] : 'Not captured'; ?></div>
                                        </div>
                                        <div class="coordinate-item">
                                            <div class="coordinate-label">Longitude</div>
                                            <div class="coordinate-value" id="display-longitude"><?php echo !empty($user_data['longitude']) ? $user_data['longitude'] : 'Not captured'; ?></div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="get-location-btn" id="live-location-btn" onclick="getLiveLocation()">
                                        <?php echo (!empty($user_data['latitude']) && !empty($user_data['longitude'])) ? '✅ Location Saved - Update Again' : '🎯 Get My Live Location'; ?>
                                    </button>
                                    
                                    <div class="location-accuracy">
                                        Live location helps blood requesters find you quickly and accurately
                                    </div>
                                    
                                    <!-- FIXED: Added proper hidden inputs for location data -->
                                    <input type="hidden" name="latitude" id="latitude" value="<?php echo isset($user_data['latitude']) ? htmlspecialchars($user_data['latitude']) : ''; ?>">
                                    <input type="hidden" name="longitude" id="longitude" value="<?php echo isset($user_data['longitude']) ? htmlspecialchars($user_data['longitude']) : ''; ?>">
                                </div>

                                <!-- Name Field -->
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name" placeholder="Enter your full name" class="form-control" required value="<?php echo isset($user_data['name']) ? htmlspecialchars($user_data['name']) : ''; ?>">
                                </div>

                                <!-- Blood Group and Gender Row - FIXED -->
                                <div class="blood-gender-row">
                                    <div class="form-group">
                                        <label for="blood_group">Blood Group</label>
                                        <select class="form-control" id="blood_group" name="blood_group" required>
                                            <option value="">Select Blood Group</option>
                                            <?php 
                                            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                            foreach($blood_groups as $bg) {
                                                $selected = (isset($user_data['blood_group']) && $user_data['blood_group'] == $bg) ? 'selected' : '';
                                                echo "<option value='$bg' $selected>$bg</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Gender</label>
                                        <div class="radio-group">
                                            <div class="radio-item">
                                                <input type="radio" name="gender" id="male" value="Male" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'Male') ? 'checked' : ''; ?>>
                                                <label for="male">Male</label>
                                            </div>
                                            <div class="radio-item">
                                                <input type="radio" name="gender" id="female" value="Female" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'Female') ? 'checked' : ''; ?>>
                                                <label for="female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date of Birth - FIXED -->
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <div class="dob-row">
                                        <div class="form-group">
                                            <select class="form-control" name="day" required>
                                                <option value="">Day</option>
                                                <?php 
                                                $current_day = isset($user_data['dob']) ? date('d', strtotime($user_data['dob'])) : '';
                                                for($i = 1; $i <= 31; $i++) {
                                                    $day_val = sprintf('%02d', $i);
                                                    $selected = ($current_day == $day_val) ? 'selected' : '';
                                                    echo "<option value='$day_val' $selected>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="month" required>
                                                <option value="">Month</option>
                                                <?php 
                                                $months = ['01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December'];
                                                $current_month = isset($user_data['dob']) ? date('m', strtotime($user_data['dob'])) : '';
                                                foreach($months as $val => $month) {
                                                    $selected = ($current_month == $val) ? 'selected' : '';
                                                    echo "<option value='$val' $selected>$month</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="year" required>
                                                <option value="">Year</option>
                                                <?php 
                                                $current_year = isset($user_data['dob']) ? date('Y', strtotime($user_data['dob'])) : '';
                                                for($i = 1950; $i <= 2010; $i++) {
                                                    $selected = ($current_year == $i) ? 'selected' : '';
                                                    echo "<option value='$i' $selected>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-2">
                                        <label for="email">Email Address</label>
                                        <input type="email" name="email" id="email" placeholder="your@email.com" class="form-control" value="<?php echo isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="contact_no">Contact Number</label>
                                        <input type="text" name="contact_no" placeholder="10-digit mobile number" class="form-control" required pattern="^\d{10}$" title="10 numeric characters only" maxlength="10" value="<?php echo isset($user_data['contact_no']) ? htmlspecialchars($user_data['contact_no']) : ''; ?>">
                                    </div>
                                </div>

                                <!-- City - FIXED -->
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-control" required>
                                        <option value="">Select Your City</option>
                                        <?php 
                                        $cities = ['Delhi', 'Mumbai', 'Kolkata', 'Chennai', 'Bangalore', 'Hyderabad', 'Haldwani', 'Pune', 'Ahmedabad', 'Surat', 'Jaipur', 'Lucknow', 'Kanpur', 'Nagpur', 'Indore', 'Thane', 'Bhopal', 'Visakhapatnam', 'Pimpri-Chinchwad', 'Patna', 'Vadodara', 'Ghaziabad', 'Ludhiana', 'Agra', 'Nashik', 'Faridabad', 'Meerut', 'Rajkot', 'Kalyan-Dombivali', 'Vasai-Virar', 'Varanasi'];
                                        $current_city = isset($user_data['city']) ? $user_data['city'] : '';
                                        foreach($cities as $city) {
                                            $selected = ($current_city == $city) ? 'selected' : '';
                                            echo "<option value='$city' $selected>$city</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <input type="hidden" name="location" id="location" value="<?php echo isset($user_data['location']) ? htmlspecialchars($user_data['location']) : ''; ?>">

                                <div class="checkbox-item">
                                    <input type="checkbox" checked name="term" value="true" required id="terms">
                                    <label for="terms"><strong>I agree to donate my blood and show my Name, Contact Number, and Email in the Blood Donors List</strong></label>
                                </div>

                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" name="update_profile" class="btn btn-primary" style="width: 100%;">
                                        💾 Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password Update Form -->
                    <div class="form-section">
                        <h3 class="form-header">🔒 Change Password</h3>
                        <div class="form-content">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="old_password">Current Password</label>
                                    <input type="password" required name="old_password" class="form-control" placeholder="Enter current password">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-2">
                                        <label for="new_password">New Password</label>
                                        <input type="password" required name="new_password" class="form-control" placeholder="Enter new password" pattern=".{6,}" title="Minimum 6 characters">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" required name="confirm_password" class="form-control" placeholder="Confirm new password" pattern=".{6,}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" name="update_password">
                                        🔐 Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account Form -->
                    <div class="form-section">
                        <h3 class="form-header">⚠️ Danger Zone</h3>
                        <div class="form-content">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="account_password">Enter Password to Delete Account</label>
                                    <input type="password" required name="account_password" class="form-control" placeholder="Enter your password">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger" type="submit" name="delete_account" onclick="return confirm('⚠️ Are you sure you want to delete your account? This action cannot be undone and you will lose all your donation history.')">
                                        🗑️ Delete Account Permanently
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Location and Image -->
                <div class="col-md-4">
                    <!-- Location Picker -->
                    <div class="form-section">
                        <h3 class="form-header">📍 Optional Location Picker</h3>
                        <div class="form-content">
                            <div id="map"></div>
                            <button type="button" class="current-location-btn" onclick="getCurrentLocation()">
                                🎯 Use Current Location (Optional)
                            </button>
                            <div class="loading-spinner" id="location-spinner"></div>
                            <div class="location-info">
                                <strong>📌 Optional Location Setting:</strong><br>
                                • This is optional - you can skip this<br>
                                • Use the Live Location box in Personal Info instead<br>
                                • This map is for additional location reference only<br>
                                • Your live location is more accurate for donors
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-section">
                        <h3 class="form-header">📷 Document Picture</h3>
                        <div class="form-content">
                            <?php if (isset($user_data['profile_image']) && !empty($user_data['profile_image']) && file_exists($user_data['profile_image'])): ?>
                                <div class="image-preview">
                                    <img src="<?php echo $user_data['profile_image']; ?>" alt="Profile Image" id="preview-image">
                                    <button type="button" class="remove-image" onclick="removeImage()">×</button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="image-upload-area" id="upload-area" onclick="document.getElementById('file-input').click()">
                                <div class="upload-icon">📷</div>
                                <h4>Drag & Drop Your Photo Here</h4>
                                <p>or click to browse files</p>
                                <small>JPG, PNG, GIF (Max 2MB)</small>
                            </div>
                            
                            <form action="" method="post" enctype="multipart/form-data" id="image-form">
                                <input type="file" name="profile_pic" accept="image/*" id="file-input" style="display: none;" onchange="previewImage(this)">
                                <button class="btn btn-primary" type="submit" name="upload_image" style="width: 100%; margin-top: 15px;">
                                    📤 Upload Photo
                                </button>
                            </form>
                            
                            <div class="location-info">
                                <strong>📸 Photo Guidelines:</strong><br>
                                • Use a clear, recent photo of yourself<br>
                                • Ensure good lighting and visibility<br>
                                • This helps blood requesters identify you<br>
                                • Keep it professional and appropriate
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Live Location and Maps -->
<script>
    let map, marker;
    
    // FIXED: Live Location Function
    function getLiveLocation() {
        const btn = document.getElementById('live-location-btn');
        const statusIndicator = document.querySelector('.status-indicator');
        const statusText = document.querySelector('.location-status span');
        const coordinatesDisplay = document.getElementById('coordinates-display');
        
        if (navigator.geolocation) {
            btn.disabled = true;
            btn.innerHTML = '🔄 Getting Live Location...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    // FIXED: Update hidden inputs with correct IDs
                    document.getElementById('latitude').value = latitude.toFixed(6);
                    document.getElementById('longitude').value = longitude.toFixed(6);
                    
                    // Update display
                    document.getElementById('display-latitude').textContent = latitude.toFixed(6);
                    document.getElementById('display-longitude').textContent = longitude.toFixed(6);
                    
                    // Show coordinates display
                    coordinatesDisplay.style.display = 'grid';
                    
                    // Update status
                    statusIndicator.className = 'status-indicator active';
                    statusText.textContent = 'Live Location Captured - Ready to Save!';
                    
                    // Reset button
                    btn.disabled = false;
                    btn.innerHTML = '✅ Location Captured - Update Again';
                    
                    // Show success message
                    Swal.fire({
                        title: 'Live Location Captured!',
                        text: `Location captured with ${Math.round(accuracy)}m accuracy. Now click "Update Profile" to save!`,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 4000,
                        showConfirmButton: true,
                        confirmButtonText: 'Got it!'
                    });
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '🎯 Get My Live Location';
                    
                    let errorMessage = 'Unable to get your live location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Please allow location access in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information is unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    
                    Swal.fire({
                        title: 'Location Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        } else {
            Swal.fire({
                title: 'Not Supported',
                text: 'Geolocation is not supported by this browser.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        }
    }
    
    // Optional Map Functions
    function initMap() {
        const defaultLocation = { lat: 28.6139, lng: 77.2090 };
        
        const savedLocation = document.getElementById('location').value;
        let initialLocation = defaultLocation;
        
        if (savedLocation && savedLocation.includes(',')) {
            const coords = savedLocation.split(',');
            initialLocation = {
                lat: parseFloat(coords[0].trim()),
                lng: parseFloat(coords[1].trim())
            };
        }
        
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialLocation,
            zoom: 12,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        marker = new google.maps.Marker({
            position: initialLocation,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            title: "Optional Location",
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#17a2b8">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            }
        });

        updateLocationInput(initialLocation);

        marker.addListener('dragend', function () {
            const pos = marker.getPosition();
            updateLocationInput({lat: pos.lat(), lng: pos.lng()});
            
            marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => marker.setAnimation(null), 750);
        });

        map.addListener('click', function (e) {
            marker.setPosition(e.latLng);
            updateLocationInput({lat: e.latLng.lat(), lng: e.latLng.lng()});
            
            marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => marker.setAnimation(null), 750);
        });
    }
    
    function updateLocationInput(location) {
        document.getElementById('location').value = `${location.lat.toFixed(6)}, ${location.lng.toFixed(6)}`;
    }
    
    function getCurrentLocation() {
        const spinner = document.getElementById('location-spinner');
        const button = document.querySelector('.current-location-btn');
        
        if (navigator.geolocation) {
            spinner.style.display = 'block';
            button.disabled = true;
            button.innerHTML = '🔄 Getting Location...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    map.setCenter(currentLocation);
                    map.setZoom(15);
                    marker.setPosition(currentLocation);
                    updateLocationInput(currentLocation);
                    
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => marker.setAnimation(null), 750);
                    
                    spinner.style.display = 'none';
                    button.disabled = false;
                    button.innerHTML = '🎯 Use Current Location (Optional)';
                    
                    Swal.fire({
                        title: 'Optional Location Set!',
                        text: 'Optional map location has been set.',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                },
                function(error) {
                    spinner.style.display = 'none';
                    button.disabled = false;
                    button.innerHTML = '🎯 Use Current Location (Optional)';
                    
                    Swal.fire({
                        title: 'Location Error',
                        text: 'Unable to get location for optional map.',
                        icon: 'warning',
                        confirmButtonColor: '#dc3545'
                    });
                }
            );
        }
    }
    
    // Image Upload Functions
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Invalid File Type',
                    text: 'Please select a JPG, PNG, or GIF image.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
                input.value = '';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    title: 'File Too Large',
                    text: 'Please select an image smaller than 2MB.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                let previewContainer = document.querySelector('.image-preview');
                if (!previewContainer) {
                    previewContainer = document.createElement('div');
                    previewContainer.className = 'image-preview';
                    previewContainer.innerHTML = `
                        <img src="/placeholder.svg" alt="Profile Image" id="preview-image">
                        <button type="button" class="remove-image" onclick="removeImage()">×</button>
                    `;
                    document.getElementById('upload-area').parentNode.insertBefore(previewContainer, document.getElementById('upload-area'));
                }
                
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('upload-area').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
    
    function removeImage() {
        const previewContainer = document.querySelector('.image-preview');
        if (previewContainer) {
            previewContainer.remove();
        }
        document.getElementById('file-input').value = '';
        document.getElementById('upload-area').style.display = 'block';
    }
    
    // Drag and Drop for Image Upload
    const uploadArea = document.getElementById('upload-area');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    uploadArea.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        uploadArea.classList.add('dragover');
    }
    
    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            document.getElementById('file-input').files = files;
            previewImage(document.getElementById('file-input'));
        }
    }
    
    // FIXED: Form Validation with location check
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Check if this is the profile update form
                if (form.querySelector('button[name="update_profile"]')) {
                    const latitude = document.getElementById('latitude').value;
                    const longitude = document.getElementById('longitude').value;
                    
                    // Show warning if no location is captured
                    if (!latitude || !longitude || latitude === '0' || longitude === '0') {
                        e.preventDefault();
                        Swal.fire({
                            title: 'No Location Captured',
                            text: 'Would you like to capture your live location first? This helps blood requesters find you.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Capture Location',
                            cancelButtonText: 'Continue Without Location'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                getLiveLocation();
                            } else {
                                // Submit form without location
                                form.submit();
                            }
                        });
                        return;
                    }
                }
                
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#dc3545';
                        field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    } else {
                        field.style.borderColor = '#28a745';
                        field.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                        icon: 'warning',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
    });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-vZc-uUNkjg8HLcCbkptTbfjHD3Zyq0&callback=initMap"></script>

<?php
} else {
    header('Location: ../index.php');
    exit();
}
include 'include/footer.php'; 
?>
