<?php
// Ensure session details are refreshed
$user = $actionClass->get_user();
?>

<div class="row mt-4">
    <div class="col-12 mb-3">
        <h2 class="welcome-title text-dark-emphasis d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>ព័ត៌មានផ្ទាល់ខ្លួនរបស់គ្រូ (Teacher Profile)</span>
        </h2>
        <p class="text-muted">គ្រប់គ្រងគណនី និងប្ដូរលេខសម្ងាត់របស់អ្នកនៅទីនេះ។</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Profile View & Modify Info -->
    <div class="col-md-6 col-12">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center gap-2">
                <div class="p-2 bg-primary-subtle text-primary rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h5 class="mb-0 fw-bold text-dark-emphasis">គណនីគ្រូ</h5>
            </div>
            <div class="card-body px-4 pb-4 pt-3">
                <form id="profile-form" enctype="multipart/form-data">
                    <!-- Avatar Upload & Preview -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="profile-avatar-container mx-auto">
                                <?php if(!empty($user['avatar']) && file_exists(__DIR__.'/../'.$user['avatar'])): ?>
                                    <img id="avatar-preview" src="<?= $user['avatar'] ?>" alt="Profile Picture" class="profile-avatar-img">
                                <?php else: ?>
                                    <!-- Fallback Avatar -->
                                    <div id="avatar-placeholder" class="profile-avatar-initial">
                                        <?= mb_substr($user['fullname'] ?? 'U', 0, 1, 'UTF-8') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <label for="avatar-input" class="avatar-upload-btn" title="ប្ដូររូបថត">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </label>
                            <input type="file" id="avatar-input" name="avatar" accept="image/*" class="d-none">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="p-username" class="form-label">ឈ្មោះអ្នកប្រើប្រាស់ (Username)</label>
                        <input type="text" id="p-username" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                        <div class="form-text">ឈ្មោះអ្នកប្រើប្រាស់មិនអាចកែប្រែបានឡើយ។</div>
                    </div>

                    <div class="mb-3">
                        <label for="p-fullname" class="form-label">ឈ្មោះពេញ (Fullname) <span class="text-danger">*</span></label>
                        <input type="text" id="p-fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="p-role" class="form-label">តួនាទី (Role)</label>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-2 px-3 d-inline-block rounded-pill text-capitalize fw-bold"><?= htmlspecialchars($user['role']) ?></span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted d-block">ថ្ងៃបង្កើតគណនី៖</label>
                        <span class="text-dark fw-semibold"><?= date("d M, Y H:i", strtotime($user['created_at'])) ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>រក្សាទុកការកែប្រែ</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Change Password -->
    <div class="col-md-6 col-12">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center gap-2">
                <div class="p-2 bg-danger-subtle text-danger rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h5 class="mb-0 fw-bold text-dark-emphasis">ប្ដូរលេខសម្ងាត់ថ្មី (Change Password)</h5>
            </div>
            <div class="card-body px-4 pb-4 pt-3">
                <div id="password-alert" class="alert alert-danger d-none" role="alert">
                    <span id="password-alert-msg"></span>
                </div>
                <form id="password-form">
                    <!-- Hidden field to supply fullname as well since update_profile() extract($_POST) uses it -->
                    <input type="hidden" name="fullname" id="hidden-fullname" value="<?= htmlspecialchars($user['fullname']) ?>">

                    <div class="mb-3">
                        <label for="old_password" class="form-label">លេខសម្ងាត់ចាស់ (Old Password)</label>
                        <input type="password" id="old_password" name="old_password" class="form-control" placeholder="បញ្ចូលលេខសម្ងាត់ចាស់">
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">លេខសម្ងាត់ថ្មី (New Password)</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="បញ្ចូលលេខសម្ងាត់ថ្មី">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">បញ្ជាក់លេខសម្ងាត់ថ្មី (Confirm New Password)</label>
                        <input type="password" id="confirm_password" class="form-control" placeholder="បញ្ចូលលេខសម្ងាត់ថ្មីម្ដងទៀត">
                    </div>

                    <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
                        <span>ធ្វើបច្ចុប្បន្នភាពលេខសម្ងាត់</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Keep hidden fullname in password form in sync
    $('#p-fullname').on('input', function() {
        $('#hidden-fullname').val($(this).val());
    });

    // Instant image preview
    $('#avatar-input').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // If preview image exists, update it. Otherwise create it and remove initials placeholder.
                if ($('#avatar-preview').length > 0) {
                    $('#avatar-preview').attr('src', e.target.result);
                } else {
                    $('.profile-avatar-container').html(`<img id="avatar-preview" src="${e.target.result}" alt="Profile Picture" class="profile-avatar-img">`);
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Profile details form submit
    $('#profile-form').submit(function(e) {
        e.preventDefault();
        start_loader();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: 'ajax-api.php?action=update_profile',
            method: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            error: function(err) {
                console.error(err);
                end_loader();
                alert('មានបញ្ហាក្នុងការរក្សាទុកទិន្នន័យ។ សូមព្យាយាមម្ដងទៀត!');
            },
            success: function(resp) {
                end_loader();
                if(resp.status === 'success') {
                    location.reload();
                } else {
                    alert(resp.msg || 'មានកំហុសក្នុងការរក្សាទុកទិន្នន័យ!');
                }
            }
        });
    });

    // Password form submit
    $('#password-form').submit(function(e) {
        e.preventDefault();
        const oldPass = $('#old_password').val().trim();
        const newPass = $('#new_password').val().trim();
        const confirmPass = $('#confirm_password').val().trim();
        const alertBox = $('#password-alert');
        const alertMsg = $('#password-alert-msg');

        alertBox.addClass('d-none');

        if(!oldPass || !newPass || !confirmPass) {
            alertMsg.text('សូមបំពេញចន្លោះលេខសម្ងាត់ទាំងអស់!');
            alertBox.removeClass('d-none');
            return false;
        }

        if(newPass !== confirmPass) {
            alertMsg.text('លេខសម្ងាត់ថ្មីទាំងពីរមិនដូចគ្នាទេ!');
            alertBox.removeClass('d-none');
            return false;
        }

        if(newPass.length < 6) {
            alertMsg.text('លេខសម្ងាត់ថ្មីត្រូវតែមានយ៉ាងតិច ៦ ខ្ទង់!');
            alertBox.removeClass('d-none');
            return false;
        }

        start_loader();
        $.ajax({
            url: 'ajax-api.php?action=update_profile',
            method: 'POST',
            data: $('#password-form').serialize(),
            dataType: 'json',
            error: function(err) {
                console.error(err);
                end_loader();
                alertMsg.text('មានបញ្ហាពេលរក្សាទុកលេខសម្ងាត់ថ្មី!');
                alertBox.removeClass('d-none');
            },
            success: function(resp) {
                end_loader();
                if(resp.status === 'success') {
                    // Success, reload page to show flash message
                    location.reload();
                } else {
                    alertMsg.text(resp.msg || 'លេខសម្ងាត់ចាស់មិនត្រឹមត្រូវឡើយ!');
                    alertBox.removeClass('d-none');
                }
            }
        });
    });
});
</script>
