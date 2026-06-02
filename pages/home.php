<?php
$stats = $actionClass->get_dashboard_stats();
$total_classes = $stats['total_classes'];
$total_students = $stats['total_students'];
$latest_date = $stats['latest_date'];
$breakdown = $stats['attendance_breakdown'];
$class_list = $stats['class_list'];
$recent_sessions = $stats['recent_sessions'];

// Calculate average rate for the latest session
$latest_attendance_rate = 0;
if ($breakdown['total'] > 0) {
    // Rate of present + late
    $present_count = $breakdown['present'] + $breakdown['late'];
    $latest_attendance_rate = round(($present_count / $breakdown['total']) * 100);
}
?>

<div class="row">
    <div class="col-12">
        <div class="dashboard-welcome">
            <h1 class="welcome-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#fcd34d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"></path></svg>
                <span class="align-middle">Welcome Back!</span>
            </h1>
            <p class="welcome-subtitle">Here is the overview of your classes and student attendance for today, <?= date("l, F j, Y") ?>.</p>
        </div>
    </div>
</div>

<!-- Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-wrapper primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Classes</div>
                <div class="stat-value"><?= $total_classes ?></div>
            </div>
            <a href="./?page=class_list" class="btn btn-sm btn-light rounded-pill d-flex align-items-center justify-content-center" style="width: 2.25rem; height: 2.25rem; padding: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-wrapper success">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Students</div>
                <div class="stat-value"><?= $total_students ?></div>
            </div>
            <a href="./?page=student_list" class="btn btn-sm btn-light rounded-pill d-flex align-items-center justify-content-center" style="width: 2.25rem; height: 2.25rem; padding: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-12">
        <div class="stat-card">
            <div class="stat-icon-wrapper warning">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            </div>
            <div class="stat-details">
                <div class="stat-label">Latest Attendance Rate</div>
                <div class="stat-value"><?= !empty($latest_date) ? $latest_attendance_rate . "%" : "N/A" ?></div>
                <?php if (!empty($latest_date)): ?>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Session: <?= date("M d, Y", strtotime($latest_date)) ?></small>
                <?php endif; ?>
            </div>
            <a href="./?page=attendance_report" class="btn btn-sm btn-light rounded-pill d-flex align-items-center justify-content-center" style="width: 2.25rem; height: 2.25rem; padding: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Actions -->
    <div class="col-lg-6 col-md-12">
        <div class="card h-100">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polyline></svg>
                <span class="align-middle">Quick Actions</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-12">
                        <a href="./?page=attendance" class="action-card">
                            <div class="action-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="9 16 11 18 15 14"></polyline></svg>
                            </div>
                            <div class="action-card-title">Take Attendance</div>
                            <div class="action-card-desc">Mark student attendance for today</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-12">
                        <a href="./?page=attendance_report" class="action-card">
                            <div class="action-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>
                            </div>
                            <div class="action-card-title">View Reports</div>
                            <div class="action-card-desc">Monthly breakdown of class data</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-12">
                        <a href="javascript:void(0)" id="quick-add-class" class="action-card">
                            <div class="action-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </div>
                            <div class="action-card-title">Add Class</div>
                            <div class="action-card-desc">Create a new subject class</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-12">
                        <a href="javascript:void(0)" id="quick-add-student" class="action-card">
                            <div class="action-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="16" y1="11" x2="22" y2="11"></line></svg>
                            </div>
                            <div class="action-card-title">Add Student</div>
                            <div class="action-card-desc">Register a student to a class</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Latest Attendance Breakdown -->
    <div class="col-lg-6 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    <span class="align-middle">Attendance Breakdown</span>
                </span>
                <?php if (!empty($latest_date)): ?>
                    <span class="badge bg-light text-secondary border rounded-pill fw-bold" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                        <?= date("M d, Y", strtotime($latest_date)) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <?php if (!empty($latest_date)): ?>
                    <div class="row align-items-center py-2">
                        <div class="col-md-6 col-12 mb-3 mb-md-0 text-center">
                            <div class="position-relative d-inline-flex align-items-center justify-content-center">
                                <div class="p-4 rounded-circle bg-light d-flex flex-column align-items-center justify-content-center" style="width: 140px; height: 140px; border: 6px solid var(--primary); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);">
                                    <span class="h2 fw-bold mb-0 text-primary"><?= $latest_attendance_rate ?>%</span>
                                    <span class="text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Present</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="legend-pill success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Present</span>
                                    <strong class="text-dark"><?= $breakdown['present'] ?></strong>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="legend-pill warning"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Late</span>
                                    <strong class="text-dark"><?= $breakdown['late'] ?></strong>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="legend-pill danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Absent</span>
                                    <strong class="text-dark"><?= $breakdown['absent'] ?></strong>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="legend-pill info"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="8" height="8" fill="currentColor" class="me-1 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle></svg> Excused</span>
                                    <strong class="text-dark"><?= $breakdown['excused'] ?? 0 ?></strong>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted fw-bold">Total Marked</span>
                                    <strong class="text-dark"><?= $breakdown['total'] ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        <h5 class="text-dark fw-bold">No Attendance Data Yet</h5>
                        <p class="text-muted small px-3">Mark class attendance to see your dynamic statistics report here.</p>
                        <a href="./?page=attendance" class="btn btn-sm btn-primary mt-2">Take Attendance Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Attendance Log -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--info)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline><path d="M12 2a10 10 0 0 1 9.8 8.1M22 12a10 10 0 0 1-19.8 1.9"></path></svg>
                <span class="align-middle">Recent Attendance Sessions</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive border-0">
                    <table class="table table-hovered mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Class Name</th>
                                <th class="text-center">Total Students</th>
                                <th class="text-center">Attendance Summary</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_sessions)): ?>
                                <?php foreach ($recent_sessions as $sess): ?>
                                    <tr>
                                        <td class="fw-bold"><?= date("M d, Y", strtotime($sess['class_date'])) ?></td>
                                        <td><?= $sess['class_name'] ?></td>
                                        <td class="text-center"><?= $sess['total_students'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success me-1 px-2 py-1 rounded fw-bold" title="Present"><?= $sess['present_count'] ?> Present</span>
                                            <span class="badge bg-warning-subtle text-warning me-1 px-2 py-1 rounded fw-bold" title="Late"><?= $sess['late_count'] ?> Late</span>
                                            <span class="badge bg-danger-subtle text-danger me-1 px-2 py-1 rounded fw-bold" title="Absent"><?= $sess['absent_count'] ?> Absent</span>
                                            <span class="badge bg-info-subtle text-info px-2 py-1 rounded fw-bold" title="Excused"><?= $sess['excused_count'] ?? 0 ?> Excused</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="./?page=attendance&class_id=<?= $sess['class_id'] ?>&class_date=<?= $sess['class_date'] ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" title="Edit/View">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path></svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                        No recent sessions found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#quick-add-class').click(function(e) {
        e.preventDefault();
        open_modal('class_form.php', 'Create New Class');
    });
    $('#quick-add-student').click(function(e) {
        e.preventDefault();
        open_modal('student_form.php', 'Add New Student');
    });
});
</script>