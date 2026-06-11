<?php
$settings = $actionClass->get_settings();
?>
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
    <div class="page-title">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
        </svg>
        <span class="align-middle">ការកំណត់ទីតាំងសាលា (School Geofencing Settings)</span>
    </div>
</div>
<hr>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-1">ព័ត៌មានទីតាំងភូមិសាស្ត្រសាលា</h5>
                <p class="text-muted small mb-0">កំណត់កូអរដោនេ GPS របស់សាលា និងចម្ងាយកំណត់ដែលអនុញ្ញាតឱ្យសិស្សស្កេនចុះវត្តមានបាន។</p>
            </div>
            <div class="card-body p-4">
                <div id="settings-alert" class="alert d-none" role="alert"></div>

                <form id="settings-form" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label for="school_lat" class="form-label">រយៈទទឹង (Latitude) <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" id="school_lat" name="school_lat" 
                                   value="<?= htmlspecialchars($settings['school_lat'] ?? '') ?>" placeholder="ဥទាហរណ៍៖ 11.5564" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="school_lng" class="form-label">រយៈបណ្តោយ (Longitude) <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" id="school_lng" name="school_lng" 
                                   value="<?= htmlspecialchars($settings['school_lng'] ?? '') ?>" placeholder="ឧទាហរណ៍៖ 104.9282" required>
                        </div>
                        <div class="col-12">
                            <label for="allowed_radius" class="form-label">ចម្ងាយកំណត់អនុញ្ញាត (Allowed Radius - ម៉ែត្រ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="allowed_radius" name="allowed_radius" 
                                   value="<?= htmlspecialchars($settings['allowed_radius'] ?? '') ?>" placeholder="ចម្ងាយជាម៉ែត្រ ឧទាហរណ៍៖ 100" required>
                        </div>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-between">
                        <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2" id="get-gps-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.9c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.9zM8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                            </svg>
                            <span>ចាប់យកទីតាំងបច្ចុប្បន្នរបស់ខ្ញុំ</span>
                        </button>
                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2" id="save-settings-btn">
                            <span>រក្សាទុកការកំណត់</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Get Device Current Location
    $('#get-gps-btn').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        
        if (!navigator.geolocation) {
            showAlert('danger', 'កម្មវិធីរុករក (Browser) របស់អ្នកមិនគាំទ្រការចាប់យកទីតាំង GPS ទេ!');
            return;
        }

        btn.prop('disabled', true).html('<span>កំពុងស្វែងរកទីតាំង...</span>');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                $('#school_lat').val(position.coords.latitude.toFixed(6));
                $('#school_lng').val(position.coords.longitude.toFixed(6));
                showAlert('success', 'ចាប់យកកូអរដោនេបច្ចុប្បន្នបានជោគជ័យ!');
                btn.prop('disabled', false).html(originalText);
            },
            function(error) {
                let msg = 'មិនអាចចាប់យកទីតាំងរបស់អ្នកបានទេ៖ ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        msg += 'អ្នកបានបដិសេធសិទ្ធិចូលប្រើទីតាំង (Location permission denied)។';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        msg += 'ព័ត៌មានទីតាំងមិនមាន (Position unavailable)។';
                        break;
                    case error.TIMEOUT:
                        msg += 'សំណើស្វែងរកទីតាំងហួសពេលកំណត់ (Timeout)។';
                        break;
                    default:
                        msg += 'កំហុសមិនស្គាល់។';
                }
                showAlert('danger', msg);
                btn.prop('disabled', false).html(originalText);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    // Form submission
    $('#settings-form').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#save-settings-btn');
        const alertBox = $('#settings-alert');
        
        const schoolLat = $('#school_lat').val().trim();
        const schoolLng = $('#school_lng').val().trim();
        const allowedRadius = $('#allowed_radius').val().trim();

        if (!schoolLat || !schoolLng || !allowedRadius) {
            showAlert('danger', 'សូមបំពេញព័ត៌មានដែលចាំបាច់ទាំងអស់!');
            return;
        }

        submitBtn.prop('disabled', true).text('កំពុងរក្សាទុក...');
        alertBox.addClass('d-none');

        $.ajax({
            url: 'ajax-api.php?action=save_settings',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            error: function(err) {
                console.error(err);
                showAlert('danger', 'មានកំហុសក្នុងការរក្សាទុកទិន្នន័យ!');
                submitBtn.prop('disabled', false).text('រក្សាទុកការកំណត់');
            },
            success: function(resp) {
                submitBtn.prop('disabled', false).text('រក្សាទុកការកំណត់');
                if (resp.status === 'success') {
                    showAlert('success', 'ការកំណត់ទីតាំងសាលាត្រូវបានរក្សាទុកដោយជោគជ័យ!');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', resp.msg || 'ការរក្សាទុកការកំណត់បានបរាជ័យ!');
                }
            }
        });
    });

    function showAlert(type, message) {
        const alertBox = $('#settings-alert');
        alertBox.removeClass('alert-danger alert-success d-none')
                .addClass('alert-' + type)
                .text(message);
    }
});
</script>