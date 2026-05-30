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
                        <div class="h5 mb-0 fw-bold text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                            <span class="align-middle">Attendance Sheet</span>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-6 col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </span>
                                <input type="text" id="search-student" class="form-control border-start-0 ps-0" placeholder="Search student name...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive border-0">
                        <table id="attendance-tbl" class="table table-hovered">
                            <colgroup>
                                <col width="40%">
                                <col width="20%">
                                <col width="20%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Students</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-light-subtle">
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
                                </tr>
                                <?php if(!empty($studentList) && is_array($studentList)): ?>
                                <?php foreach($studentList as $row): ?>
                                    <tr class="student-row">
                                        <td class="fw-semibold text-dark align-middle">
                                            <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="student-avatar"><?= getInitials($row['name']) ?></div>
                                                <span class="student-name-text"><?= $row['name'] ?></span>
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
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-muted">
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
                }
            }else{
                if(id == 'PCheckAll'){
                    $('.status_check[value="1"]').prop('checked', false) 
                }else if(id == 'LCheckAll'){
                    $('.status_check[value="2"]').prop('checked', false) 
                }else if(id == 'ACheckAll'){
                    $('.status_check[value="3"]').prop('checked', false) 
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
                    var name = $(this).find('.student-name-text').text() || "";
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
    })

    function checkAll_count(){
        var statuses = {'PCheckAll': 1, 'LCheckAll': 2, 'ACheckAll': 3}
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