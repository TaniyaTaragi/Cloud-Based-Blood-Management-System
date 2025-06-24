<?php 
include ('include/header.php');
?>

<style>
    .size{
        min-height: 0px;
        padding: 60px 0 40px 0;
    }
    h1{
        color: white;
    }
    
    /* Map Container */
    .map-container {
        height: 70vh;
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        margin: 20px 0;
    }
    
    #main-map {
        height: 100%;
        width: 100%;
    }
    
    /* Control Panel */
    .map-controls {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .control-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: center;
    }
    
    .filter-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .filter-btn.active {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-donors {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .btn-donors:hover, .btn-donors.active {
        background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
    }
    
    .btn-banks {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-banks:hover, .btn-banks.active {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    }
    
    .btn-all {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
        color: white;
    }
    
    .btn-all:hover, .btn-all.active {
        background: linear-gradient(135deg, #5a32a3 0%, #4c2a85 100%);
    }
    
    /* Search Controls */
    .search-controls {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-input {
        padding: 8px 15px;
        border: 2px solid #e9ecef;
        border-radius: 20px;
        outline: none;
        transition: border-color 0.3s ease;
    }
    
    .search-input:focus {
        border-color: #dc3545;
    }
    
    .search-btn {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
    }
    
    /* Info Panel */
    .info-panel {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-top: 20px;
    }
    
    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        text-align: center;
    }
    
    .stat-item {
        padding: 15px;
        border-radius: 10px;
        color: white;
        font-weight: 600;
    }
    
    .stat-donors {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }
    
    .stat-banks {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .stat-total {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .map-container {
            height: 50vh;
        }
        
        .control-group {
            flex-direction: column;
            gap: 10px;
        }
        
        .search-controls {
            width: 100%;
            justify-content: center;
        }
        
        .search-input {
            flex: 1;
            min-width: 200px;
        }
    }
    
    /* Loading Spinner */
    .map-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        background: rgba(255,255,255,0.9);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #dc3545;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="container-fluid red-background size">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center">🗺️ Blood Donors & Banks Map</h1>
            <hr class="white-bar">
            <p class="text-center" style="color: white; font-size: 16px;">
                Find blood donors and blood banks near you on the interactive map
            </p>
        </div>
    </div>
</div>

<div class="container" style="padding: 40px 0;">
    
    <!-- Map Controls -->
    <div class="map-controls">
        <div class="control-group">
            <div class="filter-buttons">
                <button class="filter-btn btn-all active" onclick="toggleMarkers('all')">
                    🌟 Show All
                </button>
                <button class="filter-btn btn-donors" onclick="toggleMarkers('donors')">
                    🩸 Donors Only
                </button>
                <button class="filter-btn btn-banks" onclick="toggleMarkers('banks')">
                    🏥 Blood Banks Only
                </button>
            </div>
            
            <div class="search-controls">
                <select class="search-input" id="cityFilter">
                    <option value="">All Cities</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Kolkata">Kolkata</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Pune">Pune</option>
                    <option value="Ahmedabad">Ahmedabad</option>
                </select>
                
                <select class="search-input" id="bloodGroupFilter">
                    <option value="">All Blood Groups</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
                
                <button class="search-btn" onclick="applyFilters()">
                    🔍 Filter
                </button>
            </div>
        </div>
    </div>
    
    <!-- Map Container -->
    <div class="map-container">
        <div id="map-loading" class="map-loading">
            <div class="spinner"></div>
            <div>Loading map data...</div>
        </div>
        <div id="main-map"></div>
    </div>
    
    <!-- Info Panel -->
    <div class="info-panel">
        <h3 style="text-align: center; color: #333; margin-bottom: 20px;">📊 Statistics</h3>
        <div class="stats">
            <div class="stat-item stat-donors">
                <div style="font-size: 24px;" id="donor-count">0</div>
                <div>Available Donors</div>
            </div>
            <div class="stat-item stat-banks">
                <div style="font-size: 24px;" id="bank-count">0</div>
                <div>Blood Banks</div>
            </div>
            <div class="stat-item stat-total">
                <div style="font-size: 24px;" id="total-count">0</div>
                <div>Total Locations</div>
            </div>
        </div>
    </div>
</div>

<script>
let map;
let donorMarkers = [];
let bankMarkers = [];
let allMarkers = [];
let currentFilter = 'all';

// Sample data - replace with actual database data
const donorsData = [
    <?php
    // Get donors with location data
    $sql = "SELECT * FROM donor WHERE save_life_date = '0' AND latitude IS NOT NULL AND longitude IS NOT NULL AND latitude != '0.0000000' AND longitude != '0.0000000'";
    $result = mysqli_query($connection, $sql);
    $donors = [];
    
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $donors[] = [
                'id' => $row['id'],
                'name' => addslashes($row['name']),
                'city' => addslashes($row['city']),
                'blood_group' => $row['blood_group'],
                'gender' => $row['gender'],
                'email' => addslashes($row['email']),
                'contact' => $row['contact_no'],
                'lat' => floatval($row['latitude']),
                'lng' => floatval($row['longitude']),
                'type' => 'donor'
            ];
        }
    }
    
    // Output donors data as JavaScript
    foreach($donors as $index => $donor) {
        echo json_encode($donor);
        if($index < count($donors) - 1) echo ',';
    }
    ?>
];

// Sample blood banks data (replace with actual database data)
const bloodBanksData = [
    {
        id: 1,
        name: "City General Blood Bank",
        city: "Delhi",
        contact: "011-12345678",
        email: "info@citybloodbank.com",
        working_hours: "24/7",
        blood_types: "A+,A-,B+,B-,O+,O-,AB+,AB-",
        lat: 28.6139,
        lng: 77.2090,
        type: "bank"
    },
    {
        id: 2,
        name: "Red Cross Blood Center",
        city: "Mumbai",
        contact: "022-87654321",
        email: "contact@redcrossblood.org",
        working_hours: "8 AM - 8 PM",
        blood_types: "A+,B+,O+,AB+",
        lat: 19.0760,
        lng: 72.8777,
        type: "bank"
    },
    {
        id: 3,
        name: "Community Blood Bank",
        city: "Bangalore",
        contact: "080-11223344",
        email: "help@communityblood.in",
        working_hours: "9 AM - 6 PM",
        blood_types: "A-,B-,O-,AB-",
        lat: 12.9716,
        lng: 77.5946,
        type: "bank"
    },
    {
        id: 4,
        name: "Apollo Blood Bank",
        city: "Chennai",
        contact: "044-55667788",
        email: "blood@apollo.com",
        working_hours: "24/7",
        blood_types: "A+,A-,B+,B-,O+,O-,AB+,AB-",
        lat: 13.0827,
        lng: 80.2707,
        type: "bank"
    },
    {
        id: 5,
        name: "Max Blood Center",
        city: "Delhi",
        contact: "011-99887766",
        email: "blood@maxhealthcare.com",
        working_hours: "24/7",
        blood_types: "A+,B+,O+,AB+,A-,B-",
        lat: 28.5355,
        lng: 77.3910,
        type: "bank"
    }
];

// Initialize map
function initMap() {
    // Create map centered on India
    map = new google.maps.Map(document.getElementById("main-map"), {
        center: { lat: 20.5937, lng: 78.9629 }, // Center of India
        zoom: 5,
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
    
    // Load all markers
    loadDonorMarkers();
    loadBankMarkers();
    updateStats();
    
    // Hide loading
    document.getElementById('map-loading').style.display = 'none';
}

// Load donor markers
function loadDonorMarkers() {
    donorsData.forEach(donor => {
        const marker = new google.maps.Marker({
            position: { lat: donor.lat, lng: donor.lng },
            map: map,
            title: `${donor.name} - ${donor.blood_group} Donor`,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#dc3545">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        <circle cx="12" cy="9" r="2" fill="white"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            }
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; max-width: 250px;">
                    <h4 style="margin: 0 0 10px 0; color: #dc3545;">🩸 ${donor.name}</h4>
                    <p style="margin: 5px 0;"><strong>Blood Group:</strong> <span style="color: #dc3545; font-weight: bold;">${donor.blood_group}</span></p>
                    <p style="margin: 5px 0;"><strong>City:</strong> ${donor.city}</p>
                    <p style="margin: 5px 0;"><strong>Gender:</strong> ${donor.gender}</p>
                    <p style="margin: 5px 0;"><strong>Contact:</strong> ${donor.contact}</p>
                    <p style="margin: 5px 0;"><strong>Email:</strong> ${donor.email}</p>
                    <div style="margin-top: 10px; padding: 8px; background: #e8f5e8; border-radius: 5px; font-size: 12px;">
                        ✅ <strong>Available for blood donation</strong>
                    </div>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            closeAllInfoWindows();
            infoWindow.open(map, marker);
        });
        
        marker.donorData = donor;
        donorMarkers.push(marker);
        allMarkers.push(marker);
    });
}

// Load blood bank markers
function loadBankMarkers() {
    bloodBanksData.forEach(bank => {
        const marker = new google.maps.Marker({
            position: { lat: bank.lat, lng: bank.lng },
            map: map,
            title: `${bank.name} - Blood Bank`,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#28a745">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        <path d="M12 6c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="white"/>
                        <path d="M11 7h2v4h-2V7z" fill="#28a745"/>
                        <path d="M9 9h6v2H9V9z" fill="#28a745"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            }
        });
        
        const bloodTypesHtml = bank.blood_types.split(',').map(type => 
            `<span style="background: #28a745; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; margin: 1px;">${type.trim()}</span>`
        ).join(' ');
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; max-width: 280px;">
                    <h4 style="margin: 0 0 10px 0; color: #28a745;">🏥 ${bank.name}</h4>
                    <p style="margin: 5px 0;"><strong>City:</strong> ${bank.city}</p>
                    <p style="margin: 5px 0;"><strong>Contact:</strong> ${bank.contact}</p>
                    <p style="margin: 5px 0;"><strong>Email:</strong> ${bank.email}</p>
                    <p style="margin: 5px 0;"><strong>Hours:</strong> ${bank.working_hours}</p>
                    <div style="margin-top: 10px; padding: 8px; background: #e8f5e8; border-radius: 5px;">
                        <strong>Available Blood Types:</strong><br>
                        ${bloodTypesHtml}
                    </div>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            closeAllInfoWindows();
            infoWindow.open(map, marker);
        });
        
        marker.bankData = bank;
        bankMarkers.push(marker);
        allMarkers.push(marker);
    });
}

// Close all info windows
function closeAllInfoWindows() {
    // This will be handled by Google Maps automatically when a new one opens
}

// Toggle markers visibility
function toggleMarkers(type) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.btn-${type}`).classList.add('active');
    
    currentFilter = type;
    
    switch(type) {
        case 'donors':
            donorMarkers.forEach(marker => marker.setVisible(true));
            bankMarkers.forEach(marker => marker.setVisible(false));
            break;
        case 'banks':
            donorMarkers.forEach(marker => marker.setVisible(false));
            bankMarkers.forEach(marker => marker.setVisible(true));
            break;
        case 'all':
        default:
            donorMarkers.forEach(marker => marker.setVisible(true));
            bankMarkers.forEach(marker => marker.setVisible(true));
            break;
    }
    
    updateStats();
}

// Apply filters
function applyFilters() {
    const cityFilter = document.getElementById('cityFilter').value;
    const bloodGroupFilter = document.getElementById('bloodGroupFilter').value;
    
    // Filter donors
    donorMarkers.forEach(marker => {
        let show = true;
        
        if (cityFilter && marker.donorData.city !== cityFilter) {
            show = false;
        }
        
        if (bloodGroupFilter && marker.donorData.blood_group !== bloodGroupFilter) {
            show = false;
        }
        
        if (currentFilter === 'banks') {
            show = false;
        } else if (currentFilter === 'donors' || currentFilter === 'all') {
            marker.setVisible(show);
        }
    });
    
    // Filter blood banks
    bankMarkers.forEach(marker => {
        let show = true;
        
        if (cityFilter && marker.bankData.city !== cityFilter) {
            show = false;
        }
        
        if (bloodGroupFilter && !marker.bankData.blood_types.includes(bloodGroupFilter)) {
            show = false;
        }
        
        if (currentFilter === 'donors') {
            show = false;
        } else if (currentFilter === 'banks' || currentFilter === 'all') {
            marker.setVisible(show);
        }
    });
    
    updateStats();
}

// Update statistics
function updateStats() {
    const visibleDonors = donorMarkers.filter(marker => marker.getVisible()).length;
    const visibleBanks = bankMarkers.filter(marker => marker.getVisible()).length;
    
    document.getElementById('donor-count').textContent = visibleDonors;
    document.getElementById('bank-count').textContent = visibleBanks;
    document.getElementById('total-count').textContent = visibleDonors + visibleBanks;
}

// Initialize map when page loads
window.onload = function() {
    initMap();
};
</script>

<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-vZc-uUNkjg8HLcCbkptTbfjHD3Zyq0&callback=initMap"></script>

<?php 
include ('include/footer.php');
?>
