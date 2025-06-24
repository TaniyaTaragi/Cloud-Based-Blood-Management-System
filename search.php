<?php 

	//include header file
	include ('include/header.php');

?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	.size{
		min-height: 0px;
		padding: 60px 0 40px 0;

	}
	.loader{
		display:none;
		width:69px;
		height:89px;
		position:absolute;
		top:25%;
		left:50%;
		padding:2px;
		z-index: 1;
	}
	.loader .fa{
		color: #e74c3c;
		font-size: 52px !important;
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
	span{
		display: block;
	}
	.name{
		color: #e74c3c;
		font-size: 22px;
		font-weight: 700;
	}
	.donors_data{
		background-color: white;
		border-radius: 5px;
		margin: 25px;
		-webkit-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		-moz-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		padding: 20px 10px 20px 30px;
		position: relative;
	}
	
	/* Location Button Styles */
	.location-btn {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		color: white;
		border: none;
		padding: 8px 15px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		margin-top: 10px;
		width: 100%;
		box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
	}
	
	.location-btn:hover {
		background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
		transform: translateY(-2px);
		box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
	}
	
	.location-btn:disabled {
		background: #6c757d;
		cursor: not-allowed;
		transform: none;
		box-shadow: none;
	}
	
	.no-location-btn {
		background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
		color: #212529;
	}
	
	.no-location-btn:hover {
		background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
		color: white;
	}
	
	/* Blood Bank Styles */
	.blood_bank_section {
		background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
		border-radius: 15px;
		margin: 40px 0;
		padding: 30px;
		border: 2px solid #28a745;
		box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
	}
	
	.blood_bank_header {
		text-align: center;
		margin-bottom: 30px;
	}
	
	.blood_bank_title {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		color: white;
		padding: 15px 30px;
		border-radius: 25px;
		display: inline-block;
		font-size: 20px;
		font-weight: 700;
		box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
		margin-bottom: 10px;
	}
	
	.blood_bank_subtitle {
		color: #28a745;
		font-size: 16px;
		font-weight: 600;
		margin: 0;
	}
	
	.blood_bank_data{
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
		border-radius: 12px;
		margin: 15px;
		border: 2px solid #28a745;
		box-shadow: 0 6px 20px rgba(40, 167, 69, 0.2);
		padding: 25px;
		position: relative;
		transition: all 0.3s ease;
	}
	
	.blood_bank_data:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
	}
	
	.blood-bank-badge {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		color: white;
		padding: 8px 15px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 700;
		position: absolute;
		top: -10px;
		right: 20px;
		box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
		z-index: 2;
	}
	
	.bank-name {
		color: #28a745;
		font-size: 22px;
		font-weight: 800;
		margin-bottom: 15px;
		padding-top: 10px;
	}
	
	.bank-info {
		display: flex;
		align-items: center;
		margin: 8px 0;
		font-size: 15px;
		color: #495057;
	}
	
	.bank-info-icon {
		width: 20px;
		margin-right: 10px;
		font-size: 16px;
		color: #28a745;
	}
	
	.blood-availability {
		background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
		padding: 15px;
		border-radius: 10px;
		margin-top: 15px;
		border-left: 4px solid #28a745;
	}
	
	.blood-availability-title {
		font-weight: 700;
		color: #28a745;
		margin-bottom: 10px;
		font-size: 14px;
	}
	
	.blood-type {
		display: inline-block;
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		color: white;
		padding: 5px 12px;
		border-radius: 15px;
		font-size: 12px;
		margin: 3px;
		font-weight: 700;
		box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
	}
	
	.blood-type-highlight {
		display: inline-block;
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
		color: white;
		padding: 5px 12px;
		border-radius: 15px;
		font-size: 12px;
		margin: 3px;
		font-weight: 700;
		box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
		animation: pulse 2s infinite;
	}
	
	@keyframes pulse {
		0% { box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4); }
		50% { box-shadow: 0 4px 15px rgba(220, 53, 69, 0.6); }
		100% { box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4); }
	}
	
	.no-banks-message {
		text-align: center;
		padding: 40px;
		background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
		border-radius: 15px;
		border: 2px solid #ffc107;
		margin: 20px;
	}
	
	.no-banks-icon {
		font-size: 48px;
		color: #ffc107;
		margin-bottom: 15px;
	}
	
	/* Status Indicators */
	.status-available {
		position: absolute;
		top: 10px;
		right: 10px;
		background: #28a745;
		color: white;
		padding: 4px 8px;
		border-radius: 12px;
		font-size: 10px;
		font-weight: 600;
	}
	
	.status-donated {
		position: absolute;
		top: 10px;
		right: 10px;
		background: #6c757d;
		color: white;
		padding: 4px 8px;
		border-radius: 12px;
		font-size: 10px;
		font-weight: 600;
	}
	
	/* Map Modal Styles */
	.map-modal {
		display: none;
		position: fixed;
		z-index: 9999;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,0.8);
		animation: fadeIn 0.3s ease;
	}
	
	@keyframes fadeIn {
		from { opacity: 0; }
		to { opacity: 1; }
	}
	
	.map-modal-content {
		background-color: #fefefe;
		margin: 2% auto;
		padding: 0;
		border-radius: 15px;
		width: 90%;
		max-width: 900px;
		height: 80vh;
		position: relative;
		overflow: hidden;
		box-shadow: 0 20px 60px rgba(0,0,0,0.3);
		animation: slideIn 0.3s ease;
	}
	
	@keyframes slideIn {
		from { transform: translateY(-50px); opacity: 0; }
		to { transform: translateY(0); opacity: 1; }
	}
	
	.map-header {
		color: white;
		padding: 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	
	.map-header.donor-header {
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
	}
	
	.map-header.bank-header {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
	}
	
	.map-header h3 {
		margin: 0;
		color: white;
		font-size: 1.3rem;
	}
	
	.close-btn {
		background: none;
		border: none;
		color: white;
		font-size: 28px;
		font-weight: bold;
		cursor: pointer;
		padding: 0;
		width: 35px;
		height: 35px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.3s ease;
	}
	
	.close-btn:hover {
		background: rgba(255,255,255,0.2);
		transform: rotate(90deg);
	}
	
	.location-info {
		padding: 15px 20px;
		background: #f8f9fa;
		border-bottom: 1px solid #dee2e6;
	}
	
	.location-info-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 10px;
		font-size: 14px;
	}
	
	.info-item {
		display: flex;
		align-items: center;
		gap: 8px;
	}
	
	.info-label {
		font-weight: 600;
		color: #495057;
	}
	
	.info-value {
		font-weight: 500;
	}
	
	.info-value.donor-value {
		color: #dc3545;
	}
	
	.info-value.bank-value {
		color: #28a745;
	}
	
	#location-map {
		width: 100%;
		height: calc(80vh - 140px);
		border: none;
	}
	
	.map-loading {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 200px;
		font-size: 18px;
		color: #6c757d;
	}
	
	.loading-spinner {
		width: 40px;
		height: 40px;
		border: 4px solid #f3f3f3;
		border-top: 4px solid #dc3545;
		border-radius: 50%;
		animation: spin 1s linear infinite;
		margin-right: 15px;
	}
	
	/* Search Form Centering */
	.search-form-container {
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 40px 0;
	}

	.search-form-wrapper {
		display: flex;
		flex-wrap: wrap;
		gap: 15px;
		align-items: center;
		justify-content: center;
	}

	.search-form-wrapper .form-group {
		margin: 0;
		text-align: center;
	}

	.search-btn {
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
		color: #dc3545;
		border: 2px solid #ffffff;
		padding: 10px 30px;
		border-radius: 25px;
		font-weight: 700;
		font-size: 16px;
		transition: all 0.3s ease;
		box-shadow: 0 4px 15px rgba(255,255,255,0.3);
		min-width: 120px;
	}

	.search-btn:hover {
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
		color: white;
		border-color: #dc3545;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(255,255,255,0.4);
	}

	.white-bar {
		background: white;
		height: 3px;
		border: none;
		margin: 20px auto;
		width: 100px;
	}
	
	/* Responsive Design */
	@media (max-width: 768px) {
		.map-modal-content {
			width: 95%;
			height: 85vh;
			margin: 5% auto;
		}
		
		.map-header {
			padding: 15px;
		}
		
		.map-header h3 {
			font-size: 1.1rem;
		}
		
		.location-info-grid {
			grid-template-columns: 1fr;
			gap: 8px;
		}
		
		#location-map {
			height: calc(85vh - 120px);
		}
		
		.blood_bank_data {
			margin: 10px 5px;
		}
		
		.blood_bank_title {
			font-size: 18px;
			padding: 12px 20px;
		}
		
		.blood-bank-search-form {
			flex-direction: column;
			gap: 10px;
		}
		
		.blood-bank-search-input {
			width: 100%;
			min-width: auto;
		}
		
		.blood-bank-search-title {
			font-size: 24px;
		}
		
		.search-form-wrapper {
			flex-direction: column;
			gap: 15px;
		}
		
		.search-form-wrapper .form-group select,
		.search-form-wrapper .form-group button {
			width: 100%;
			max-width: 280px;
		}
	}

	@media (max-width: 480px) {
		.search-form-container {
			padding: 20px 10px;
		}
		
		.search-form-wrapper .form-group select {
			width: 100%;
			font-size: 14px;
		}
	}
