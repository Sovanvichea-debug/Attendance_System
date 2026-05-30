<?php 
session_start();
require_once('classes/actions.class.php');
$actionClass = new Actions();
$page = $_GET['page'] ?? "home";

// Session check
if(!isset($_SESSION['user'])){
    if($page !== 'login'){
        header('Location: ./?page=login');
        exit;
    }
} else {
    if($page === 'login'){
        header('Location: ./');
        exit;
    }
}

$page_title = ucwords(str_replace("_", " ", $page));
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once('inc/header.php'); ?>
<body class="<?= $page === 'login' ? 'login-page-body' : '' ?>">
<?php if($page !== 'login'): ?>
    <!-- Sidebar Drawer Component (will push content when opened) -->
    <?php include_once('inc/navigation.php'); ?>

    <div class="app-content-push-wrapper">
        <!-- Top Navbar with Sidebar Toggle -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center gap-2">
                    <!-- Hamburger Menu Button -->
                    <button class="btn btn-link text-dark p-0 me-2" type="button" id="sidebar-toggle" aria-label="Toggle Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-3 ms-auto">
                    <!-- Real-time Clock Widget -->
                    <div class="live-clock-badge d-flex align-items-center gap-2 d-none d-sm-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary live-clock-icon">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span id="live-clock-time" class="fw-bold">--:--:--</span>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none profile-dropdown-toggle" href="#" id="headerProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle">
                                <?php if(!empty($_SESSION['user']['avatar']) && file_exists(__DIR__.'/'.$_SESSION['user']['avatar'])): ?>
                                    <img src="<?= $_SESSION['user']['avatar'] ?>" alt="Profile" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                                <?php else: ?>
                                    <?= mb_substr($_SESSION['user']['fullname'] ?? 'U', 0, 1, 'UTF-8') ?>
                                <?php endif; ?>
                            </div>
                            <span class="d-none d-sm-inline text-dark fw-semibold" style="font-size: 0.9rem;"><?= $_SESSION['user']['fullname'] ?? 'User' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu border-0 shadow mt-2" aria-labelledby="headerProfileDropdown">
                            <li>
                                <div class="dropdown-header text-dark-emphasis">
                                    <strong class="d-block"><?= $_SESSION['user']['fullname'] ?? 'User' ?></strong>
                                    <small class="text-muted">@<?= $_SESSION['user']['username'] ?? 'username' ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="./?page=profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span>ព័ត៌មានផ្ទាល់ខ្លួន (Profile)</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger d-flex align-items-center gap-2 py-2" href="javascript:void(0)" id="header-logout-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    <span>ចាកចេញ (Logout)</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="container-md py-4">
            <!-- Flashdata -->
            <?php if(isset($_SESSION['flashdata']) && !empty($_SESSION['flashdata'])): ?>
                <div class="flashdata flashdata-<?= $_SESSION['flashdata']['type'] ?? 'default' ?> mb-4">
                    <div class="d-flex w-100 align-items-center">
                        <div class="flex-grow-1">
                            <?php if (($_SESSION['flashdata']['type'] ?? '') == 'success'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-success me-2 d-inline-block align-middle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-danger me-2 d-inline-block align-middle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <?php endif; ?>
                            <?= $_SESSION['flashdata']['msg'] ?? '' ?>
                        </div>
                        <div class="ps-2">
                            <a href="javascript:void(0)" onclick="this.closest('.flashdata').remove()" class="flashdata-close">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="align-middle"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['flashdata']); ?>
            <?php endif; ?>
            
            <div class="main-wrapper">
                <?php include_once("pages/{$page}.php"); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php include_once("pages/{$page}.php"); ?>
<?php endif; ?>
    <?php include_once('inc/footer.php'); ?>
</body>
</html>