<?php
require_once('db-connect.php');
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
$expires = isset($_GET['expires']) ? floatval($_GET['expires']) : 0;

$class_name = '';
$is_valid_qr = false;
$already_checked_in = false;
$is_expired = false;
$students_in_class = [];

$school_lat = 0;
$school_lng = 0;
$allowed_radius = 100;

$server_time = round(microtime(true) * 1000);
if ($class_id > 0 && !empty($date)) {
    // Check if QR is expired
    if ($expires > 0 && $server_time > $expires) {
        $is_expired = true;
    }

    // Validate class exists
    $class_qry = $conn->query("SELECT name FROM `class_tbl` WHERE id = '{$class_id}'");
    if ($class_qry && $class_qry->num_rows > 0) {
        $class_name = $class_qry->fetch_assoc()['name'];
        $is_valid_qr = true;

        // Fetch students in this class
        $stud_qry = $conn->query("SELECT id, name, name_latin, gender FROM `students_tbl` WHERE class_id = '{$class_id}' ORDER BY name ASC");
        if ($stud_qry) {
            $students_in_class = $stud_qry->fetch_all(MYSQLI_ASSOC);
        }

        // Fetch school location settings
        $settings_qry = $conn->query("SELECT meta_key, meta_value FROM `settings_tbl` WHERE meta_key IN ('school_lat', 'school_lng', 'allowed_radius')");
        if ($settings_qry) {
            $sett_arr = [];
            while ($s_row = $settings_qry->fetch_assoc()) {
                $sett_arr[$s_row['meta_key']] = $s_row['meta_value'];
            }
            $school_lat = floatval($sett_arr['school_lat'] ?? 0);
            $school_lng = floatval($sett_arr['school_lng'] ?? 0);
            $allowed_radius = floatval($sett_arr['allowed_radius'] ?? 100);
        }
    }

    // Check device cookie to prevent duplicate check-in
    if (isset($_COOKIE['marked_attendance_date']) && $_COOKIE['marked_attendance_date'] === $date) {
        $already_checked_in = true;
    }
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កត់វត្តមានតាម QR Code | QR Attendance Check-In</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --font-main: 'Kantumruy Pro', 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1f2937;
        }

        .checkin-container {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 2.5rem 2rem;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            border-radius: 16px;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .title {
            font-weight: 700;
            font-size: 1.35rem;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .gps-status-box {
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .gps-status-box.pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .gps-status-box.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .gps-status-box.error {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .input-group-custom {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1.5px solid #d1d5db;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .name-preview-box {
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.5rem;
            min-height: 20px;
        }

        .name-preview-box.match {
            color: var(--success);
        }

        .name-preview-box.no-match {
            color: var(--danger);
        }

        .btn-submit {
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 1rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover:not(:disabled) {
            background-color: var(--primary-hover);
        }

        .btn-submit:disabled {
            background-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .alert-box {
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        /* Success & Error State Views */
        .state-view {
            text-align: center;
            padding: 1rem 0;
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .state-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .state-icon.success { color: var(--success); }
        .state-icon.error { color: var(--danger); }
    </style>
</head>
<body>

<div class="checkin-container">
    <div class="header-logo">
        <div class="logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 15h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2zm1 1v1h1v-1H4zM3 9.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2zm1 1v1h1v-1H4zM9 4.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2zm1 1v1h1v-1h-1zM9.05 9.5a.5.5 0 0 1 .537-.462l1.402.167a.5.5 0 0 1 .438.33l.47 1.409a.5.5 0 0 1-.462.654l-.015-.002a.5.5 0 0 1-.484-.324l-.15-.452a.5.5 0 0 1-.448-.362l-.188-.564L9.05 9.5z"/>
            </svg>
        </div>
        <h4 class="title">ចុះវត្តមានសិស្ស (Student Check-In)</h4>
        <p class="subtitle" id="class-subtitle">
            <?php if ($is_valid_qr): ?>
                ថ្នាក់រៀន៖ <strong><?= htmlspecialchars($class_name) ?></strong> | ថ្ងៃទី៖ <strong><?= htmlspecialchars($date) ?></strong>
            <?php else: ?>
                តំណភ្ជាប់មិនត្រឹមត្រូវ
            <?php endif; ?>
        </p>
    </div>

    <?php if (!$is_valid_qr): ?>
        <!-- Invalid Link View -->
        <div class="state-view">
            <div class="state-icon error">⚠️</div>
            <h5 class="fw-bold mb-2">តំណភ្ជាប់ស្កេនមិនត្រឹមត្រូវ</h5>
            <p class="text-secondary small mb-0">សូមទាក់ទងលោកគ្រូ/អ្នកគ្រូ ដើម្បីសុំ QR Code វត្តមានជាថ្មី។</p>
        </div>
    <?php elseif ($is_expired): ?>
        <!-- Expired Link View -->
        <div class="state-view">
            <div class="state-icon error" style="color: var(--danger);">⏰</div>
            <h5 class="fw-bold mb-2">កូដ QR នេះបានហួសកំណត់ហើយ</h5>
            <p class="text-secondary small mb-0">សូមទាក់ទងលោកគ្រូ/អ្នកគ្រូ ដើម្បីសុំកូដ QR វត្តមានជាថ្មី។</p>
        </div>
    <?php elseif ($already_checked_in): ?>
        <!-- Device Already Checked In View -->
        <div class="state-view">
            <div class="state-icon success">🎉</div>
            <h5 class="fw-bold mb-2 text-success">កត់វត្តមានរួចរាល់ហើយ!</h5>
            <p class="text-secondary small mb-0">ទូរសព្ទរបស់អ្នកត្រូវបានកត់វត្តមានសម្រាប់ថ្ងៃនេះរួចរាល់ហើយ។ មិនអាចកត់ម្តងទៀតបានទេ។</p>
        </div>
    <?php else: ?>
        <!-- Main Form & Check-in View -->
        <div id="checkin-content-area">
            <!-- GPS Status -->
            <div id="gps-status" class="gps-status-box pending">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span id="gps-status-text">កំពុងចាប់យកកូអរដោនេ GPS របស់អ្នក...</span>
            </div>

            <!-- Countdown Timer for Student Scan -->
            <?php if ($expires > 0): ?>
                <div id="student-countdown" class="alert-box alert-warning text-center mb-3" style="background-color: rgba(245, 158, 11, 0.05); color: var(--warning); border: 1px dashed rgba(245, 158, 11, 0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history me-1 align-middle" viewBox="0 0 16 16">
                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654.5.5 0 0 1-.168.386zm1.22 1.22A7 7 0 0 0 14 4.5h1a8 8 0 0 1-.56 2.057.5.5 0 0 1-.343-.223zm.45 2.004c.086.383.143.76.17 1.126l-.994.088c-.024-.325-.075-.644-.15-.985zm.022 2.057a7 7 0 0 0-.022-1.026l.994-.088c.03.4.045.803.045 1.21zm-.45 2.004a7 7 0 0 0-.299-.985l.976-.219c.142.366.256.743.342 1.126zm-.71 1.37a7 7 0 0 0-.27-.439l.87-.493c.31.52.56 1.077.747 1.666a.5.5 0 0 1-.386-.168zm-1.22 1.22a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654.5.5 0 0 1-.168.386zm-2.004.45c-.383.086-.76.143-1.126.17l-.088-.994c.325-.024.644-.075.985-.15Z"/>
                        <path d="M8 4.466V8h3.5"/>
                    </svg>
                    ពេលវេលានៅសល់៖ <strong id="student-timer-text">--:--</strong>
                </div>
            <?php endif; ?>

            <div id="form-alert" class="alert-box alert-danger d-none"></div>

            <form id="checkin-form" class="d-none">
                <div class="input-group-custom">
                    <label for="student_id" class="form-label">អត្តសញ្ញាណប័ណ្ណសិស្ស (Student ID)</label>
                    <input type="number" id="student_id" name="student_id" class="form-control" placeholder="បញ្ចូលលេខ ID របស់អ្នក ឧ. 1005" required>
                    <div id="name-preview" class="name-preview-box"></div>
                </div>

                <button type="submit" id="submit-btn" class="btn-submit" disabled>
                    <span>កត់វត្តមាន (Check In)</span>
                </button>
            </form>
        </div>

        <!-- Success Result View (Hidden Initially) -->
        <div id="success-view" class="state-view d-none">
            <div class="state-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </div>
            <h5 class="fw-bold mb-2 text-success" id="success-title">ចុះវត្តមានទទួលបានជោគជ័យ!</h5>
            <p class="text-secondary small mb-0" id="success-msg">វត្តមានរបស់អ្នកត្រូវបានកត់ចូលក្នុងប្រព័ន្ធរួចរាល់ហើយ។</p>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_valid_qr && !$already_checked_in): ?>
<script>
$(document).ready(function() {
    // List of students in this class for fast client-side autocomplete lookup
    const studentsInClass = <?= json_encode($students_in_class) ?>;
    
    let userLatitude = null;
    let userLongitude = null;
    let gpsLocked = false;
    let studentMatched = false;
    let matchedStudentId = null;

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth radius in meters
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // Distance in meters
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    // Start fetching GPS coordinates on load
    function getGPSLocation() {
        if (!navigator.geolocation) {
            updateGPSBox('error', 'កម្មវិធីរុករកមិនគាំទ្រការចាប់យក GPS ឡើយ!');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                
                // Calculate distance to school client-side
                const schoolLat = parseFloat("<?= $school_lat ?>");
                const schoolLng = parseFloat("<?= $school_lng ?>");
                const allowedRadius = parseFloat("<?= $allowed_radius ?>");
                
                const distance = calculateDistance(userLatitude, userLongitude, schoolLat, schoolLng);
                
                if (distance > allowedRadius) {
                    gpsLocked = false;
                    $('#checkin-form').addClass('d-none');
                    updateGPSBox('error', 'រកមិនឃើញទីតាំងថ្នាក់រៀនទេ (Classroom not found)។ សូមចូលក្នុងថ្នាក់រៀនដើម្បីស្កេន!');
                } else {
                    gpsLocked = true;
                    $('#checkin-form').removeClass('d-none');
                    updateGPSBox('success', 'GPS Locked');
                    validateForm();
                }
            },
            function(error) {
                let msg = 'មិនអាចចាប់យកទីតាំង GPS បានឡើយ៖ ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        msg += 'សូមអនុញ្ញាតសិទ្ធិចូលប្រើទីតាំង (Location permission denied)។';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        msg += 'មិនមានព័ត៌មានទីតាំង។';
                        break;
                    case error.TIMEOUT:
                        msg += 'សំណើស្វែងរកទីតាំងហួសពេលកំណត់។';
                        break;
                    default:
                        msg += 'កំហុសមិនស្គាល់។';
                }
                gpsLocked = false;
                $('#checkin-form').addClass('d-none');
                updateGPSBox('error', msg);
            },
            { enableHighAccuracy: true, timeout: 15000 }
        );
    }

    function updateGPSBox(status, message) {
        const box = $('#gps-status');
        box.removeClass('pending success error d-none').addClass(status);
        
        if (status === 'success') {
            box.addClass('d-none');
            return;
        }
        
        let iconHtml = '';
        if (status === 'pending') {
            iconHtml = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
        } else if (status === 'error') {
            iconHtml = '⚠️ ';
        }
        
        box.html(iconHtml + '<span id="gps-status-text">' + message + '</span>');
    }

    // Live search student ID as user types
    $('#student_id').on('input', function() {
        const idInput = $(this).val().trim();
        const preview = $('#name-preview');
        
        studentMatched = false;
        matchedStudentId = null;

        if (idInput === '') {
            preview.removeClass('match no-match').text('');
            validateForm();
            return;
        }

        // Search in class students list
        const match = studentsInClass.find(s => parseInt(s.id) === parseInt(idInput));

        if (match) {
            studentMatched = true;
            matchedStudentId = match.id;
            const genderKhmer = match.gender === 'Male' ? 'ប្រុស' : (match.gender === 'Female' ? 'ស្រី' : '');
            const genderText = genderKhmer ? ` | ភេទ៖ ${genderKhmer}` : '';
            const latinText = match.name_latin ? ` (${match.name_latin})` : '';
            preview.removeClass('no-match').addClass('match').text('ឈ្មោះ៖ ' + match.name + latinText + genderText);
        } else {
            preview.removeClass('match').addClass('no-match').text('រកមិនឃើញសិស្សម្នាក់នេះនៅក្នុងថ្នាក់រៀននេះទេ!');
        }
        
        validateForm();
    });

    function validateForm() {
        const submitBtn = $('#submit-btn');
        if (gpsLocked && studentMatched) {
            submitBtn.prop('disabled', false);
        } else {
            submitBtn.prop('disabled', true);
        }
    }

    // Handle form submit
    $('#checkin-form').submit(function(e) {
        e.preventDefault();

        const submitBtn = $('#submit-btn');
        const alertBox = $('#form-alert');

        if (!gpsLocked || !studentMatched) return;

        submitBtn.prop('disabled', true).html('<span>កំពុងកត់វត្តមាន...</span>');
        alertBox.addClass('d-none');

        $.ajax({
            url: 'ajax-api.php?action=student_qr_scan',
            method: 'POST',
            data: {
                student_id: matchedStudentId,
                class_id: <?= $class_id ?>,
                date: '<?= $date ?>',
                lat: userLatitude,
                lng: userLongitude,
                expires: '<?= $expires ?>'
            },
            dataType: 'json',
            error: function(err) {
                console.error(err);
                alertBox.removeClass('d-none').text('មានកំហុសក្នុងការតភ្ជាប់ទៅកាន់ Server!');
                submitBtn.prop('disabled', false).html('<span>កត់វត្តមាន (Check In)</span>');
            },
            success: function(resp) {
                if (resp.status === 'success' || resp.status === 'info') {
                    // Show success screen
                    $('#checkin-content-area').addClass('d-none');
                    if (resp.status === 'info') {
                        $('#success-title').text('វត្តមានត្រូវបានកត់រួចរាល់ហើយ!');
                        $('#success-title').removeClass('text-success').addClass('text-info');
                    }
                    $('#success-msg').text(resp.msg);
                    $('#success-view').removeClass('d-none');
                } else {
                    alertBox.removeClass('d-none').text(resp.msg || 'ការចុះវត្តមានបានបរាជ័យ!');
                    submitBtn.prop('disabled', false).html('<span>កត់វត្តមាន (Check In)</span>');
                }
            }
        });
    });

    <?php if ($expires > 0): ?>
        var serverTime = <?= $server_time ?>;
        var expiresTime = <?= $expires ?>;
        var localTimeOffset = Date.now() - serverTime;

        function updateStudentTimer() {
            var now = Date.now() - localTimeOffset;
            var diff = expiresTime - now;
            if (diff <= 0) {
                clearInterval(studentTimerInterval);
                $('#checkin-content-area').html(`
                    <div class="state-view">
                        <div class="state-icon error" style="color: var(--danger);">⏰</div>
                        <h5 class="fw-bold mb-2">កូដ QR នេះបានហួសកំណត់ហើយ</h5>
                        <p class="text-secondary small mb-0">សូមទាក់ទងលោកគ្រូ/អ្នកគ្រូ ដើម្បីសុំកូដ QR វត្តមានជាថ្មី។</p>
                    </div>
                `);
            } else {
                var seconds = Math.floor(diff / 1000);
                var minutes = Math.floor(seconds / 60);
                seconds = seconds % 60;
                var minStr = minutes < 10 ? '0' + minutes : minutes;
                var secStr = seconds < 10 ? '0' + seconds : seconds;
                $('#student-timer-text').text(minStr + ':' + secStr);
            }
        }
        
        updateStudentTimer();
        var studentTimerInterval = setInterval(updateStudentTimer, 1000);
    <?php endif; ?>

    // Run GPS lookup
    getGPSLocation();
});
</script>
<?php endif; ?>

</body>
</html>