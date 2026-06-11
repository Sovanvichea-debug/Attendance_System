<?php
session_start();
require_once(realpath(__DIR__.'/../classes/actions.class.php'));
$actionClass = new Actions();

$student_id = $_POST['id'] ?? '';
if(empty($student_id)){
    echo "<div class='alert alert-danger'>Invalid Student ID!</div>";
    exit;
}

$data = $actionClass->get_student_attendance_stats($student_id);
if(!$data){
    echo "<div class='alert alert-danger'>Student record not found!</div>";
    exit;
}

$student = $data['student'];
$counts = $data['counts'];
$history = $data['history'];

// Helper function to get initials for avatar
if (!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(" ", $name);
        $initials = "";
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1, 'UTF-8');
        }
        return mb_strtoupper(mb_substr($initials, 0, 2, 'UTF-8'));
    }
}

// Calculate percentages
$total = (int)$counts['total'];
$present_pct = $total > 0 ? round(($counts['present'] / $total) * 100) : 0;
$late_pct = $total > 0 ? round(($counts['late'] / $total) * 100) : 0;
$absent_pct = $total > 0 ? round(($counts['absent'] / $total) * 100) : 0;
$excused_pct = $total > 0 ? round(($counts['excused'] / $total) * 100) : 0;
?>

<div class="container-fluid py-2">
    <!-- Student Header Info -->
    <div class="d-flex align-items-center mb-4">
        <div class="profile-modal-avatar">
            <?= getInitials($student['name']) ?>
        </div>
        <div class="student-profile-info">
            <h4 class="fw-bold text-dark mb-1">
                <?= htmlspecialchars($student['name']) ?> 
                <?php if(!empty($student['name_latin'])): ?>
                    <span class="text-muted fw-normal fs-6"> (<?= htmlspecialchars($student['name_latin']) ?>)</span>
                <?php endif; ?>
            </h4>
            <div class="d-flex flex-wrap gap-3 align-items-center mt-1 text-muted" style="font-size: 0.9rem;">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 align-middle"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                    Class: <span class="fw-bold text-dark"><?= htmlspecialchars($student['class_name']) ?></span>
                </div>
                <div>
                    Gender: 
                    <?php 
                        $g = $student['gender'] ?? '';
                        if ($g == 'Male') {
                            echo "<span class='badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill'>ប្រុស (Male)</span>";
                        } else if ($g == 'Female') {
                            echo "<span class='badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill'>ស្រី (Female)</span>";
                        } else {
                            echo "<span class='text-muted'>-</span>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6 col-6">
            <div class="student-stat-card present">
                <div class="student-stat-num present"><?= $counts['present'] ?></div>
                <div class="student-stat-label">Present</div>
                <div class="student-stat-pct"><?= $present_pct ?>%</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-6">
            <div class="student-stat-card late">
                <div class="student-stat-num late"><?= $counts['late'] ?></div>
                <div class="student-stat-label">Late</div>
                <div class="student-stat-pct"><?= $late_pct ?>%</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-6">
            <div class="student-stat-card absent">
                <div class="student-stat-num absent"><?= $counts['absent'] ?></div>
                <div class="student-stat-label">Absent</div>
                <div class="student-stat-pct"><?= $absent_pct ?>%</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-6">
            <div class="student-stat-card excused">
                <div class="student-stat-num excused"><?= $counts['excused'] ?></div>
                <div class="student-stat-label">Excused</div>
                <div class="student-stat-pct"><?= $excused_pct ?>%</div>
            </div>
        </div>
    </div>

    <!-- Attendance History List -->
    <div class="mb-3">
        <h5 class="fw-bold text-dark mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 align-middle"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span class="align-middle">Attendance History / ប្រវត្តវត្តមាន</span>
        </h5>
        
        <?php if(!empty($history)): ?>
            <div class="history-table-container">
                <table class="table table-hovered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 15%;">No.</th>
                            <th style="width: 45%;">Date / ថ្ងៃខែឆ្នាំ</th>
                            <th style="width: 25%;">Day / ថ្ងៃនៃសប្តាហ៍</th>
                            <th class="text-center" style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($history as $k => $row): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?= $k + 1 ?></td>
                                <td class="fw-semibold text-dark"><?= date("F d, Y", strtotime($row['class_date'])) ?></td>
                                <td class="text-secondary"><?= date("l", strtotime($row['class_date'])) ?></td>
                                <td class="text-center">
                                    <?php 
                                        switch((int)$row['status']){
                                            case 1:
                                                echo "<span class='status-cell-pill p' title='Present'>P</span>";
                                                break;
                                            case 2:
                                                echo "<span class='status-cell-pill l' title='Late'>L</span>";
                                                break;
                                            case 3:
                                                echo "<span class='status-cell-pill a' title='Absent'>A</span>";
                                                break;
                                            case 4:
                                                echo "<span class='status-cell-pill e' title='Excused'>E</span>";
                                                break;
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4 border rounded bg-light-subtle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <div class="text-muted small">No attendance records found for this student.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
