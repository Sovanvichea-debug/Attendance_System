<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
    <div class="page-title">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2 d-inline-block align-middle"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span class="align-middle">List of Students</span>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-danger d-flex align-items-center gap-2" type="button" id="reset_student_ids" title="Reset and reindex student IDs starting from 1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
            Reset IDs
        </button>
        <button class="btn btn-outline-primary d-flex align-items-center gap-2" type="button" id="import_student">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Import Excel
        </button>
        <button class="btn btn-primary d-flex align-items-center gap-2" type="button" id="add_student">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="16" y1="11" x2="22" y2="11"></line></svg>
            Add New Student
        </button>
    </div>
</div>
<hr>
<?php 
$studentList = $actionClass->list_student();
$classList = $actionClass->list_class();

$filter_class_id = $_GET['class_id'] ?? '';
$filter_class_name = '';
if (!empty($filter_class_id)) {
    foreach ($classList as $c) {
        if ($c['id'] == $filter_class_id) {
            $filter_class_name = $c['name'];
            break;
        }
    }
}

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
?>
<!-- Order & Filter Controls -->
<div class="row g-3 mb-3 justify-content-center">
    <div class="col-lg-10 col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #f8fafc;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-md-4 col-12">
                        <div class="position-relative">
                            <input type="text" id="search-student" class="form-control ps-5" placeholder="ស្វែងរកឈ្មោះសិស្ស (Search name)..." style="border-radius: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%);">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                    <!-- Filter by Class -->
                    <div class="col-md-4 col-12">
                        <select id="filter-class" class="form-select" style="border-radius: 8px;">
                            <option value="">-- បង្ហាញថ្នាក់ទាំងអស់ (All Classes) --</option>
                            <?php foreach($classList as $c): ?>
                                <option value="<?= htmlspecialchars($c['name']) ?>" <?= ($filter_class_name === $c['name']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Sort by Name -->
                    <div class="col-md-4 col-12">
                        <select id="sort-student" class="form-select" style="border-radius: 8px;">
                            <option value="asc">តម្រៀបតាមឈ្មោះ៖ ក ដល់ ល្អ (A to Z)</option>
                            <option value="desc">តម្រៀបតាមឈ្មោះ៖ ល្អ ដល់ ក (Z to A)</option>
                            <option value="id-asc">តម្រៀបតាម ID៖ តូចទៅធំ</option>
                            <option value="id-desc">តម្រៀបតាម ID៖ ធំទៅតូច</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive border-0">
                    <table class="table table-hovered table-striped">
                        <colgroup>
                            <col width="8%">
                            <col width="22%">
                            <col width="25%">
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>ថ្នាក់រៀន (Class)</th>
                                <th>ឈ្មោះខ្មែរ (Name)</th>
                                <th>ឈ្មោះឡាតាំង (Latin Name)</th>
                                <th class="text-center">ភេទ (Gender)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($studentList) && is_array($studentList)): ?>
                            <?php foreach($studentList as $row): ?>
                                <tr class="student-row" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>" data-class="<?= htmlspecialchars($row['class']) ?>">
                                    <td class="text-center fw-bold text-muted"><?= $row['id'] ?></td>
                                    <td><span class="class-tag"><?= $row['class'] ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar"><?= getInitials($row['name']) ?></div>
                                            <a href="javascript:void(0)" class="view_profile fw-semibold text-dark text-decoration-none hover-primary-text" data-id="<?= $row['id'] ?>"><?= $row['name'] ?></a>
                                        </div>
                                    </td>
                                    <td class="align-middle fw-semibold text-dark-emphasis"><?= htmlspecialchars($row['name_latin'] ?? '-') ?></td>
                                    <td class="text-center align-middle">
                                        <?php 
                                            $g = $row['gender'] ?? '';
                                            if ($g == 'Male') {
                                                echo "<span class='badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill' style='font-size: 0.8rem;'>ប្រុស</span>";
                                            } else if ($g == 'Female') {
                                                echo "<span class='badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill' style='font-size: 0.8rem;'>ស្រី</span>";
                                            } else {
                                                echo "<span class='text-muted'>-</span>";
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-outline-primary edit_student d-flex align-items-center justify-content-center" type="button" data-id="<?= $row['id'] ?>" title="Edit Student">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path></svg>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete_student d-flex align-items-center justify-content-center" type="button" data-id="<?= $row['id'] ?>" title="Delete Student">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="text-center py-4 text-muted" colspan="6">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <div>No students registered yet.</div>
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
    $(document).ready(function(){
        // Dynamic Filter and Sort logic
        function filterAndSortStudents() {
            const searchQuery = $('#search-student').val().toLowerCase().trim();
            const classFilter = $('#filter-class').val();
            const sortVal = $('#sort-student').val();
            const tbody = $('table tbody');
            const rows = $('.student-row').detach().toArray();
            let visibleCount = 0;

            // Remove no-results row if it exists
            $('#no-results-row').remove();

            // 1. Filter
            rows.forEach(function(row) {
                const name = $(row).attr('data-name').toLowerCase();
                const className = $(row).attr('data-class');
                
                const matchesSearch = name.indexOf(searchQuery) > -1;
                const matchesClass = !classFilter || className === classFilter;

                if (matchesSearch && matchesClass) {
                    $(row).show();
                    visibleCount++;
                } else {
                    $(row).hide();
                }
            });

            // 2. Sort
            rows.sort(function(a, b) {
                if (sortVal === 'asc' || sortVal === 'desc') {
                    const nameA = $(a).attr('data-name').toLowerCase();
                    const nameB = $(b).attr('data-name').toLowerCase();
                    if (nameA < nameB) return sortVal === 'asc' ? -1 : 1;
                    if (nameA > nameB) return sortVal === 'asc' ? 1 : -1;
                    return 0;
                } else {
                    const idA = parseInt($(a).attr('data-id'));
                    const idB = parseInt($(b).attr('data-id'));
                    return sortVal === 'id-asc' ? idA - idB : idB - idA;
                }
            });

            // 3. Append back
            rows.forEach(function(row) {
                tbody.append(row);
            });

            // 4. Show empty state if none visible
            if (visibleCount === 0) {
                tbody.append(`
                    <tr id="no-results-row">
                        <td class="text-center py-4 text-muted" colspan="6">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <div>រកមិនឃើញទិន្នន័យដែលអ្នកស្វែងរកទេ (No matching students found)</div>
                        </td>
                    </tr>
                `);
            }
        }

        // Bind events for live filter/sorting
        $('#search-student').on('input', filterAndSortStudents);
        $('#filter-class, #sort-student').on('change', filterAndSortStudents);

        // Run initially to sort A-Z by default
        filterAndSortStudents();

        $('#add_student').click(function(e){
            e.preventDefault()
            open_modal('student_form.php', 'Add New Student')
        })
        $('#import_student').click(function(e){
            e.preventDefault()
            open_modal('import_students.php', 'នាំចូលបញ្ជីឈ្មោះសិស្ស (Import Student List)')
        })
        $('#reset_student_ids').click(function(e){
            e.preventDefault()
            if(confirm("តើអ្នកពិតជាចង់កំណត់ ID របស់សិស្សទាំងអស់ឡើងវិញចាប់ពីលេខ 1 មកវិញមែនទេ? (ចំណាំ៖ វានឹងមិនធ្វើឱ្យបាត់បង់ទិន្នន័យវត្តមានឡើយ)\n\nAre you sure you want to reset all student IDs to start from 1? (Note: This will NOT delete or break any attendance records)") == true){
                start_loader()
                $.ajax({
                    url: "./ajax-api.php?action=reset_student_ids",
                    method: "POST",
                    dataType: 'JSON',
                    error: (error) => {
                        console.error(error)
                        alert('An error occurred.')
                        end_loader()
                    },
                    success:function(resp){
                        if(resp?.status == 'success')
                            location.reload();
                        else {
                            alert(resp?.msg || 'An error occurred.')
                            end_loader();
                        }
                    }
                })
            }
        })
        $('.edit_student').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            open_modal('student_form.php', 'Update Student Details', {id: id})
        })
        $('.view_profile').click(function(e){
            e.preventDefault()
            var id = $(this).attr('data-id')
            open_modal('student_profile.php', 'ប្រវត្តិរូបសិស្ស / Student Profile', {id: id}, 'modal-lg')
        })
        $('.delete_student').click(function(e){
            e.preventDefault()
            var id = $(this)[0].dataset?.id || ''
            start_loader()
            if(confirm(`Are you sure to delete the selected student? All historical attendance record for this student will also be deleted. This action cannot be undone.`) == true){
                $.ajax({
                    url: "./ajax-api.php?action=delete_student",
                    method: "POST",
                    data: { id : id},
                    dataType: 'JSON',
                    error: (error) => {
                        console.error(error)
                        alert('An error occurred.')
                        end_loader()
                    },
                    success:function(resp){
                        if(resp?.status != '')
                            location.reload();
                        else
                            end_loader();
                    }
                })
            }else{
                end_loader();
            }
        })
    })
</script>