<div class="sidebar" id="sidebar">
    
        <link rel="stylesheet" href="/school portal/index.css">
        <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


    <style>
/* Sidebar container */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0; 
    left: 0;
    background: #b80000; /* solid university red */
    color: #fff;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.15);
    transition: all 0.3s;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    z-index: 10;

}

.main-content {
    margin-left: 250px; /* leave room for sidebar */
    padding: 20px;
    position: relative;
    z-index: 1;
}



/* Sidebar menu */
.sidebar-menu {
    list-style: none;
    padding: 15px 0;
    margin: 0;
}

.sidebar-menu .menu-item {
    margin: 5px 10px;
}

/* Sidebar links */
.sidebar-menu .menu-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    background-color: transparent;
    transition: background 0.18s, color 0.18s, transform 0.12s;
}

.sidebar-menu .menu-link i {
    font-size: 18px;
    width: 20px;
    text-align: center;
}

/* Hover behaviour kept minimal here; visual theming is handled by module CSS */
/* hover styles intentionally removed here so the module CSS can control hover/active visuals */

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.25);
    border-radius: 4px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(243, 14, 14, 0.5);
}

/* Mobile responsiveness */
@media (max-width: 992px) {
    .sidebar {
        left: -260px;
    }
    .sidebar.open {
        left: 0;
    }
}
</style>

<?php
// determine current script to mark active menu item
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
    <div class="sidebar-header text-center py-3">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;">
            <div class="sidebar-school">TEACHERS PANEL</div>
            <button id="sidebarToggle" title="Toggle sidebar" style="background:transparent;border:none;color:#fff;font-size:18px;cursor:pointer;padding:4px 8px;border-radius:6px;">☰</button>
        </div>
    </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'index.php' ? ' active' : ''); ?>" href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'mycourse.php' ? ' active' : ''); ?>" href="mycourse.php">
                    <i class="fas fa-book"></i>
                    <span>My Course Units</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'mystudents.php' ? ' active' : ''); ?>" href="mystudents.php">
                    <i class="fas fa-user-graduate"></i>
                    <span>My Students</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'attendance.php' ? ' active' : ''); ?>" href="attendance.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'mycourseinfo.php' ? ' active' : ''); ?>" href="mycourseinfo.php">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Grades</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'report.php' ? ' active' : ''); ?>" href="report.php">
                    <i class="fas fa-flag"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'viewProfile.php' ? ' active' : ''); ?>" href="viewProfile.php">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'shareNotes.php' ? ' active' : ''); ?>" href="shareNotes.php">
                    <i class="fas fa-user"></i>
                    <span>Notes</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'logout.php' ? ' active' : ''); ?>" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="<?php echo 'menu-link' . ($current_page === 'awardmarks.php' ? ' active' : ''); ?>" href="awardmarks.php">
                    <i class="fas fa-marker"></i>
                    <span>Award Marks & Grades</span>
                </a>
            </li>
        </ul>
    </div>
    <script>
        (function() {
            try {
                var sidebar = document.getElementById('sidebar');
                var toggle = document.getElementById('sidebarToggle');
                var body = document.body;
                var collapsed = localStorage.getItem('teacherSidebarCollapsed') === '1';
                function applyState() {
                    if (collapsed) {
                        sidebar.classList.add('collapsed');
                        body.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        body.classList.remove('sidebar-collapsed');
                    }
                }
                if (toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        collapsed = !collapsed;
                        localStorage.setItem('teacherSidebarCollapsed', collapsed ? '1' : '0');
                        applyState();
                    });
                }
                // apply on load
                applyState();
            } catch (err) { console && console.error && console.error(err); }
        })();
    </script>
