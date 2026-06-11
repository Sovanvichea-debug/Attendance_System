<?php
session_start();
require_once(realpath(__DIR__.'/../classes/actions.class.php'));
$actionClass = new Actions();
$classList = $actionClass->list_class();
$selected_class_id = $_POST['class_id'] ?? '';
?>
<div class="container-fluid">
    <form id="import-student-form" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="import_class_id" class="form-label mb-0">ជ្រើសរើសថ្នាក់រៀន (Select Class) <span class="text-danger">*</span></label>
                        <a href="javascript:void(0)" id="import-toggle-new-class" class="text-primary small text-decoration-none" style="font-size: 0.8rem; font-weight: 500;">+ បង្កើតថ្នាក់ថ្មី (Create Class)</a>
                    </div>
                    <select class="form-select" id="import_class_id" name="class_id" required="required">
                        <option value="" <?= empty($selected_class_id) ? "selected" : "" ?> disabled> -- ជ្រើសរើសថ្នាក់រៀន -- </option>
                        <?php if(!empty($classList) && is_array($classList)): ?>
                            <?php foreach($classList as $row): ?>
                                <option value="<?= $row['id'] ?>" <?= ($selected_class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <!-- Quick Class Creator Widget -->
                    <div id="import-quick-class-section" class="mt-2 p-2 border rounded bg-light d-none">
                        <label for="import_new_class_name" class="form-label small fw-bold text-dark mb-1" style="font-size: 0.75rem;">ឈ្មោះថ្នាក់រៀន និងមុខវិជ្ជាថ្មី (New Class & Subject)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="import_new_class_name" class="form-control" placeholder="ឧ. MMO - HTML5">
                            <button type="button" id="import-save-quick-class" class="btn btn-primary btn-sm px-3">រក្សាទុក (Save)</button>
                        </div>
                        <div id="import-quick-class-msg" class="small mt-1 d-none"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="excel_file" class="form-label">ជ្រើសរើសឯកសារ Excel ឬ CSV (Select Excel or CSV File) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls, .csv" required="required">
                </div>
                
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <h6 class="fw-bold text-dark mb-2">ទម្រង់ឯកសារគំរូ Excel / CSV (Excel / CSV Format Guidelines)</h6>
                    <p class="text-secondary small mb-2">ឯកសារត្រូវមានជួរឈរ (Columns) តាមលំដាប់លំដោយដូចខាងក្រោម៖</p>
                    <table class="table table-bordered table-sm text-center bg-white mb-2" style="font-size: 0.8rem;">
                        <thead>
                            <tr class="table-light">
                                <th>Name (ឈ្មោះ)</th>
                                <th>Name Latin (ឡាតាំង)</th>
                                <th>Gender (ភេទ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ជា ពេញ</td>
                                <td>CHEA PENH</td>
                                <td>Male (ឬ ប្រុស)</td>
                            </tr>
                            <tr>
                                <td>សាន ណារី</td>
                                <td>SAN NARY</td>
                                <td>Female (ឬ ស្រី)</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <a href="javascript:void(0)" id="download-template-btn" class="btn btn-sm btn-link text-primary p-0 text-decoration-none">📥 ទាញយកគំរូឯកសារ Excel (Download Excel Template)</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Load SheetJS for Excel Template Generation Client-side -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    // Toggle Quick Class Creator
    $('#import-toggle-new-class').click(function(e) {
        e.preventDefault();
        var section = $('#import-quick-class-section');
        section.toggleClass('d-none');
        if(!section.hasClass('d-none')) {
            $('#import_new_class_name').focus();
        } else {
            $('#import_new_class_name').val('');
            $('#import-quick-class-msg').addClass('d-none').text('').removeClass('text-success text-danger text-muted');
        }
    });

    // Handle Enter key on quick class input
    $('#import_new_class_name').keypress(function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $('#import-save-quick-class').click();
        }
    });

    // Save Quick Class
    $('#import-save-quick-class').click(function(e) {
        e.preventDefault();
        var name = $('#import_new_class_name').val().trim();
        var msgDiv = $('#import-quick-class-msg');
        
        if(name === '') {
            msgDiv.removeClass('d-none text-success text-muted').addClass('text-danger').text('សូមបញ្ចូលឈ្មោះថ្នាក់រៀន! (Please enter a class name!)');
            return;
        }
        
        // Disable inputs
        $('#import_new_class_name, #import-save-quick-class').prop('disabled', true);
        msgDiv.removeClass('d-none text-danger text-success').addClass('text-muted').text('កំពុងរក្សាទុក... (Saving...)');
        
        $.ajax({
            url: "ajax-api.php?action=save_class",
            method: "POST",
            data: { name: name, quick_save: 1 },
            dataType: 'JSON',
            error: (err) => {
                console.warn(err);
                msgDiv.removeClass('text-muted').addClass('text-danger').text('មានកំហុសក្នុងការតភ្ជាប់! (Connection error!)');
                $('#import_new_class_name, #import-save-quick-class').prop('disabled', false);
            },
            success: function(resp) {
                if(resp?.status == 'success') {
                    var newId = resp.id;
                    // Add to select dropdown and select it
                    var newOption = new Option(name, newId, true, true);
                    $('#import_class_id').append(newOption).trigger('change');
                    
                    // Clear and hide section
                    $('#import_new_class_name').val('');
                    $('#import-quick-class-section').addClass('d-none');
                    msgDiv.addClass('d-none').text('');
                } else {
                    var errorMsg = resp?.msg || 'មានកំហុសក្នុងការរក្សាទុក! (Save error!)';
                    if(errorMsg === 'Class Name Already Exists!') {
                        errorMsg = 'ឈ្មោះថ្នាក់នេះមានរួចហើយ! (Class name already exists!)';
                    }
                    msgDiv.removeClass('text-muted').addClass('text-danger').text(errorMsg);
                }
                $('#import_new_class_name, #import-save-quick-class').prop('disabled', false);
            }
        });
    });

    // Generate and download Excel template client-side using SheetJS
    $('#download-template-btn').click(function(e) {
        e.preventDefault();
        
        if (typeof XLSX === 'undefined') {
            alert('កំហុស៖ មិនអាចទាញយកបណ្ណាល័យ XLSX សម្រាប់បង្កើតឯកសារគំរូបានទេ!');
            return;
        }

        const data = [
            ["Name", "Name Latin", "Gender"],
            ["ជា ពេញ", "CHEA PENH", "Male"],
            ["សាន ណារី", "SAN NARY", "Female"]
        ];
        
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, "Template");
        XLSX.writeFile(wb, "student_import_template.xlsx");
    });

    $('#import-student-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        
        // Use FormData to send file uploads
        var formData = new FormData(this);
        
        start_loader();
        $(uniModal).find('.flashdata').remove();
        
        var flashData = $('<div>').addClass('flashdata mb-3').html(`
            <div class="d-flex w-100 align-items-center flex-wrap">
                <div class="col-11 flashdata-msg"></div>
                <div class="col-1 text-center">
                    <a href="javascript:void(0)" onclick="this.closest('.flashdata').remove()" class="flashdata-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="align-middle"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>
                </div>
            </div>
        `);

        $.ajax({
            url: "ajax-api.php?action=import_students",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            error: function(err) {
                console.error(err);
                flashData.find('.flashdata-msg').text(`មានកំហុសក្នុងការតភ្ជាប់ ឬឯកសារធំពេក!`);
                flashData.addClass('flashdata-danger');
                _this.prepend(flashData);
                end_loader();
            },
            success: function(resp) {
                if (resp?.status === 'success') {
                    location.reload();
                } else {
                    flashData.find('.flashdata-msg').text(resp?.msg || `មានកំហុសក្នុងការនាំចូលសិស្ស!`);
                    flashData.addClass('flashdata-danger');
                    _this.prepend(flashData);
                    end_loader();
                }
            }
        });
    });
});
</script>
