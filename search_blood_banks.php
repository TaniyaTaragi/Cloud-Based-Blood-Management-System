<?php 
include ('include/config.php');

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = mysqli_real_escape_string($connection, $_GET['city']);
    $blood_group = isset($_GET['blood_group']) ? mysqli_real_escape_string($connection, $_GET['blood_group']) : '';
    
    echo '<div class="blood_bank_section">
            <div class="blood_bank_header">
                <div class="blood_bank_title">🏥 Blood Banks in ' . htmlspecialchars($city) . '</div>
                <p class="blood_bank_subtitle">Blood banks available in ' . htmlspecialchars($city) . ($blood_group ? ' for ' . htmlspecialchars($blood_group) . ' blood group' : '') . ':</p>
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
    
    // If no blood banks found in database, fetch coordinates from donor table for this city
    if (!$banksFound) {
        // Get coordinates from existing donors in this city
        $coordQuery = "SELECT latitude, longitude FROM donor WHERE city = '$city' AND latitude IS NOT NULL AND longitude IS NOT NULL AND latitude != '0.0000000' AND longitude != '0.0000000' LIMIT 1";
        $coordResult = mysqli_query($connection, $coordQuery);
        
        $defaultCoords = ['lat' => '28.6139', 'lng' => '77.2090']; // Default to Delhi
        
        if(mysqli_num_rows($coordResult) > 0) {
            $coordRow = mysqli_fetch_assoc($coordResult);
            $defaultCoords = ['lat' => $coordRow['latitude'], 'lng' => $coordRow['longitude']];
        }

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
            
            echo '    </div.')">
                 📍 View Location on Map
              </button>';
            
            echo '    </div>
                </div>';
        }
    }
    
    echo '      </div>
          </div>';
    
    // If no banks found at all
    if (!$banksFound && empty($sampleBanks)) {
        echo '<div class="no-banks-message">
                <div class="no-banks-icon">🏥</div>
                <h3>No Blood Banks Found</h3>
                <p>No blood banks found in <strong>' . htmlspecialchars($city) . '</strong>.</p>
                <p>Please contact nearby hospitals or try searching in nearby cities.</p>
              </div>';
    }
    
} else {
    echo '<div class="no-banks-message">
            <div class="no-banks-icon">⚠️</div>
            <h3>Invalid Search</h3>
            <p>Please select a city to search for blood banks.</p>
          </div>';
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
<?php 
include ('include/config.php');

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = mysqli_real_escape_string($connection, $_GET['city']);
    $blood_group = isset($_GET['blood_group']) ? mysqli_real_escape_string($connection, $_GET['blood_group']) : '';
    
    // First check if there are any available donors in this city
    $donorCheckSql = "SELECT COUNT(*) as donor_count FROM donor WHERE city = '$city' AND save_life_date = '0'";
    $donorCheckResult = mysqli_query($connection, $donorCheckSql);
    $donorCount = mysqli_fetch_assoc($donorCheckResult)['donor_count'];
    
    echo '<div class="blood_bank_section">
            <div class="blood_bank_header">
                <div class="blood_bank_title">🏥 Blood Banks in ' . htmlspecialchars($city) . '</div>';
    
    if ($donorCount > 0) {
        echo '<p class="blood_bank_subtitle">Found ' . $donorCount . ' donors in ' . htmlspecialchars($city) . '. Here are additional blood banks in this location:</p>';
    } else {
        echo '<p class="blood_bank_subtitle">No donors available in ' . htmlspecialchars($city) . '. Here are blood banks in this location:</p>';
    }
    
    echo '    </div>
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
                
                echo '      </div>
                    </div>
                </div>';
            }
        }
    }
    
    // If no blood banks found in database for this specific city, show sample blood banks for that city
    if (!$banksFound) {
        $sampleBanks = [
            [
                'name' => 'City General Blood Bank', 
                'city' => $city, 
                'address' => 'Main Hospital Complex, ' . $city,
                'contact' => '1800-BLOOD-1', 
                'email' => 'info@citybloodbank' . strtolower(str_replace(' ', '', $city)) . '.com', 
                'working_hours' => '24/7', 
                'blood_types' => 'A+,A-,B+,B-,O+,O-,AB+,AB-'
            ],
            [
                'name' => 'Red Cross Blood Center', 
                'city' => $city, 
                'address' => 'Red Cross Building, ' . $city,
                'contact' => '1800-RED-CROSS', 
                'email' => 'contact@redcross' . strtolower(str_replace(' ', '', $city)) . '.org', 
                'working_hours' => '8 AM - 8 PM', 
                'blood_types' => 'A+,B+,O+,AB+,A-,B-'
            ],
            [
                'name' => 'Community Blood Bank', 
                'city' => $city, 
                'address' => 'Community Health Center, ' . $city,
                'contact' => '080-COMMUNITY', 
                'email' => 'help@community' . strtolower(str_replace(' ', '', $city)) . '.in', 
                'working_hours' => '9 AM - 6 PM', 
                'blood_types' => 'A-,B-,O-,AB-,O+,A+'
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
            
            echo '      </div>
                </div>
            </div>';
        }
    }
    
    echo '      </div>
          </div>';
    
    // If no banks found at all
    if (!$banksFound && empty($sampleBanks)) {
        echo '<div class="no-banks-message">
                <div class="no-banks-icon">🏥</div>
                <h3>No Blood Banks Found</h3>
                <p>No blood banks found in <strong>' . htmlspecialchars($city) . '</strong>.</p>
                <p>Please contact nearby hospitals or try searching in nearby cities.</p>
              </div>';
    }
    
} else {
    echo '<div class="no-banks-message">
            <div class="no-banks-icon">⚠️</div>
            <h3>Invalid Search</h3>
            <p>Please select a city to search for blood banks.</p>
          </div>';
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
</style>
