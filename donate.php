<?php 
// include header
include ('include/header.php');

if (isset($_POST['submit'])) {
    if (isset($_POST['term']) && $_POST['term'] === 'true') {

        // Validate Full Name
        if (isset($_POST['name'])&& !empty($_POST['name'])) {
                         if (preg_match('/^[A-Za-z\s]+$/', $_POST['name'])) {
                         $name = $_POST['name'];
                     } else {
                      $nameError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Only letters and spaces allowed in name.</strong>
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span></button></div>';
                     }
        } else {
            $nameError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fill the name field.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate Gender
        if (isset($_POST['gender'])&&!empty($_POST['gender'])) {
            $gender = $_POST['gender'];
        } else {
            $genderError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please select your gender.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate DOB
        if (isset($_POST['day'])&&!empty($_POST['day'])) {
            $day = $_POST['day'];
        } else {
            $dayError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please select day.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        if (isset($_POST['month'])&&!empty($_POST['month'])) {
            $month = $_POST['month'];
        } else {
            $monthError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please select month.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        if (isset($_POST['year'])&&!empty($_POST['year'])) {
            $year = $_POST['year'];
        } else {
            $yearError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please select year.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate Blood Group
        if (isset($_POST['blood_group'])&&!empty($_POST['blood_group'])) {
            $blood_group = $_POST['blood_group'];
        } else {
            $blood_groupError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please select your blood group.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate City
        if (isset($_POST['city'])&&!empty($_POST['city'])) {
            if (preg_match("/^[A-Za-z\s]+$/", $_POST['city'])) {
                $city = $_POST['city'];
            } else {
                $cityError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Only letters and spaces allowed in city.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button></div>';
            }
        } else {
            $cityError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fill the city field.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate Contact No
        if (isset($_POST['contact_no'])&&!empty($_POST['contact_no'])) {
            if (preg_match("/^\d{10}$/", $_POST['contact_no'])) {
                $contact = $_POST['contact_no'];
            } else {
                $contactError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Contact number must be 10 digits.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button></div>';
            }
        } else {
            $contactError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fill the contact number field.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }

        // Validate Password
        if (isset($_POST['password']) && !empty($_POST['password'])&&isset($_POST['c_password']) && !empty($_POST['c_password'])) {
            if (strlen($_POST['password']) >= 6) {
                if ($_POST['password'] == $_POST['c_password']) {
                    $password = $_POST['password'];
                } else {
                    $passwordError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Passwords do not match.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button></div>';
                }
            } else {
                $passwordError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Password must be at least 6 characters long.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button></div>';
            }
        } else {
            $passwordError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fill both password fields.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        }


// Validate Email
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';
    if (preg_match($pattern, $_POST['email'])) {
        $check_email = $_POST['email'];
        $sql = "SELECT email FROM donor WHERE email='$check_email'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) > 0) {
            $emailError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Email already exists.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>';
        } else {
            $email = $check_email;
        }
    } else {
        $emailError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Only valid email addresses are allowed.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>';
    }
} else {
    $emailError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fill the email field.</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button></div>';
}

// Insert into database
// Insert into database
if (isset($name) && isset($blood_group) && isset($gender) && isset($day) && isset($month) && isset($year) && isset($email) && isset($contact) && isset($city) && isset($password)) {

    $DonorDOB = $year . "-" . $month . "-" . $day;
 

    // ----------------------password encryption--------------------------
    $password= md5($password);
  


    // Note: no id in INSERT because it's auto-increment
    $sql = "INSERT INTO donor (
        name, gender, email, city, dob, contact_no, save_life_date, password, blood_group
    ) VALUES (
        '$name', '$gender', '$email', '$city', '$DonorDOB', '$contact', '0', '$password','$blood_group'
    )";

    if (mysqli_query($connection, $sql)) {
       header('Location: signin.php');
       
    } else {
        $submitError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data not inserted. Try again.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>';
    }
}




    } else {
        $termError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please agree to our terms and conditions.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>';
    }
    


}
?>


<style>
	.size{
		min-height: 0px;
		padding: 60px 0 40px 0;
		
	}
	.form-container{
		background-color: white;
		border: .5px solid #eee;
		border-radius: 5px;
		padding: 20px 10px 20px 30px;
		-webkit-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
-moz-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
	}
	.form-group{
		text-align: left;
	}
	h1{
		color: white;
	}
	h3{
		color: #e74c3c;
		text-align: center;
	}
	.red-bar{
		width: 25%;
	}
</style>
<div class="container-fluid red-background size">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h1 class="text-center">Donate</h1>
			<hr class="white-bar">
		</div>
	</div>
</div>
<div class="container size">
	<div class="row">
		<div class="col-md-6 offset-md-3 form-container">
					<h3>SignUp</h3>
					<hr class="red-bar">
					<?php if(isset($termError))echo $termError;
                            if(isset($submitSuccess))echo $submitSuccess;
                            if(isset($submitError))echo $submitError;
                    ?>
					
          <!-- Error Messages -->

				<form class="form-group" action="" method="post">
					<div class="form-group">
						<label for="fullname">Full Name</label>
						<input type="text" name="name" id="fullname" placeholder="Full Name" required pattern="[A-Za-z/\s]+" title="Only lower and upper case and space" class="form-control" value="<?php if(isset($name))echo $name;?>">
					    <?php if(isset($nameError))echo $nameError;?>
                    </div><!--full name-->
					<div class="form-group">
              <label for="name">Blood Group</label><br>
              <select class="form-control demo-default" id="blood_group" name="blood_group" required>
                <option value="">---Select Your Blood Group---</option>
                <?php if(isset($blood_group)) echo '<option selected="" value="'.$blood_group.'">'.$blood_group.'</option>'; ?>
                <option value="A-">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
              <?php if(isset($blood_groupError)) echo $blood_groupError; ?>
            </div><!--End form-group-->
            
					<div class="form-group">
                            <label for="gender">Gender</label><br>
				              		Male<input type="radio" name="gender" id="gender" value="Male" style="margin-left:10px; margin-right:10px;" checked>
				              		Female<input type="radio" name="gender" id="gender" value="Female" style="margin-left:10px;"<?php if(isset($gender)) {if($gender=="Female")echo 'checked';} ?>>
				     <?php if(isset($genderError))echo $genderError;?>
                                </div><!--gender-->
                   
				    <div class="form-inline">
              <label for="name">Date of Birth</label><br>
              <select class="form-control demo-default" id="date" name="day" style="margin-bottom:10px;" required>
                <option value="">---Date---</option>
                  <?php if(isset($day)) echo '<option selected="" value="'.$day.'">'.$day.'</option>'; ?>
                <option value="01" >01</option><option value="02" >02</option><option value="03" >03</option><option value="04" >04</option><option value="05" >05</option><option value="06" >06</option><option value="07" >07</option> <option value="08" >08</option><option value="09" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option>
              </select>
              <select class="form-control demo-default" name="month" id="month" style="margin-bottom:10px;" required>
                <option value="">---Month---</option>
                 <?php if(isset($month)) echo '<option selected="" value="'.$month.'">'.$month.'</option>'; ?>
                <option value="01" >January</option><option value="02" >February</option><option value="03" >March</option><option value="04" >April</option><option value="05" >May</option><option value="06" >June</option><option value="07" >July</option><option value="08" >August</option><option value="09" >September</option><option value="10" >October</option><option value="11" >November</option><option value="12" >December</option>
              </select>
              <select class="form-control demo-default" id="year" name="year" style="margin-bottom:10px;" required>
                <option value="">---Year---</option>
                <?php if(isset($year)) echo '<option selected="" value="'.$year.'">'.$year.'</option>'; ?>
				<option value="1980">1980</option>
<option value="1981">1981</option>
<option value="1982">1982</option>
<option value="1983">1983</option>
<option value="1984">1984</option>
<option value="1985">1985</option>
<option value="1986">1986</option>
<option value="1987">1987</option>
<option value="1988">1988</option>
<option value="1989">1989</option>
<option value="1990">1990</option>
<option value="1991">1991</option>
<option value="1992">1992</option>
<option value="1993">1993</option>
<option value="1994">1994</option>
<option value="1995">1995</option>
<option value="1996">1996</option>
<option value="1997">1997</option>
<option value="1998">1998</option>
<option value="1999">1999</option>
<option value="2000">2000</option>
<option value="2001">2001</option>
<option value="2002">2002</option>
<option value="2003">2003</option>
<option value="2004">2004</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
<option value="2013">2013</option>
<option value="2014">2014</option>
<option value="2015">2015</option>
<option value="2016">2016</option>
<option value="2017">2017</option>
<option value="2018">2018</option>
<option value="2019">2019</option>
<option value="2020">2020</option>
<option value="2021">2021</option>
<option value="2022">2022</option>
<option value="2023">2023</option>
<option value="2024">2024</option>
<option value="2025">2025</option>

              </select>
             
             </div><!--End form-group-->
              <?php if(isset($dayError)) echo $dayError; ?>
             <?php if(isset($monthError)) echo $monthError; ?>
             <?php if(isset($yearError)) echo $yearError; ?>
             
				    <div class="form-group">
						<label for="fullname">Email</label>
						<input type="text" name="email" id="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Please write correct email" class="form-control"value="<?php if(isset($email))echo $email;?>">
					</div>
                    <?php if(isset($emailError)) echo $emailError; ?>
					<div class="form-group">
              <label for="contact_no">Contact No</label>
              <input type="text" name="contact_no" placeholder="+91 ********" class="form-control" required pattern="^\d{10}$" title="10 numeric characters only" maxlength="10"value="<?php if(isset($contact))echo $contact;?>">
              <?php if(isset($contactError)) echo $contactError; ?>
            </div><!--End form-group-->
					<div class="form-group">
              <label for="city">City</label>
              <select name="city" id="city" class="form-control demo-default" required>
             <?php if(isset($city)) echo '<option selected="" value="'.$city.'">'.$city.'</option>'; ?>
               
	 <option value="">-- Select --</option>
  
  <optgroup title="Andhra Pradesh" label="&raquo; Andhra Pradesh"></optgroup>
<option value="Anantapur">Anantapur</option>
<option value="Chittoor">Chittoor</option>
<option value="East Godavari">East Godavari</option>
<option value="Guntur">Guntur</option>
<option value="Krishna">Krishna</option>
<option value="Kurnool">Kurnool</option>
<option value="Nellore">Nellore</option>
<option value="Prakasam">Prakasam</option>
<option value="Srikakulam">Srikakulam</option>
<option value="Visakhapatnam">Visakhapatnam</option>
<option value="Vizianagaram">Vizianagaram</option>
<option value="West Godavari">West Godavari</option>

<optgroup title="Arunachal Pradesh" label="&raquo; Arunachal Pradesh"></optgroup>
<option value="Tawang">Tawang</option>
<option value="West Kameng">West Kameng</option>
<option value="East Kameng">East Kameng</option>
<option value="Papum Pare">Papum Pare</option>
<option value="Kurung Kumey">Kurung Kumey</option>
<option value="Kra Daadi">Kra Daadi</option>
<option value="Lower Subansiri">Lower Subansiri</option>
<option value="Upper Subansiri">Upper Subansiri</option>

<optgroup title="Assam" label="&raquo; Assam"></optgroup>
<option value="Baksa">Baksa</option>
<option value="Barpeta">Barpeta</option>
<option value="Bongaigaon">Bongaigaon</option>
<option value="Cachar">Cachar</option>
<option value="Charaideo">Charaideo</option>
<option value="Darrang">Darrang</option>
<option value="Dhemaji">Dhemaji</option>
<option value="Dhubri">Dhubri</option>
<option value="Dibrugarh">Dibrugarh</option>
<option value="Goalpara">Goalpara</option>
<option value="Golaghat">Golaghat</option>
<option value="Hailakandi">Hailakandi</option>
<option value="Jorhat">Jorhat</option>
<option value="Kamrup">Kamrup</option>
<option value="Karbi Anglong">Karbi Anglong</option>
<option value="Kokrajhar">Kokrajhar</option>

<optgroup title="Bihar" label="&raquo; Bihar"></optgroup>
<option value="Araria">Araria</option>
<option value="Arwal">Arwal</option>
<option value="Aurangabad">Aurangabad</option>
<option value="Banka">Banka</option>
<option value="Begusarai">Begusarai</option>
<option value="Bhagalpur">Bhagalpur</option>
<option value="Bhojpur">Bhojpur</option>
<option value="Darbhanga">Darbhanga</option>
<option value="Gaya">Gaya</option><option value="Muzaffarpur">Muzaffarpur</option><option value="Nalanda">Nalanda</option><option value="Patna">Patna</option><option value="Purnia">Purnia</option><option value="Rohtas">Rohtas</option><optgroup title="Delhi" label="&raquo; Delhi"></optgroup><option value="Central Delhi">Central Delhi</option><option value="East Delhi">East Delhi</option><option value="New Delhi">New Delhi</option><option value="North Delhi">North Delhi</option><option value="North East Delhi">North East Delhi</option><option value="South Delhi">South Delhi</option><option value="West Delhi">West Delhi</option>
<optgroup title="Uttarakhand" label="&raquo; Uttarakhand"></optgroup><option value="Almora">Almora</option><option value="Bageshwar">Bageshwar</option><option value="Chamoli">Chamoli</option><option value="Champawat">Champawat</option><option value="Dehradun">Dehradun</option><option value="Haridwar">Haridwar</option><option value="Nainital">Nainital</option><option value="Pauri Garhwal">Pauri Garhwal</option><option value="Pithoragarh">Pithoragarh</option><option value="Rudraprayag">Rudraprayag</option>
<option value="Tehri Garhwal">Tehri Garhwal</option><option value="Udham Singh Nagar">Udham Singh Nagar</option><option value="Haldwani">Haldwani</option><option value="Uttarkashi">Uttarkashi</option>
<optgroup title="West Bengal" label="&raquo; West Bengal"></optgroup>
<option value="Alipurduar">Alipurduar</option><option value="Bankura">Bankura</option><option value="Birbhum">Birbhum</option><option value="Cooch Behar">Cooch Behar</option><option value="Darjeeling">Darjeeling</option><option value="Hooghly">Hooghly</option><option value="Howrah">Howrah</option><option value="Jalpaiguri">Jalpaiguri</option><option value="Kolkata">Kolkata</option><option value="Murshidabad">Murshidabad</option><option value="Nadia">Nadia</option><option value="Purba Medinipur">Purba Medinipur</option><option value="South 24 Parganas">South 24 Parganas</option><option value="West Medinipur">West Medinipur</option>
</select>
 <?php if(isset($cityError)) echo $cityError; ?>
            </div><!--city end-->
           

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" value="" placeholder="Password" class="form-control" required pattern=".{6,}">
            </div><!--End form-group-->
            
            <div class="form-group">
              <label for="password">Confirm Password</label>
              <input type="password" name="c_password" value="" placeholder="Confirm Password" class="form-control" required pattern=".{6,}">
            <?php if(isset($passwordError)) echo $passwordError; ?>
            </div><!--End form-group-->
            
            <div class="form-inline">
              <input type="checkbox" checked="" name="term" value="true" required style="margin-left:10px;">
              <span style="margin-left:10px;"><b>I am agree to donate my blood and show my Name, Contact Nos. and E-Mail in Blood donors List</b></span>
            </div><!--End form-group-->
			
					<div class="form-group">
						<button id="submit" name="submit" type="submit" class="btn btn-lg btn-danger center-aligned" style="margin-top: 20px;">SignUp</button>
					</div>
				</form>
		</div>
	</div>
</div>

<?php 
  //include footer file
  include ('include/footer.php');
?>