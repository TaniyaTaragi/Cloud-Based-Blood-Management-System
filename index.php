<?php 

	//include header file
	include ('include/header.php');

?>


<div class="container-fluid header-img">
				<div class="row">
					<div class="col-md-6 offset-md-3">

						<div class="header">
							<h1 class="text-center">The Place Where Every Drop of Blood Saves a Life!</h1>
						<p class="text-center">Donate the blood to help the others.</p>
						</div>


						<h1 class="text-center">Search The Donors</h1>
						<hr class="white-bar text-center">

						<!-- FIXED FORM - Properly sends data to search.php -->
						<form action="search.php" method="get" class="form-inline text-center" style="padding: 40px 0px 0px 5px;">
							<div class="form-group text-center justify-content-center">
							
								<select style="width: 220px; height: 45px;" name="city" id="city" class="form-control demo-default" required>
									<option value="">-- Select City --</option>

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
									<option value="Gaya">Gaya</option>
									<option value="Muzaffarpur">Muzaffarpur</option>
									<option value="Nalanda">Nalanda</option>
									<option value="Patna">Patna</option>
									<option value="Purnia">Purnia</option>
									<option value="Rohtas">Rohtas</option>

									<optgroup title="Delhi" label="&raquo; Delhi"></optgroup>
									<option value="Central Delhi">Central Delhi</option>
									<option value="East Delhi">East Delhi</option>
									<option value="New Delhi">New Delhi</option>
									<option value="North Delhi">North Delhi</option>
									<option value="North East Delhi">North East Delhi</option>
									<option value="South Delhi">South Delhi</option>
									<option value="West Delhi">West Delhi</option>

									<optgroup title="Uttarakhand" label="&raquo; Uttarakhand"></optgroup>
									<option value="Almora">Almora</option>
									<option value="Bageshwar">Bageshwar</option>
									<option value="Chamoli">Chamoli</option>
									<option value="Champawat">Champawat</option>
									<option value="Dehradun">Dehradun</option>
									<option value="Haridwar">Haridwar</option>
									<option value="Nainital">Nainital</option>
									<option value="Pauri Garhwal">Pauri Garhwal</option>
									<option value="Pithoragarh">Pithoragarh</option>
									<option value="Rudraprayag">Rudraprayag</option>
									<option value="Tehri Garhwal">Tehri Garhwal</option>
									<option value="Udham Singh Nagar">Udham Singh Nagar</option>
									<option value="Haldwani">Haldwani</option>
									<option value="Uttarkashi">Uttarkashi</option>

									<optgroup title="West Bengal" label="&raquo; West Bengal"></optgroup>
									<option value="Alipurduar">Alipurduar</option>
									<option value="Bankura">Bankura</option>
									<option value="Birbhum">Birbhum</option>
									<option value="Cooch Behar">Cooch Behar</option>
									<option value="Darjeeling">Darjeeling</option>
									<option value="Hooghly">Hooghly</option>
									<option value="Howrah">Howrah</option>
									<option value="Jalpaiguri">Jalpaiguri</option>
									<option value="Kolkata">Kolkata</option>
									<option value="Murshidabad">Murshidabad</option>
									<option value="Nadia">Nadia</option>
									<option value="Purba Medinipur">Purba Medinipur</option>
									<option value="South 24 Parganas">South 24 Parganas</option>
									<option value="West Medinipur">West Medinipur</option>

									<!-- Add other states and districts similarly -->
								</select>
							</div>
							<div class="form-group center-aligned">
								<select name="blood_group" id="blood_group" style="padding: 0 20px; width: 220px; height: 45px;" class="form-control demo-default text-center margin10px" required>
									<option value="">-- Select Blood Group --</option>
									<option value="A+">A+</option>
									<option value="A-">A-</option>
									<option value="B+">B+</option>
									<option value="B-">B-</option>
									<option value="AB+">AB+</option>
									<option value="AB-">AB-</option>
									<option value="O+">O+</option>
									<option value="O-">O-</option>

								</select>
							</div>
							<div class="form-group center-aligned">
								<button type="submit" class="btn btn-lg btn-danger">Search</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- header ends -->

			<!-- donate section -->
			<div class="container-fluid red-background">
				<div class="row">
					<div class="col-md-12">
						<h1 class="text-center"  style="color: white; font-weight: 700;padding: 10px 0 0 0;">Donate The Blood</h1>
						<hr class="white-bar">
						<p class="text-center pera-text">
						Our motive is to create a compassionate and connected community where every individual understands the importance of blood donation. We strive to eliminate shortages by encouraging voluntary donations and making the process simple, efficient, and accessible to all.

						Through awareness, technology, and collaboration, we aim to ensure that every drop of blood reaches those who need it the most, ultimately saving lives and making a difference.
						</p>
						<a href="donate.php" class="btn btn-default btn-lg text-center center-aligned">Become a Life Saver!</a>
					</div>
				</div>
			</div>
			<!-- end doante section -->
			

			<div class="container">
				<div class="row">
    				<div class="col">
    					<div class="card">
     						<h3 class="text-center red">Our Vission</h3>
								<img src="img/binoculars.png" alt="Our Vission" class="img img-responsive" width="168" height="168">
								<p class="text-center">
									"To create a seamless, accessible, and reliable platform that bridges the gap between blood donors and those in need. Our mission is to ensure that no life is lost due to the unavailability of blood by leveraging technology to connect donors and recipients efficiently. We aim to foster a culture of voluntary blood donation, raising awareness about its importance and making the process as convenient and transparent as possible."
								</p>
						</div>
    				</div>
    				
    				<div class="col">
    					<div class="card">
      							<h3 class="text-center red">Our Goal</h3>
								<img src="img/target.png" alt="Our Vission" class="img img-responsive" width="168" height="168">
								<p class="text-center">
								Our goal is to create a user-friendly platform that connects blood donors with urgent need, raises awareness about voluntary blood donation, and eliminates misconceptions. We partner with hospitals, blood banks, and NGOs to build a network, maintain a real-time database, and encourage regular blood donations through reward systems and community engagement.
								</p>
							</div>
    				</div>
    			
    				<div class="col">
    					<div class="card">
      						<h3 class="text-center red">Our Mission</h3>
								<img src="img/goal.png" alt="Our Vission" class="img img-responsive" width="168" height="168">
								<p class="text-center">
								Our mission is to create a life-saving platform connecting blood donors with those in need, raising awareness about blood donation, and encouraging voluntary giving. We aim to provide a seamless, transparent, and efficient system through technology, collaborations with hospitals, blood banks, and NGOs.
							</p>
							</div>
   			 		</div>
 			</div>
 		</div>

			<!-- end aboutus section -->


<?php
//include footer file
include ('include/footer.php');
 ?>
