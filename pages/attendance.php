<div class="page-title mb-3">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="9 16 11 18 15 14"></polyline></svg>
    <span class="align-middle">Manage Attendance</span>
</div>
<hr>
<?php 
$classList = $actionClass->list_class();
$class_id = $_GET['class_id'] ?? "";
$class_date = $_GET['class_date'] ?? "";
$studentList = $actionClass->attendanceStudents($class_id, $class_date);

if (!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(" ", $name);
        $initials = "";
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_substr($w, 0, 1, 'UTF-8');
            }
        }
        return mb_substr($initials, 0, 2, 'UTF-8');
    }
}
?>

<form action="" id="manage-attendance">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div id="msg"></div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row align-items-end g-3">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label for="class_id" class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted me-1"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                                    Select Class
                                </label>
                                <select name="class_id" id="class_id" class="form-select" required="required">
                                    <option value="" disabled <?= empty($class_id) ? "selected" : "" ?>> -- Select Here -- </option>
                                    <?php if(!empty($classList) && is_array($classList)): ?>
                                    <?php foreach($classList as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= (isset($class_id) && $class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label for="class_date" class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted me-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    Date
                                </label>
                                <input type="date" name="class_date" id="class_date" class="form-control" value="<?= $class_date ?? '' ?>" required="required">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($class_id) && !empty($class_date)): ?>
            <div class="card mb-4">
                <div class="card-header bg-transparent py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="h5 mb-0 fw-bold text-dark me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                                <span class="align-middle">Attendance Sheet</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 py-1 px-2" id="show-qr-btn" style="border-radius: 6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <rect x="7" y="7" width="3" height="3"></rect>
                                    <rect x="14" y="7" width="3" height="3"></rect>
                                    <rect x="7" y="14" width="3" height="3"></rect>
                                    <rect x="14" y="14" width="3" height="3"></rect>
                                </svg>
                                បង្ហាញកូដ QR (Show QR)
                            </button>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-6 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </span>
                                <input type="text" id="search-student" class="form-control border-start-0" placeholder="ស្វែងរកឈ្មោះសិស្ស (Search)...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive border-0">
                        <table id="attendance-tbl" class="table table-hovered">
                            <colgroup>
                                <col width="8%">
                                <col width="32%">
                                <col width="15%">
                                <col width="15%">
                                <col width="15%">
                                <col width="15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Students</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Excused</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-light-subtle">
                                    <td class="text-center fw-bold text-muted">-</td>
                                    <td class="fw-bold text-muted ps-4">Check/Uncheck All</td>
                                    <td class="text-center">
                                        <div class="checkall-badge-wrapper">
                                            <label class="checkall-badge option-present" for="PCheckAll" title="Mark All Present">
                                                <input class="checkAll" type="checkbox" id="PCheckAll">
                                                <span>ALL</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkall-badge-wrapper">
                                            <label class="checkall-badge option-late" for="LCheckAll" title="Mark All Late">
                                                <input class="checkAll" type="checkbox" id="LCheckAll">
                                                <span>ALL</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkall-badge-wrapper">
                                            <label class="checkall-badge option-absent" for="ACheckAll" title="Mark All Absent">
                                                <input class="checkAll" type="checkbox" id="ACheckAll">
                                                <span>ALL</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkall-badge-wrapper">
                                            <label class="checkall-badge option-excused" for="ECheckAll" title="Mark All Excused">
                                                <input class="checkAll" type="checkbox" id="ECheckAll">
                                                <span>ALL</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <?php if(!empty($studentList) && is_array($studentList)): ?>
                                <?php foreach($studentList as $row): ?>
                                    <tr class="student-row">
                                        <td class="text-center align-middle fw-bold text-muted"><?= $row['id'] ?></td>
                                        <td class="fw-semibold text-dark align-middle">
                                            <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="student-avatar"><?= getInitials($row['name']) ?></div>
                                                <div class="ms-2">
                                                    <a href="javascript:void(0)" class="view_profile student-name-text text-dark text-decoration-none hover-primary-text fw-bold d-block mb-0" style="line-height: 1.2;" data-id="<?= $row['id'] ?>"><?= $row['name'] ?></a>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <?= htmlspecialchars($row['name_latin'] ?? '') ?>
                                                        <?php 
                                                            $g = $row['gender'] ?? '';
                                                            if($g === 'Male') echo " • ប្រុស";
                                                            elseif($g === 'Female') echo " • ស្រី";
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="attendance-options-group">
                                                <label class="att-option option-present" for="status_p_<?= $row['id'] ?>" title="Present">
                                                    <input class="status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="1" id="status_p_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 1) ? "checked" : "" ?>>
                                                    <span>P</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="attendance-options-group">
                                                <label class="att-option option-late" for="status_l_<?= $row['id'] ?>" title="Late">
                                                    <input class="status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="2" id="status_l_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 2) ? "checked" : "" ?>>
                                                    <span>L</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="attendance-options-group">
                                                <label class="att-option option-absent" for="status_a_<?= $row['id'] ?>" title="Absent">
                                                    <input class="status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="3" id="status_a_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 3) ? "checked" : "" ?>>
                                                    <span>A</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="attendance-options-group">
                                                <label class="att-option option-excused" for="status_e_<?= $row['id'] ?>" title="Excused">
                                                    <input class="status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="4" id="status_e_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 4) ? "checked" : "" ?>>
                                                    <span>E</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                            <div>No students registered in this class.</div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-center align-items-center mb-4">
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <button class="btn btn-primary btn-lg rounded-pill w-100 py-3 shadow d-flex align-items-center justify-content-center gap-2" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Save Attendance
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        checkAll_count()

        // Real-time student search filter
        $('#search-student').on('keyup input', function() {
            var query = $(this).val().toLowerCase();
            $('#attendance-tbl tbody .student-row').each(function() {
                var name = $(this).find('.student-name-text').text().toLowerCase();
                if (name.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#class_id, #class_date').change(function(e){
            var class_id = $('#class_id').val()
            var class_date = $('#class_date').val()
            if (class_id && class_date) {
                location.replace(`./?page=attendance&class_id=${class_id}&class_date=${class_date}`)
            }
        })
        
        $('.status_check').change(function(){
            var student_id = $(this)[0].dataset?.id
            var isChecked = $(this).is(":checked")
            if(isChecked === true){
                $(`.status_check[data-id='${student_id}']`).prop("checked", false)
                $(this).prop("checked", true)
            }
            checkAll_count()
        })
        
        $('.checkAll').change(function(){
            var _this = $(this)
            var isChecked = $(this).is(":checked")
            var id = $(this).attr('id')
            if(isChecked === true){
                $('.checkAll').each(function(){
                    if($(this).attr('id') != id&& $(this).is(":checked") == true){
                        $(this).prop("checked", false)
                    }
                })
                $('.status_check').prop('checked', false)
                if(id == 'PCheckAll'){
                    $('.status_check[value="1"]').prop('checked', true) 
                }else if(id == 'LCheckAll'){
                    $('.status_check[value="2"]').prop('checked', true) 
                }else if(id == 'ACheckAll'){
                    $('.status_check[value="3"]').prop('checked', true) 
                }else if(id == 'ECheckAll'){
                    $('.status_check[value="4"]').prop('checked', true) 
                }
            }else{
                if(id == 'PCheckAll'){
                    $('.status_check[value="1"]').prop('checked', false) 
                }else if(id == 'LCheckAll'){
                    $('.status_check[value="2"]').prop('checked', false) 
                }else if(id == 'ACheckAll'){
                    $('.status_check[value="3"]').prop('checked', false) 
                }else if(id == 'ECheckAll'){
                    $('.status_check[value="4"]').prop('checked', false) 
                }
            }
        })
        
        $('#manage-attendance').submit(function(e){
            e.preventDefault()
            start_loader()
            var _this = $(this)
            var all_marked = true;
            $('#attendance-tbl .student-row').each(function(){
                var has_checks = $(this).find('.status_check:checked').length
                if(has_checks < 1){
                    var name = $(this).find('.view_profile').text() || "";
                        name = String(name).trim();
                    alert(`${name}'s attendance is not yet marked!`);
                    end_loader()
                    all_marked = false;
                    return false;
                }
            })
            if(!all_marked) return false;
            
            $.ajax({
                url:'./ajax-api.php?action=save_attendance',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                error: (err) => {
                    console.error(err)
                    alert("An error occurred while saving the data. kindly reload this page.")
                    end_loader();
                },
                success: function(resp){
                    if(resp?.status == "success"){
                        location.reload()
                    }else if(resp?.status == "error" && resp?.msg != ""){
                        var fd = $(flashdataHTML).clone()
                        fd.addClass('flashdata-danger')
                        fd.find('.flashdata-msg').html(resp.msg)
                        $('#msg').html(fd)
                        $('html, body').scrollTop(0)
                        end_loader();
                    }else{
                        alert("An error occurred while saving the data. kindly reload this page.")
                        end_loader();
                    }
                }
            })
        })

        // Student Profile Modal Trigger
        $('.view_profile').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            open_modal('student_profile.php', 'ប្រវត្តិរូបសិស្ស / Student Profile', {id: id}, 'modal-lg');
        });

        // QR Code Modal Trigger & Live Syncing
        let qrcode_att1 = null;
        let qrcode_att2 = null;
        let live_att_interval = null;

        function fetchLiveAttendance() {
            var class_id = $('#class_id').val();
            var class_date = $('#class_date').val();
            if (!class_id || !class_date) return;

            $.ajax({
                url: `./ajax-api.php?action=get_live_attendance&class_id=${class_id}&class_date=${class_date}`,
                method: 'GET',
                dataType: 'JSON',
                success: function(resp) {
                    if (resp && Array.isArray(resp)) {
                        resp.forEach(function(student) {
                            if (parseInt(student.status) === 1) {
                                // Select Present (1), and uncheck others
                                $(`.status_check[data-id="${student.id}"]`).prop('checked', false);
                                $(`#status_p_${student.id}`).prop('checked', true);
                            }
                        });
                        checkAll_count();
                    }
                }
            });
        }

        let qr_countdown_interval = null;
        let expires_timestamp = 0;

        function generateQRCode(forceReset = false) {
            var class_id = $('#class_id').val();
            var className = $('#class_id option:selected').text();
            var class_date = $('#class_date').val();
            var duration = parseInt($('#qr-duration').val());

            if (!class_id || !class_date) return;

            // Clear previous QRs
            $('#qrcode-attendance').html('');
            $('#qrcode-fs-attendance').html('');

            // Calculate expiration
            var expiresParam = '';
            if (duration > 0) {
                if (forceReset || expires_timestamp <= Date.now()) {
                    expires_timestamp = Date.now() + (duration * 1000);
                }
                expiresParam = `&expires=${expires_timestamp}`;
                
                // Show countdown UI
                $('#qr-countdown-wrapper').removeClass('d-none alert-danger').addClass('alert-warning');
                $('#qr-fs-countdown-wrapper').removeClass('d-none alert-danger').addClass('alert-warning');
                $('#qr-countdown-text').html(`កូដ QR នេះនឹងហួសកំណត់ក្នុងរយៈពេល៖ <strong id="qr-timer">--:--</strong>`);
                $('#qr-fs-countdown-wrapper span').html(`កូដ QR នេះនឹងហួសកំណត់ក្នុងរយៈពេល៖ <strong id="qr-fs-timer" class="fw-bold">--:--</strong>`);
            } else {
                expires_timestamp = 0;
                // Hide countdown UI
                $('#qr-countdown-wrapper').addClass('d-none');
                $('#qr-fs-countdown-wrapper').addClass('d-none');
            }

            // Generate scan URL dynamically
            var protocol = window.location.protocol;
            var host = window.location.host;
            var path = window.location.pathname.replace('index.php', '');
            var scanUrl = `${protocol}//${host}${path}scan.php?class_id=${class_id}&date=${class_date}${expiresParam}`;

            // Render QRs
            qrcode_att1 = new QRCode(document.getElementById("qrcode-attendance"), {
                text: scanUrl,
                width: 250,
                height: 250,
                colorDark : "#1f2937",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            qrcode_att2 = new QRCode(document.getElementById("qrcode-fs-attendance"), {
                text: scanUrl,
                width: 450,
                height: 450,
                colorDark : "#1f2937",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            // Start countdown interval
            if (qr_countdown_interval) clearInterval(qr_countdown_interval);
            if (duration > 0) {
                updateTimerDisplay();
                qr_countdown_interval = setInterval(updateTimerDisplay, 1000);
            }
        }

        function updateTimerDisplay() {
            var diff = expires_timestamp - Date.now();
            if (diff <= 0) {
                clearInterval(qr_countdown_interval);
                expires_timestamp = 0;
                
                // QR Code Expired UX
                $('#qrcode-attendance').html('<div class="d-flex align-items-center justify-content-center border rounded" style="width: 250px; height: 250px; background: #fee2e2; color: #dc2626; font-weight: bold;">កូដ QR ហួសកំណត់ (Expired)</div>');
                $('#qrcode-fs-attendance').html('<div class="d-flex align-items-center justify-content-center border rounded" style="width: 450px; height: 450px; background: #fee2e2; color: #dc2626; font-weight: bold; font-size: 2rem;">កូដ QR ហួសកំណត់ (Expired)</div>');
                
                $('#qr-countdown-wrapper').removeClass('alert-warning').addClass('alert-danger');
                $('#qr-countdown-text').html('<span class="text-danger fw-bold">កូដ QR នេះបានហួសកំណត់ហើយ! សូមជ្រើសរើសពេលវេលាឡើងវិញដើម្បីបង្កើតថ្មី។</span>');
                
                $('#qr-fs-countdown-wrapper').removeClass('alert-warning').addClass('alert-danger');
                $('#qr-fs-countdown-wrapper span').html('<span class="text-danger fw-bold fs-4">កូដ QR នេះបានហួសកំណត់ហើយ! សូមបង្កើតកូដសារជាថ្មី។</span>');

                // Stop live poller since QR has expired
                if (live_att_interval) {
                    clearInterval(live_att_interval);
                    live_att_interval = null;
                }
            } else {
                var seconds = Math.floor(diff / 1000);
                var minutes = Math.floor(seconds / 60);
                seconds = seconds % 60;
                var minStr = minutes < 10 ? '0' + minutes : minutes;
                var secStr = seconds < 10 ? '0' + seconds : seconds;
                $('#qr-timer').text(minStr + ':' + secStr);
                $('#qr-fs-timer').text(minStr + ':' + secStr);
            }
        }

        $('#show-qr-btn').click(function(e) {
            e.preventDefault();
            
            var class_id = $('#class_id').val();
            var className = $('#class_id option:selected').text();
            var class_date = $('#class_date').val();

            if (!class_id || !class_date) {
                alert("សូមជ្រើសរើសថ្នាក់រៀន និងកាលបរិច្ឆេទជាមុនសិន!");
                return;
            }

            // Set Title text
            $('#qr-class-title').text(className);
            $('#qr-date-title').text('កាលបរិច្ឆេទ៖ ' + class_date);
            $('#qr-fs-class-title').text(className);
            $('#qr-fs-date-title').text('កាលបរិច្ឆេទ៖ ' + class_date);

             // Generate initial QR without resetting timer if already active
             generateQRCode(false);
 
             // Open Modal
             $('#qrModal').modal('show');
 
             // Start Live Sync Poller
             if (live_att_interval) clearInterval(live_att_interval);
             fetchLiveAttendance();
             live_att_interval = setInterval(fetchLiveAttendance, 3000);
         });
 
         // Regenerate QR on duration change
         $('#qr-duration').change(function() {
             generateQRCode(true); // Force reset because duration is explicitly changed by the teacher
             // If live syncing is stopped because of previous expiration, restart it
             if (!live_att_interval) {
                 fetchLiveAttendance();
                 live_att_interval = setInterval(fetchLiveAttendance, 3000);
             }
         });

        // Clear interval when modal is hidden
        $('#qrModal').on('hidden.bs.modal', function () {
            if (live_att_interval) {
                clearInterval(live_att_interval);
                live_att_interval = null;
            }
            if (qr_countdown_interval) {
                clearInterval(qr_countdown_interval);
                qr_countdown_interval = null;
            }
        });

        // Fullscreen overlay triggers
        $('#fullscreen-qr-attendance-btn').click(function() {
            $('#attendance-fullscreen-overlay').removeClass('d-none');
        });

        $('#close-attendance-fs-btn').click(function() {
            $('#attendance-fullscreen-overlay').addClass('d-none');
        });

        $(document).keydown(function(e) {
            if (e.keyCode === 27) { // ESC key
                $('#attendance-fullscreen-overlay').addClass('d-none');
            }
        });

        // Download QR Code image
        $('#download-qr-btn').click(function(e) {
            e.preventDefault();
            const canvas = $('#qrcode-attendance canvas')[0];
            const img = $('#qrcode-attendance img')[0];
            
            let imgSrc = '';
            if (canvas) {
                imgSrc = canvas.toDataURL("image/png");
            } else if (img) {
                imgSrc = img.src;
            }

            if (!imgSrc) {
                alert("មិនអាចទាញយករូបភាពបានឡើយ (Failed to extract QR Code image).");
                return;
            }

            const className = $('#class_id option:selected').text().replace(/[^a-z0-9]/gi, '_').toLowerCase();
            const classDate = $('#class_date').val();
            const fileName = `qr_${className}_${classDate}.png`;

            const link = document.createElement('a');
            link.href = imgSrc;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    })

    function checkAll_count(){
        var statuses = {'PCheckAll': 1, 'LCheckAll': 2, 'ACheckAll': 3, 'ECheckAll': 4}
        $('.checkAll').each(function(){
            var id = $(this).attr('id')
            var checkedCount = $(`.status_check[value="${statuses[id]}"]:checked`).length
            var totalCount = $(`.status_check[value="${statuses[id]}"]`).length
            if(totalCount != checkedCount || totalCount == 0){
                $(this).prop('checked', false)
            }else{
                $(`#${id}`).prop('checked', true)
            }
        })
    }
</script>

<!-- QR Code Library without SRI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="qrModalLabel">កូដ QR វត្តមានសម្រាប់ថ្នាក់រៀន</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h6 class="fw-bold mb-1 text-primary" id="qr-class-title">ឈ្មោះថ្នាក់រៀន</h6>
                <p class="text-secondary small mb-2" id="qr-date-title">កាលបរិច្ឆេទ</p>

                <!-- Duration Selector -->
                <div class="mb-3 d-flex justify-content-center align-items-center gap-2">
                    <label for="qr-duration" class="form-label mb-0 small fw-bold text-secondary">ទុកពេលឱ្យសិស្ស (Time Limit):</label>
                    <select id="qr-duration" class="form-select form-select-sm w-auto" style="border-radius: 8px;">
                        <option value="60">1 នាទី (1 Min)</option>
                        <option value="120">2 នាទី (2 Mins)</option>
                        <option value="300" selected>5 នាទី (5 Mins)</option>
                        <option value="600">10 នាទី (10 Mins)</option>
                        <option value="900">15 នាទី (15 Mins)</option>
                        <option value="0">គ្មានកំណត់ (No Limit)</option>
                    </select>
                </div>

                <!-- Countdown Display -->
                <div id="qr-countdown-wrapper" class="alert py-2 px-3 mb-3 d-none text-center" style="border-radius: 8px; font-size: 0.9rem; background-color: rgba(245, 158, 11, 0.05); border: 1px dashed rgba(245, 158, 11, 0.3); color: var(--warning);">
                    <span id="qr-countdown-text">កូដ QR នេះនឹងហួសកំណត់ក្នុងរយៈពេល៖ <strong id="qr-timer">--:--</strong></span>
                </div>
                
                <div id="qrcode-attendance" class="d-flex justify-content-center p-3 bg-light rounded-3 mb-3" style="min-height: 250px;"></div>
                
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" id="fullscreen-qr-attendance-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16">
                            <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <span>អេក្រង់ពេញ (Fullscreen)</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" id="download-qr-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        <span>ទាញយក (Download QR)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen QR Overlay -->
<div id="attendance-fullscreen-overlay" class="d-none d-flex flex-column align-items-center justify-content-center" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999; background: #ffffff; padding: 2rem;">
    <button type="button" class="btn-close" id="close-attendance-fs-btn" aria-label="Close" style="position: absolute; top: 30px; right: 30px; font-size: 2rem;"></button>
    
    <div class="text-center mb-2">
        <h2 class="fw-bold text-dark mb-1" id="qr-fs-class-title">ឈ្មោះថ្នាក់រៀន</h2>
        <p class="text-secondary fs-5 mb-2" id="qr-fs-date-title">កាលបរិច្ឆេទ</p>
    </div>

    <!-- Fullscreen Countdown Display -->
    <div id="qr-fs-countdown-wrapper" class="alert py-2 px-4 mb-3 d-none text-center" style="border-radius: 8px; font-size: 1.25rem; background-color: rgba(245, 158, 11, 0.05); border: 1px dashed rgba(245, 158, 11, 0.3); color: var(--warning);">
        <span>កូដ QR នេះនឹងហួសកំណត់ក្នុងរយៈពេល៖ <strong id="qr-fs-timer" class="fw-bold">--:--</strong></span>
    </div>
    
    <div id="qrcode-fs-attendance" class="d-flex justify-content-center p-4 bg-white border rounded-4 shadow-sm" style="min-height: 450px;"></div>
    
    <div class="text-center mt-3 text-muted fs-6">
        <p class="mb-0">សូមឱ្យសិស្សបើកកាមេរ៉ាទូរសព្ទ ដើម្បីស្កេនចុះវត្តមាន</p>
        <small>(ចុច ESC ឬសញ្ញាខ្វែង ដើម្បីចាកចេញ)</small>
    </div>
</div>