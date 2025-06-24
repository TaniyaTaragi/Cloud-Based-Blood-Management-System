<?php	
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
		margin:20px 5px 0px 5px;
		-webkit-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		-moz-box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		box-shadow: 0px 2px 5px -2px rgba(89,89,89,0.95);
		padding: 20px;
		position: relative;
	}
	
	/* NEW: Location Button Styles */
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
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
		color: white;
		padding: 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
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
	
	.donor-info {
		padding: 15px 20px;
		background: #f8f9fa;
		border-bottom: 1px solid #dee2e6;
	}
	
	.donor-info-grid {
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
		color: #dc3545;
		font-weight: 500;
	}
	
	#donor-map {
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
	
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
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
		
		.donor-info-grid {
			grid-template-columns: 1fr;
			gap: 8px;
		}
		
		#donor-map {
			height: calc(85vh - 120px);
		}
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
</style>

<div class="container-fluid red-background size">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h1 class="text-center">🩸 Blood Donors</h1>
			<hr class="white-bar">
		</div>
	</div>
</div>

<div class="container" style="padding: 60px 0;">
	
	<div class="row data" id="donorsList">
		
<?php
		// Get all donors
		$sql = "SELECT * FROM donor ORDER BY save_life_date ASC, name ASC";
        $result = mysqli_query($connection, $sql);
        $availableDonors = [];
        $donatedDonors = [];
        
        if(mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            if($row['save_life_date'] == '0') {
	                $availableDonors[] = $row;
	            } else {
	                $donatedDonors[] = $row;
	            }
	        }
        }
        
        // Display available donors first
        foreach($availableDonors as $row) {
			$hasLocation = (!empty($row['latitude']) && !empty($row['longitude']) && 
						   $row['latitude'] != '0.0000000' && $row['longitude'] != '0.0000000');
			
 		    echo '
            <div class="col-md-3 col-sm-12 col-lg-3 donors_data">
				<div class="status-available">Available</div>
                <span class="name"> ' . htmlspecialchars($row['name']) . '</span>
                <span> ' . htmlspecialchars($row['city']) . '</span>
                <span> ' . htmlspecialchars($row['blood_group']) . '</span>
                <span> ' . htmlspecialchars($row['gender']) . '</span>
                <span> ' . htmlspecialchars($row['email']) . '</span>
                <span> ' . htmlspecialchars($row['contact_no']) . '</span>';
			
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
        
        // Display donated donors
        foreach($donatedDonors as $row) {
            echo '
            <div class="col-md-3 col-sm-12 col-lg-3 donors_data">
				<div class="status-donated">Donated</div>
                <span class="name"> ' . htmlspecialchars($row['name']) . '</span>
                <span> ' . htmlspecialchars($row['city']) . '</span>
                <span>' . htmlspecialchars($row['blood_group']) . '</span>
                <span>' . htmlspecialchars($row['gender']) . '</span>
                <h4 class="name text-center" style="margin-top: 15px; color: #28a745;">✅ Blood Donated</h4>
				<small style="color: #6c757d; text-align: center; display: block; margin-top: 5px;">
					Thank you for saving lives! 
				</small>
            </div>';
        }
        
        // If no donors at all, show message
        if(empty($availableDonors) && empty($donatedDonors)) {
	        echo '<div class="col-12 text-center">
			        <h3>No donors found</h3>
			        <p>Be the first to register as a blood donor!</p>
		          </div>';
        }
?>

	</div>
</div>

<!-- Map Modal -->
<div id="mapModal" class="map-modal">
	<div class="map-modal-content">
		<div class="map-header">
			<h3 id="modal-title">📍 Donor Location</h3>
			<button class="close-btn" onclick="closeMapModal()">&times;</button>
		</div>
		<div class="donor-info" id="donor-info">
			<div class="donor-info-grid">
				<div class="info-item">
					<span class="info-label">Name:</span>
					<span class="info-value" id="info-name">-</span>
				</div>
				<div class="info-item">
					<span class="info-label">Blood Group:</span>
					<span class="info-value" id="info-blood">-</span>
				</div>
				<div class="info-item">
					<span class="info-label">City:</span>
					<span class="info-value" id="info-city">-</span>
				</div>
				<div class="info-item">
					<span class="info-label">Gender:</span>
					<span class="info-value" id="info-gender">-</span>
				</div>
				<div class="info-item">
					<span class="info-label">Email:</span>
					<span class="info-value" id="info-email">-</span>
				</div>
				<div class="info-item">
					<span class="info-label">Contact:</span>
					<span class="info-value" id="info-contact">-</span>
				</div>
			</div>
		</div>
		<div id="map-loading" class="map-loading">
			<div class="loading-spinner"></div>
			Loading map...
		</div>
		<div id="donor-map"></div>
	</div>
</div>

<script>
let map;
let marker;
let currentDonorData = null;

// Show donor location on map
function showDonorLocation(id, name, city, bloodGroup, gender, email, contact, latitude, longitude) {
	// Store donor data
	currentDonorData = {
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
	
	// Update modal info
	document.getElementById('modal-title').innerHTML = `📍 ${name}'s Location`;
	document.getElementById('info-name').textContent = name;
	document.getElementById('info-blood').textContent = bloodGroup;
	document.getElementById('info-city').textContent = city;
	document.getElementById('info-gender').textContent = gender;
	document.getElementById('info-email').textContent = email;
	document.getElementById('info-contact').textContent = contact;
	
	// Show modal
	document.getElementById('mapModal').style.display = 'block';
	document.getElementById('map-loading').style.display = 'flex';
	document.getElementById('donor-map').style.display = 'none';
	
	// Initialize map after a short delay
	setTimeout(() => {
		initDonorMap();
	}, 500);
}

// Initialize Google Map
function initDonorMap() {
	if (!currentDonorData) return;
	
	const donorLocation = {
		lat: currentDonorData.latitude,
		lng: currentDonorData.longitude
	};
	
	// Create map
	map = new google.maps.Map(document.getElementById("donor-map"), {
		center: donorLocation,
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
	
	// Create custom marker icon
	const markerIcon = {
		url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
			<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="#dc3545">
				<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
				<circle cx="12" cy="9" r="2" fill="white"/>
			</svg>
		`),
		scaledSize: new google.maps.Size(50, 50),
		anchor: new google.maps.Point(25, 50)
	};
	
	// Create marker
	marker = new google.maps.Marker({
		position: donorLocation,
		map: map,
		title: `${currentDonorData.name} - ${currentDonorData.bloodGroup} Donor`,
		icon: markerIcon,
		animation: google.maps.Animation.DROP
	});
	
	// Create info window
	const infoWindow = new google.maps.InfoWindow({
		content: `
			<div style="padding: 10px; max-width: 250px;">
				<h4 style="margin: 0 0 10px 0; color: #dc3545;">🩸 ${currentDonorData.name}</h4>
				<p style="margin: 5px 0;"><strong>Blood Group:</strong> ${currentDonorData.bloodGroup}</p>
				<p style="margin: 5px 0;"><strong>City:</strong> ${currentDonorData.city}</p>
				<p style="margin: 5px 0;"><strong>Contact:</strong> ${currentDonorData.contact}</p>
				<p style="margin: 5px 0;"><strong>Email:</strong> ${currentDonorData.email}</p>
				<div style="margin-top: 10px; padding: 8px; background: #e8f5e8; border-radius: 5px; font-size: 12px;">
					📍 <strong>Available for blood donation</strong>
				</div>
			</div>
		`
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
	document.getElementById('donor-map').style.display = 'block';
	
	// Trigger map resize
	setTimeout(() => {
		google.maps.event.trigger(map, 'resize');
		map.setCenter(donorLocation);
	}, 100);
}

// Close map modal
function closeMapModal() {
	document.getElementById('mapModal').style.display = 'none';
	currentDonorData = null;
	
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

// Show loading message if no location data
document.addEventListener('DOMContentLoaded', function() {
	const noLocationBtns = document.querySelectorAll('.no-location-btn');
	noLocationBtns.forEach(btn => {
		btn.addEventListener('click', function() {
			Swal.fire({
				title: 'Location Not Available',
				text: 'This donor has not shared their location yet. Please contact them directly using the provided contact information.',
				icon: 'info',
				confirmButtonColor: '#dc3545',
				confirmButtonText: 'Understood'
			});
		});
	});
});
</script>

<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-vZc-uUNkjg8HLcCbkptTbfjHD3Zyq0&callback=initDonorMap"></script>

<?php	
include ('include/footer.php'); 
?>