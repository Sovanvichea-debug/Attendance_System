<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                </svg>
            </div>
            <h2 class="login-title">ប្រព័ន្ធគ្រប់គ្រងវត្តមាន</h2>
            <p class="login-subtitle">សូមបញ្ចូលគណនីគ្រូបង្រៀនដើម្បីចូលទៅកាន់ប្រព័ន្ធ</p>
        </div>

        <div id="login-alert" class="alert alert-danger d-none" role="alert">
            <span id="login-alert-msg"></span>
        </div>

        <form id="login-form" novalidate>
            <div class="form-group-custom mb-3">
                <label for="username" class="form-label-custom">ឈ្មោះអ្នកប្រើប្រាស់ (Username)</label>
                <div class="input-wrapper-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" id="username" name="username" class="form-control-custom" placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់" required autocomplete="username">
                </div>
            </div>

            <div class="form-group-custom mb-4">
                <label for="password" class="form-label-custom">លេខសម្ងាត់ (Password)</label>
                <div class="input-wrapper-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password" name="password" class="form-control-custom" placeholder="បញ្ចូលលេខសម្ងាត់" required autocomplete="current-password">
                    <button type="button" id="toggle-password" class="btn-toggle-password" aria-label="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit-custom w-100">
                <span class="btn-text">ចូលប្រើប្រាស់ប្រព័ន្ធ</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </form>

        <div class="helper-box mt-4">
            <div class="helper-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                គណនីសាកល្បងលំនាំដើម (សាកល្បងចុចលើគណនីដើម្បីបំពេញ)៖
            </div>
            <div class="helper-credentials mt-2" id="demo-account" style="cursor: pointer;">
                <div>Username: <strong class="text-primary text-decoration-underline" id="demo-username">teacher</strong></div>
                <div>Password: <strong class="text-primary text-decoration-underline" id="demo-password">teacher123</strong></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Autofill demo account on click
    $('#demo-account').click(function() {
        $('#username').val($('#demo-username').text());
        $('#password').val($('#demo-password').text());
        $('#username').focus();
        
        // Add subtle animation scale effect to input
        $('.input-wrapper-custom').addClass('glow-effect');
        setTimeout(function() {
            $('.input-wrapper-custom').removeClass('glow-effect');
        }, 1000);
    });

    // Toggle Password Visibility
    $('#toggle-password').click(function() {
        const passwordField = $('#password');
        const passwordType = passwordField.attr('type');
        const eyeIcon = $(this).find('svg');
        
        if (passwordType === 'password') {
            passwordField.attr('type', 'text');
            eyeIcon.html('<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>');
        } else {
            passwordField.attr('type', 'password');
            eyeIcon.html('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>');
        }
    });

    // Handle form submit
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('.btn-submit-custom');
        const btnText = submitBtn.find('.btn-text');
        const spinner = submitBtn.find('.spinner-border');
        const alertBox = $('#login-alert');
        const alertMsg = $('#login-alert-msg');

        // Reset state
        alertBox.addClass('d-none');
        
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();

        if(!username || !password) {
            alertMsg.text('សូមបំពេញឈ្មោះអ្នកប្រើប្រាស់ និងលេខសម្ងាត់!');
            alertBox.removeClass('d-none');
            return false;
        }

        // Show spinner, disable button
        submitBtn.prop('disabled', true);
        btnText.addClass('opacity-50');
        spinner.removeClass('d-none');

        $.ajax({
            url: 'ajax-api.php?action=login',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            error: function(err) {
                console.error(err);
                alertMsg.text('មានបញ្ហាក្នុងការតភ្ជាប់ទៅកាន់ម៉ាស៊ីនបម្រើ (Server Error)។ សូមព្យាយាមម្ដងទៀត!');
                alertBox.removeClass('d-none');
                
                // Hide spinner
                submitBtn.prop('disabled', false);
                btnText.removeClass('opacity-50');
                spinner.addClass('d-none');
            },
            success: function(resp) {
                if (resp.status === 'success') {
                    // Redirect to home page
                    window.location.href = './';
                } else {
                    // Show error message
                    alertMsg.text(resp.msg || 'ឈ្មោះអ្នកប្រើប្រាស់ ឬលេខសម្ងាត់ មិនត្រឹមត្រូវទេ!');
                    alertBox.removeClass('d-none');
                    
                    // Hide spinner
                    submitBtn.prop('disabled', false);
                    btnText.removeClass('opacity-50');
                    spinner.addClass('d-none');
                }
            }
        });
    });
});
</script>
