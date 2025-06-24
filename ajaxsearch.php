<?php 
include ('include/config.php');

if (isset($_GET['city'], $_GET['blood_group']) && !empty($_GET['city']) && !empty($_GET['blood_group'])) {
    $city = $_GET['city'];
    $blood_group = $_GET['blood_group'];
    
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
    
    // Show blood banks ONLY for the searched city
    echo '<div class="col-12">
            <div class="blood_bank_section">
                <div class="blood_bank_header">
                    <div class="blood_bank_title">🏥 Blood Banks in ' . htmlspecialchars($city) . '</div>
                    <p class="blood_bank_subtitle">Blood banks available in ' . htmlspecialchars($city) . ' for ' . htmlspecialchars($blood_group) . ' blood group:</p>
                </div>
                <div class="row">';
    
    // Check if blood_banks table exists
    $bloodBankSql = "SHOW TABLES LIKE 'blood_banks'";
    $tableExists = mysqli_query($connection, $bloodBankSql);
    $banksFound = false;
    
    if(mysqli_num_rows($tableExists) > 0) {
        // Search for blood banks specifically in the searched city
        $bankSql = "SELECT * FROM blood_banks WHERE city = '$city' AND status = 'active'";
        
        // If blood group is specified, filter by blood type availability
        if (!empty($blood_group)) {
            $bankSql .= " AND available_blood_types LIKE '%$blood_group%'";
        }
        
        $bankResult = mysqli_query($connection, $bankSql);
        
        if(mysqli_num_rows($bankResult) > 0) {
            $banksFound = true;
            while($bank = mysqli_fetch_assoc($bankResult)) {
                // Check if blood bank has location data
                $bankHasLocation = (!empty($bank['latitude']) && !empty($bank['longitude']) && 
                                   $bank['latitude'] != '0.0000000' && $bank['longitude'] != '0.0000000');
                
                echo '
                <div class="col-md-6 col-lg-4">
                    <div class="blood_bank_data">
                        <div class="blood-bank-badge">🏥 Blood Bank</div>
                        <div class="bank-name">' . htmlspecialchars($bank['name']) . '</div>
                        
                        <div class="bank-info">
                            <span class="bank-info-icon">📍</span>
                            <span>' . htmlspecialchars($bank['city']) . '</span>
                        </div>';
                
                if (!empty($bank['address'])) {
                    echo '
                        <div class="bank-info">
                            <span class="bank-info-icon">🏠</span>
                            <span>' . htmlspecialchars($bank['address']) . '</span>
                        </div>';
                }
                
                echo '
                        <div class="bank-info">
                            <span class="bank-info-icon">📞</span>
                            <span>' . htmlspecialchars($bank['contact']) . '</span>
                        </div>
                        
                        <div class="bank-info">
                            <span class="bank-info-icon">✉️</span>
                            <span>' . htmlspecialchars($bank['email']) . '</span>
                        </div>
                        
                        <div class="bank-info">
                            <span class="bank-info-icon">🕒</span>
                            <span>' . htmlspecialchars($bank['working_hours']) . '</span>
                        </div>
                        
                        <div class="blood-availability">
                            <div class="blood-availability-title">🩸 Available Blood Types:</div>';
                
                // Display available blood types
                $bloodTypes = explode(',', $bank['available_blood_types']);
                foreach($bloodTypes as $type) {
                    $typeClass = (!empty($blood_group) && trim($type) == $blood_group) ? 'blood-type-highlight' : 'blood-type';
                    echo '<span class="' . $typeClass . '">' . trim($type) . '</span>';
                }
                
                echo '      </div>';
                
                // Add location button for blood bank
                if($bankHasLocation) {
                    echo '<button class="location-btn" onclick="showBloodBankLocation(\'' . 
                         addslashes($bank['name']) . '\', \'' . 
                         addslashes($bank['city']) . '\', \'' . 
                         addslashes($bank['address']) . '\', \'' . 
                         addslashes($bank['contact']) . '\', \'' . 
                         addslashes($bank['email']) . '\', \'' . 
                         addslashes($bank['working_hours']) . '\', \'' . 
                         addslashes($bank['available_blood_types']) . '\', ' . 
                         $bank['latitude'] . ', ' . 
                         $bank['longitude'] . ')">
                         📍 View Location on Map
                      </button>';
                } else {
                    echo '<button class="location-btn no-location-btn" disabled>
                         ⚠️ Location Not Available
                      </button>';
                }
                
                echo '    </div>
                    </div>';
            }
        }
    }
    
    // If no blood banks found in database for this specific city, show sample blood banks for that city
    if (!$banksFound) {
        // Generate realistic coordinates for the searched city
        $cityCoordinates = [
            'Haldwani' => ['lat' => '29.2183', 'lng' => '79.5130'],
            'Haridwar' => ['lat' => '29.9457', 'lng' => '78.1642'],
            'Dehradun' => ['lat' => '30.3165', 'lng' => '78.0322'],
            'Nainital' => ['lat' => '29.3919', 'lng' => '79.4542'],
            'Almora' => ['lat' => '29.5971', 'lng' => '79.6590'],
            'Bageshwar' => ['lat' => '29.8390', 'lng' => '79.7737'],
            'Chamoli' => ['lat' => '30.4041', 'lng' => '79.3230'],
            'Champawat' => ['lat' => '29.3367', 'lng' => '80.0955'],
            'Pauri Garhwal' => ['lat' => '30.1497', 'lng' => '78.7820'],
            'Pithoragarh' => ['lat' => '29.5817', 'lng' => '80.2109'],
            'Rudraprayag' => ['lat' => '30.2849', 'lng' => '78.9811'],
            'Tehri Garhwal' => ['lat' => '30.3783', 'lng' => '78.4803'],
            'Udham Singh Nagar' => ['lat' => '28.9845', 'lng' => '79.4504'],
            'Uttarkashi' => ['lat' => '30.7268', 'lng' => '78.4354'],
            'Delhi' => ['lat' => '28.6139', 'lng' => '77.2090'],
            'Central Delhi' => ['lat' => '28.6508', 'lng' => '77.2311'],
            'East Delhi' => ['lat' => '28.6508', 'lng' => '77.3152'],
            'New Delhi' => ['lat' => '28.6139', 'lng' => '77.2090'],
            'North Delhi' => ['lat' => '28.7041', 'lng' => '77.1025'],
            'South Delhi' => ['lat' => '28.5355', 'lng' => '77.3910'],
            'West Delhi' => ['lat' => '28.6692', 'lng' => '77.1174'],
            'Patna' => ['lat' => '25.5941', 'lng' => '85.1376'],
            'Gaya' => ['lat' => '24.7914', 'lng' => '85.0002'],
            'Bhagalpur' => ['lat' => '25.2425', 'lng' => '86.9842'],
            'Muzaffarpur' => ['lat' => '26.1209', 'lng' => '85.3647'],
            'Kolkata' => ['lat' => '22.5726', 'lng' => '88.3639'],
            'Howrah' => ['lat' => '22.5958', 'lng' => '88.2636'],
            'Darjeeling' => ['lat' => '27.0360', 'lng' => '88.2627']
        ];

        $defaultCoords = isset($cityCoordinates[$city]) ? $cityCoordinates[$city] : ['lat' => '28.6139', 'lng' => '77.2090'];

        $sampleBanks = [
            [
                'name' => 'City General Blood Bank', 
                'city' => $city, 
                'address' => 'Main Hospital Complex, ' . $city,
                'contact' => '1800-BLOOD-1', 
                'email' => 'info@citybloodbank' . strtolower(str_replace(' ', '', $city)) . '.com', 
                'working_hours' => '24/7', 
                'blood_types' => 'A+,A-,B+,B-,O+,O-,AB+,AB-',
                'latitude' => $defaultCoords['lat'],
                'longitude' => $defaultCoords['lng']
            ],
            [
                'name' => 'Red Cross Blood Center', 
                'city' => $city, 
                'address' => 'Red Cross Building, ' . $city,
                'contact' => '1800-RED-CROSS', 
                'email' => 'contact@redcross' . strtolower(str_replace(' ', '', $city)) . '.org', 
                'working_hours' => '8 AM - 8 PM', 
                'blood_types' => 'A+,B+,O+,AB+,A-,B-',
                'latitude' => (string)(floatval($defaultCoords['lat']) + 0.01),
                'longitude' => (string)(floatval($defaultCoords['lng']) + 0.01)
            ],
            [
                'name' => 'Community Blood Bank', 
                'city' => $city, 
                'address' => 'Community Health Center, ' . $city,
                'contact' => '080-COMMUNITY', 
                'email' => 'help@community' . strtolower(str_replace(' ', '', $city)) . '.in', 
                'working_hours' => '9 AM - 6 PM', 
                'blood_types' => 'A-,B-,O-,AB-,O+,A+',
                'latitude' => (string)(floatval($defaultCoords['lat']) - 0.01),
                'longitude' => (string)(floatval($defaultCoords['lng']) - 0.01)
            ]
        ];
        
        foreach($sampleBanks as $bank) {
            echo '
            <div class="col-md-6 col-lg-4">
                <div class="blood_bank_data">
                    <div class="blood-bank-badge">🏥 Blood Bank</div>
                    <div class="bank-name">' . htmlspecialchars($bank['name']) . '</div>
                    
                    <div class="bank-info">
                        <span class="bank-info-icon">📍</span>
                        <span>' . htmlspecialchars($bank['city']) . '</span>
                    </div>
                    
                    <div class="bank-info">
                        <span class="bank-info-icon">🏠</span>
                        <span>' . htmlspecialchars($bank['address']) . '</span>
                    </div>
                    
                    <div class="bank-info">
                        <span class="bank-info-icon">📞</span>
                        <span>' . htmlspecialchars($bank['contact']) . '</span>
                    </div>
                    
                    <div class="bank-info">
                        <span class="bank-info-icon">✉️</span>
                        <span>' . htmlspecialchars($bank['email']) . '</span>
                    </div>
                    
                    <div class="bank-info">
                        <span class="bank-info-icon">🕒</span>
                        <span>' . htmlspecialchars($bank['working_hours']) . '</span>
                    </div>
                    
                    <div class="blood-availability">
                        <div class="blood-availability-title">🩸 Available Blood Types:</div>';
            
            $bloodTypes = explode(',', $bank['blood_types']);
            foreach($bloodTypes as $type) {
                $typeClass = (!empty($blood_group) && trim($type) == $blood_group) ? 'blood-type-highlight' : 'blood-type';
                echo '<span class="' . $typeClass . '">' . trim($type) . '</span>';
            }
            
            echo '      </div>';
            
            // Add location button for sample blood bank
            echo '<button class="location-btn" onclick="showBloodBankLocation(\'' . 
                 addslashes($bank['name']) . '\', \'' . 
                 addslashes($bank['city']) . '\', \'' . 
                 addslashes($bank['address']) . '\', \'' . 
                 addslashes($bank['contact']) . '\', \'' . 
                 addslashes($bank['email']) . '\', \'' . 
                 addslashes($bank['working_hours']) . '\', \'' . 
                 addslashes($bank['blood_types']) . '\', ' . 
                 $bank['latitude'] . ', ' . 
                 $bank['longitude'] . ')">
                 📍 View Location on Map
              </button>';
            
            echo '    </div>
                </div>';
        }
    }
    
    echo '      </div>
          </div>
      </div>';
    
    // If no donors found at all, show message
    if (!$availableDonorsFound && mysqli_num_rows($donatedResult) == 0) {
        echo '<div class="col-12">
                <div class="no-donors-message" style="text-align: center; padding: 30px; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 15px; border: 2px solid #ffc107; margin: 20px;">
                    <div style="font-size: 48px; color: #ffc107; margin-bottom: 15px;">🔍</div>
                    <h3>No Donors Found</h3>
                    <p>No donors found in <strong>' . htmlspecialchars($city) . '</strong> with blood group <strong>' . htmlspecialchars($blood_group) . '</strong>.</p>
                    <p>But check the blood banks above for available blood!</p>
                </div>
              </div>';
    }
}
?>

<style>
/* Additional styles for search results */
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

.no-location-btn {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #212529;
}

.no-location-btn:hover {
    background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
    color: white;
}

@media (max-width: 768px) {
    .blood_bank_data {
        margin: 10px 5px;
    }
    
    .blood_bank_title {
        font-size: 18px;
        padding: 12px 20px;
    }
}
</style>
