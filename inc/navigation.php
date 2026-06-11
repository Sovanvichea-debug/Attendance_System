<aside class="app-sidebar" id="app-sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
        <a class="sidebar-brand d-flex align-items-center gap-2 text-decoration-none" href="./">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"></path></svg>
            <span class="fw-bold">Attendance Tracker</span>
        </a>
        <button class="btn-close" type="button" id="sidebar-close" aria-label="Close Sidebar"></button>
    </div>
    
    <div class="sidebar-body py-3 px-3">
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (!isset($page) || $page == 'home') ? 'active' : '' ?>" href="./">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (isset($page)) && $page == 'class_list' ? 'active' : '' ?>" href="./?page=class_list">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                    <span>Classes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (isset($page)) && $page == 'student_list' ? 'active' : '' ?>" href="./?page=student_list">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (isset($page)) && $page == 'attendance' ? 'active' : '' ?>" href="./?page=attendance">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="9 16 11 18 15 14"></polyline></svg>
                    <span>Take Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (isset($page)) && $page == 'attendance_report' ? 'active' : '' ?>" href="./?page=attendance_report">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>Attendance Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?= (isset($page)) && $page == 'settings' ? 'active' : '' ?>" href="./?page=settings">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
$(document).ready(function() {
    // Live Clock Function
    function updateLiveClock() {
        const timeSpan = document.getElementById('live-clock-time');
        if (!timeSpan) return;
        
        const now = new Date();
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        
        const timeString = now.toLocaleTimeString('en-US', options);
        timeSpan.textContent = timeString;
    }
    
    updateLiveClock();
    setInterval(updateLiveClock, 1000);

    // Sidebar Push Toggle
    $('#sidebar-toggle, #sidebar-close').click(function(e) {
        e.stopPropagation();
        $('#app-sidebar').toggleClass('show');
        $('body').toggleClass('sidebar-open');
    });

    // Close sidebar when clicking outside of it
    $(document).click(function(e) {
        if ($('body').hasClass('sidebar-open')) {
            if (!$(e.target).closest('#app-sidebar').length && !$(e.target).closest('#sidebar-toggle').length) {
                $('#app-sidebar').removeClass('show');
                $('body').removeClass('sidebar-open');
            }
        }
    });

    // Logout Click Handler
    $('#header-logout-btn').click(function(e) {
        e.preventDefault();
        if(confirm("តើលោកគ្រូពិតជាចង់ចាកចេញពីប្រព័ន្ធមែនទេ?")) {
            $.ajax({
                url: 'ajax-api.php?action=logout',
                dataType: 'json',
                success: function(resp) {
                    if(resp.status === 'success') {
                        window.location.href = './?page=login';
                    }
                }
            });
        }
    });
});
</script>