</style>

<div class="container-fluid red-background size">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h1 class="text-center">Search Donors</h1>
			<hr class="white-bar">
			<br>
			<div class="search-form-container">
				<div class="search-form-wrapper">
					<div class="form-group">
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
							<option value="Haldwani">Haldwani</option>
							<option value="Haridwar">Haridwar</option>
							<option value="Nainital">Nainital</option>
							<option value="Pauri Garhwal">Pauri Garhwal</option>
							<option value="Pithoragarh">Pithoragarh</option>
							<option value="Rudraprayag">Rudraprayag</option>
							<option value="Tehri Garhwal">Tehri Garhwal</option>
							<option value="Udham Singh Nagar">Udham Singh Nagar</option>
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
						</select>
					</div>
					<div class="form-group">
						<select name="blood_group" id="blood_group" style="padding: 0 20px; width: 220px; height: 45px;" class="form-control demo-default text-center">
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
					<div class="form-group">
						<button type="button" class="btn btn-lg btn-default search-btn" id="search">Search</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container" style="padding: 60px 0 60px 0;">
	<div class="row " id="data">

		<!-- Display The Search Result -->
		<?php 
		if ( isset($_GET['city'], $_GET['blood_group']) &&!empty($_GET['city']) && !empty($_GET['blood_group']) ) {
			$city = mysqli_real_escape_string($connection, $_GET['city']);
			$blood_group = mysqli_real_escape_string($connection, $_GET['blood_group']);
			
			// Search for available donors ONLY in the searched city with the searched blood group
			$sql = "SELECT * FROM donor WHERE city = '$city' AND blood_group = '$blood_group' AND save_life_date = '0'";
			$result = mysqli_query($connection, $sql);
			$availableDonorsFound = false;
			
			if(mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$availableDonorsFound = true;
					
					// Check if location is available
					$hasLocation = (!empty($row['latitude']) && !empty($row['longitude']) && 
								   $row['latitude'] != '0.0000000' && $row['longitude'] != '0.0000000');
					
					echo '
					<div class="col-md-3 col-sm-12 col-lg-3 donors_data">
						<div class="status-available">Available</div>
						<span class="name">' . htmlspecialchars($row['name']) . '</span>
						<span>' . htmlspecialchars($row['city']) . '</span>
						<span>' . htmlspecialchars($row['blood_group']) . '</span>
						<span>' . htmlspecialchars($row['gender']) . '</span>
						<span>' . htmlspecialchars($row['email']) . '</span>
						<span>' . htmlspecialchars($row['contact_no']) . '</span>';
					
					if($hasLocation) {
						echo '<button class="location-btn" onclick="showDonorLocation(' . $row['id'] . ', \'' . 
							 addslashes($row['name']) . '\', \'' . 
							 addslashes($row['city']) . '\', \'' . 
							 addslashes($row['blood_group']) . '\', \'' . 
							 addslashes($row['gender']) . '\', \'' . 
							 addslashes($row['email']) . '\', \'' . 
							 addslashes($row['contact_no']) . '\', ' . 
							 $row['latitude'] . ', ' . 
							 $row['longitude'] . ')">
							 📍 View Location on Map
						  </button>';
					} else {
						echo '<button class="location-btn no-location-btn" disabled>
							 ⚠️ Location Not Available
						  </button>';
					}
					
					echo '</div>';
				}
			}
			
			// Also show donated donors ONLY from the searched city with the searched blood group
			$donatedSql = "SELECT * FROM donor WHERE city = '$city' AND blood_group = '$blood_group' AND save_life_date != '0'";
			$donatedResult = mysqli_query($connection, $donatedSql);
			
			if(mysqli_num_rows($donatedResult) > 0) {
				while ($row = mysqli_fetch_assoc($donatedResult)) {
					echo '
					<div class="col-md-3 col-sm-12 col-lg-3 donors_data">
						<div class="status-donated">Donated</div>
						<span class="name">' . htmlspecialchars($row['name']) . '</span>
						<span>' . htmlspecialchars($row['city']) . '</span>
						<span>' . htmlspecialchars($row['blood_group']) . '</span>
						<span>' . htmlspecialchars($row['gender']) . '</span>
						<h4 class="name text-center" style="color: #28a745;">✅ Blood Donated</h4>
						<small style="color: #6c757d; text-align: center; display: block; margin-top: 5px;">
							Thank you for saving lives! 
						</small>
					</div>';
				}
			}
			
			// If no donors found, show message
			if (!$availableDonorsFound && mysqli_num_rows($donatedResult) == 0) {
				echo '<div class="col-12">
						<div class="no-donors-message" style="text-align: center; padding: 30px; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 15px; border: 2px solid #ffc107; margin: 20px;">
							<div style="font-size: 48px; color: #ffc107; margin-bottom: 15px;">🔍</div>
							<h3>No Donors Found</h3>
							<p>No donors found in <strong>' . htmlspecialchars($city) . '</strong> with blood group <strong>' . htmlspecialchars($blood_group) . '</strong>.</p>
							<p>Try searching for blood banks in this city using the search above!</p>
						</div>
					  </div>';
			}
		}
		?>
