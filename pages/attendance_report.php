<div class="page-title mb-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span class="align-middle">Attendance Report</span></div>
<hr>
<?php 
$classList = $actionClass->list_class();
$class_id = $_GET['class_id'] ?? "";
$class_month = $_GET['class_month'] ?? "";
$studentList = $actionClass->attendanceStudentsMonthly($class_id, $class_month);
$monthLastDay = 0;
if(!empty($class_month)){
    $monthLastDay = date("t", strtotime("{$class_month}-01")) ;
}

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
                                <label for="class_id" class="form-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle text-muted"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg> Class</label>
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
                                <label for="class_month" class="form-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle text-muted"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Month</label>
                                <input type="month" name="class_month" id="class_month" class="form-control" value="<?= $class_month ?? '' ?>" required="required">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($class_id) && !empty($class_month)): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <strong class="text-dark me-2">Legend:</strong>
                        <span class="legend-pill success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Present (P)</span>
                        <span class="legend-pill warning"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Late (L)</span>
                        <span class="legend-pill danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Absent (A)</span>
                        <span class="legend-pill info"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Excused (E)</span>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="h5 mb-0 fw-bold text-dark"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 align-middle"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span class="align-middle">Monthly Sheet: <?= date("F Y", strtotime($class_month)) ?></span></div>
                    <div class="d-flex gap-2">
                        <a href="./export_attendance_excel.php?class_id=<?= $class_id ?>&class_month=<?= $class_month ?>" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Export to Excel
                        </a>
                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" type="button" onclick="window.print()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Print Report
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive border-0">
                        <table id="attendance-rpt-tbl" class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Students</th>
                                    <?php for($i=1; $i <= $monthLastDay; $i++): ?>
                                        <th class="text-center"><?= $i ?></th>
                                    <?php endfor; ?>
                                    <th class="text-center bg-light text-dark" title="Total Present">TP</th>
                                    <th class="text-center bg-light text-dark" title="Total Late">TL</th>
                                    <th class="text-center bg-light text-dark" title="Total Absent">TA</th>
                                    <th class="text-center bg-light text-dark" title="Total Excused">TE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($studentList) && is_array($studentList)): ?>
                                <?php foreach($studentList as $row): ?>
                                    <tr class="student-row">
                                        <td class="fw-semibold text-dark align-middle ps-4">
                                            <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="student-avatar"><?= getInitials($row['name']) ?></div>
                                                <a href="javascript:void(0)" class="view_profile text-dark text-decoration-none hover-primary-text" data-id="<?= $row['id'] ?>"><?= $row['name'] ?></a>
                                            </div>
                                        </td>
                                        <?php 
                                        $tp = 0;
                                        $tl = 0;
                                        $ta = 0;
                                        $te = 0;
                                        ?>
                                        <?php for($i=1; $i <= $monthLastDay; $i++): ?>
                                            <td class="text-center align-middle">
                                                <?php 
                                                    $day_key = $class_month . "-" . str_pad($i, 2, "0", STR_PAD_LEFT);
                                                    $status = $row['attendance'][$day_key] ?? null;
                                                    switch($status){
                                                        case 1:
                                                            echo "<span class='status-cell-pill p' title='Present'>P</span>";
                                                            $tp += 1;
                                                            break;
                                                        case 2:
                                                            echo "<span class='status-cell-pill l' title='Late'>L</span>";
                                                            $tl += 1;
                                                            break;
                                                        case 3:
                                                            echo "<span class='status-cell-pill a' title='Absent'>A</span>";
                                                            $ta += 1;
                                                            break;
                                                        case 4:
                                                            echo "<span class='status-cell-pill e' title='Excused'>E</span>";
                                                            $te += 1;
                                                            break;
                                                        default:
                                                            echo "<span class='text-muted' style='font-size: 0.8rem;'>-</span>";
                                                    }
                                                ?>
                                            </td>
                                        <?php endfor; ?>
                                        <th class="text-center align-middle bg-light text-dark fw-bold"><?= $tp ?></th>
                                        <th class="text-center align-middle bg-light text-dark fw-bold"><?= $tl ?></th>
                                        <th class="text-center align-middle bg-light text-dark fw-bold"><?= $ta ?></th>
                                        <th class="text-center align-middle bg-light text-dark fw-bold"><?= $te ?></th>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?= $monthLastDay + 5 ?>" class="py-4 text-center text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto mb-2 text-muted"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                                            No student records found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $('#class_id, #class_month').change(function(e){
            var class_id = $('#class_id').val()
            var class_month = $('#class_month').val()
            if (class_id && class_month) {
                location.replace(`./?page=attendance_report&class_id=${class_id}&class_month=${class_month}`)
            }
        })

        // Student Profile Modal Trigger
        $('.view_profile').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            open_modal('student_profile.php', 'ប្រវត្តិរូបសិស្ស / Student Profile', {id: id}, 'modal-lg');
        });
    })
</script>