</div>
</div>

<!-- Map Modal -->
<div id="mapModal" class="map-modal">
	<div class="map-modal-content">
		<div class="map-header" id="mapHeader">
			<h3 id="modal-title">📍 Location</h3>
			<button class="close-btn" onclick="closeMapModal()">&times;</button>
		</div>
		<div class="location-info" id="location-info">
			<div class="location-info-grid" id="locationInfoGrid">
				<!-- Dynamic content will be inserted here -->
			</div>
		</div>
		<div id="map-loading" class="map-loading">
			<div class="loading-spinner"></div>
			Loading map...
		</div>
		<div id="location-map"></div>
	</div>
</div>

<div class="loader" id="wait">
	<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
</div>

<?php 

	//include footer file
	include ('include/footer.php');

?>

<script type="text/javascript">
	let map;
	let marker;
	let currentLocationData = null;

	// Original donor search functionality - FIXED
	$("#search").on('click', function(){
		var city = $("#city").val();
		var blood_group = $("#blood_group").val();
		
		// Validation
		if(!city || !blood_group) {
			Swal.fire({
				title: 'Please Select Both',
				text: 'Please select both city and blood group to search.',
				icon: 'warning',
				confirmButtonColor: '#dc3545',
				confirmButtonText: 'OK'
			});
			return;
		}

		// Show loader
		$("#wait").show();

		$.ajax({
			type: 'GET',
			url: 'ajaxsearch.php',
			data: {city: city, blood_group: blood_group},
			success: function(data){
				$("#wait").hide();
				if(!data.error){
					$("#data").html(data);
				}
			},
			error: function() {
				$("#wait").hide();
				Swal.fire({
					title: 'Error',
					text: 'Failed to search. Please try again.',
					icon: 'error',
					confirmButtonColor: '#dc3545',
					confirmButtonText: 'OK'
				});
			}
		});
	});

	// Show donor location on map
	function showDonorLocation(id, name, city, bloodGroup, gender, email, contact, latitude, longitude) {
		currentLocationData = {
			type: 'donor',
			id: id,
			name: name,
			city: city,
			bloodGroup: bloodGroup,
			gender: gender,
			email: email,
			contact: contact,
			latitude: parseFloat(latitude),
			longitude: parseFloat(longitude)
		};
		
		// Update modal for donor
		document.getElementById('mapHeader').className = 'map-header donor-header';
		document.getElementById('modal-title').innerHTML = `🩸 ${name}'s Location`;
		
		// Update info grid for donor
		document.getElementById('locationInfoGrid').innerHTML = `
			<div class="info-item">
				<span class="info-label">Name:</span>
				<span class="info-value donor-value">${name}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Blood Group:</span>
				<span class="info-value donor-value">${bloodGroup}</span>
			</div>
			<div class="info-item">
				<span class="info-label">City:</span>
				<span class="info-value donor-value">${city}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Gender:</span>
				<span class="info-value donor-value">${gender}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Email:</span>
				<span class="info-value donor-value">${email}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Contact:</span>
				<span class="info-value donor-value">${contact}</span>
			</div>
		`;
		
		showMapModal();
	}

	// Show blood bank location on map
	function showBloodBankLocation(name, city, address, contact, email, workingHours, bloodTypes, latitude, longitude) {
		currentLocationData = {
			type: 'bank',
			name: name,
			city: city,
			address: address,
			contact: contact,
			email: email,
			workingHours: workingHours,
			bloodTypes: bloodTypes,
			latitude: parseFloat(latitude),
			longitude: parseFloat(longitude)
		};
		
		// Update modal for blood bank
		document.getElementById('mapHeader').className = 'map-header bank-header';
		document.getElementById('modal-title').innerHTML = `🏥 ${name} Location`;
		
		// Update info grid for blood bank
		document.getElementById('locationInfoGrid').innerHTML = `
			<div class="info-item">
				<span class="info-label">Name:</span>
				<span class="info-value bank-value">${name}</span>
			</div>
			<div class="info-item">
				<span class="info-label">City:</span>
				<span class="info-value bank-value">${city}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Address:</span>
				<span class="info-value bank-value">${address}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Contact:</span>
				<span class="info-value bank-value">${contact}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Email:</span>
				<span class="info-value bank-value">${email}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Working Hours:</span>
				<span class="info-value bank-value">${workingHours}</span>
			</div>
		`;
		
		showMapModal();
	}

	// Show map modal
	function showMapModal() {
		document.getElementById('mapModal').style.display = 'block';
		document.getElementById('map-loading').style.display = 'flex';
		document.getElementById('location-map').style.display = 'none';
		
		setTimeout(() => {
			initLocationMap();
		}, 500);
	}

	// Initialize Google Map
	function initLocationMap() {
		if (!currentLocationData) return;
		
		const location = {
			lat: currentLocationData.latitude,
			lng: currentLocationData.longitude
		};
		
		// Create map
		map = new google.maps.Map(document.getElementById("location-map"), {
			center: location,
			zoom: 15,
			mapTypeControl: true,
			streetViewControl: true,
			fullscreenControl: true,
			styles: [
				{
					featureType: "poi.business",
					elementType: "labels",
					stylers: [{ visibility: "off" }]
				}
			]
		});
		
		// Create marker based on type
		let markerIcon, infoContent;
		
		if (currentLocationData.type === 'donor') {
			markerIcon = {
				url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
					<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="#dc3545">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
						<circle cx="12" cy="9" r="2" fill="white"/>
					</svg>
				`),
				scaledSize: new google.maps.Size(50, 50),
				anchor: new google.maps.Point(25, 50)
			};
			
			infoContent = `
				<div style="padding: 10px; max-width: 250px;">
					<h4 style="margin: 0 0 10px 0; color: #dc3545;">🩸 ${currentLocationData.name}</h4>
					<p style="margin: 5px 0;"><strong>Blood Group:</strong> ${currentLocationData.bloodGroup}</p>
					<p style="margin: 5px 0;"><strong>City:</strong> ${currentLocationData.city}</p>
					<p style="margin: 5px 0;"><strong>Contact:</strong> ${currentLocationData.contact}</p>
					<p style="margin: 5px 0;"><strong>Email:</strong> ${currentLocationData.email}</p>
					<div style="margin-top: 10px; padding: 8px; background: #e8f5e8; border-radius: 5px; font-size: 12px;">
						📍 <strong>Available for blood donation</strong>
					</div>
				</div>
			`;
		} else {
			markerIcon = {
				url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
					<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="#28a745">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
						<path d="M12 6c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="white"/>
						<path d="M11 7h2v4h-2V7z" fill="#28a745"/>
						<path d="M9 9h6v2H9V9z" fill="#28a745"/>
					</svg>
				`),
				scaledSize: new google.maps.Size(50, 50),
				anchor: new google.maps.Point(25, 50)
			};
			
			infoContent = `
				<div style="padding: 10px; max-width: 280px;">
					<h4 style="margin: 0 0 10px 0; color: #28a745;">🏥 ${currentLocationData.name}</h4>
					<p style="margin: 5px 0;"><strong>City:</strong> ${currentLocationData.city}</p>
					<p style="margin: 5px 0;"><strong>Address:</strong> ${currentLocationData.address}</p>
					<p style="margin: 5px 0;"><strong>Contact:</strong> ${currentLocationData.contact}</p>
					<p style="margin: 5px 0;"><strong>Email:</strong> ${currentLocationData.email}</p>
					<p style="margin: 5px 0;"><strong>Hours:</strong> ${currentLocationData.workingHours}</p>
					<div style="margin-top: 10px; padding: 8px; background: #e8f5e8; border-radius: 5px;">
						<strong>Available Blood Types:</strong><br>
						<span style="font-size: 12px;">${currentLocationData.bloodTypes}</span>
					</div>
				</div>
			`;
		}
		
		// Create marker
		marker = new google.maps.Marker({
			position: location,
			map: map,
			title: currentLocationData.name,
			icon: markerIcon,
			animation: google.maps.Animation.DROP
		});
		
		// Create info window
		const infoWindow = new google.maps.InfoWindow({
			content: infoContent
		});
		
		// Show info window on marker click
		marker.addListener('click', () => {
			infoWindow.open(map, marker);
		});
		
		// Auto-open info window
		setTimeout(() => {
			infoWindow.open(map, marker);
		}, 1000);
		
		// Hide loading and show map
		document.getElementById('map-loading').style.display = 'none';
		document.getElementById('location-map').style.display = 'block';
		
		// Trigger map resize
		setTimeout(() => {
			google.maps.event.trigger(map, 'resize');
			map.setCenter(location);
		}, 100);
	}

	// Close map modal
	function closeMapModal() {
		document.getElementById('mapModal').style.display = 'none';
		currentLocationData = null;
		
		// Clean up map
		if (map) {
			map = null;
		}
		if (marker) {
			marker = null;
		}
	}

	// Close modal when clicking outside
	window.onclick = function(event) {
		const modal = document.getElementById('mapModal');
		if (event.target == modal) {
			closeMapModal();
		}
	}

	// Handle escape key
	document.addEventListener('keydown', function(event) {
		if (event.key === 'Escape') {
			closeMapModal();
		}
	});
</script>

<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-vZc-uUNkjg8HLcCbkptTbfjHD3Zyq0&callback=initLocationMap"></script>
