<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: index.php");
    exit();
}

$fullname = htmlspecialchars($_SESSION["fullname"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dashboard-body">

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-logo">
            <div class="logo-icon">S</div>
            <div>
                <h2>Student Portal</h2>
                <p>Activity System</p>
            </div>
        </div>

        <div class="sidebar-menu">

            <p class="menu-title">MAIN MENU</p>

            <a href="dashboard.php" class="menu-link active">
                <span>▦</span> Dashboard
            </a>

            <a href="midterm.php" class="menu-link">
                <span>▤</span> Midterm Activities
            </a>

            <a href="finals.php" class="menu-link">
                <span>▤</span> Finals Activities
            </a>

            <a href="submissions.php" class="menu-link">
                <span>▣</span> My Submissions
            </a>

            <p class="menu-title">ACCOUNT</p>

            <a href="profile.php" class="menu-link">
                <span>◉</span> My Profile
            </a>

            <a href="logout.php" class="menu-link logout-link">
                <span>↪</span> Logout
            </a>

        </div>

        <div class="sidebar-footer">
            Student Activity Portal<br>
            <span>Version 1.0</span>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Dashboard</h2>
                <p>Welcome to your student portal.</p>
            </div>

            <div class="user-info">
                <div class="user-avatar">S</div>
                <div>
                    <strong><?php echo $fullname; ?></strong>
                    <small>Student Account</small>
                </div>
            </div>
        </header>

        <!-- WELCOME SECTION -->
        <section class="welcome-card">

            <div>
                <h1>Hello, <?php echo $fullname; ?>!</h1>

                <p>
                    Welcome back! Manage your activities
                    and monitor your submissions here.
                </p>
            </div>

            <div class="welcome-icon">🎓</div>

        </section>

        <!-- STATISTICS -->
        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon blue">▤</div>
                <div>
                    <p>Total Activities</p>
                    <h2>0</h2>
                    <small>Available activities</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">◷</div>
                <div>
                    <p>Midterm Activities</p>
                    <h2>0</h2>
                    <small>Midterm requirements</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">▣</div>
                <div>
                    <p>Finals Activities</p>
                    <h2>0</h2>
                    <small>Final requirements</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">✓</div>
                <div>
                    <p>My Submissions</p>
                    <h2>0</h2>
                    <small>Activities submitted</small>
                </div>
            </div>

        </section>

        <!-- ACTIVITY SECTIONS -->
        <section class="content-grid">

            <div class="content-card">
                <div class="section-heading">
                    <h3>Midterm Activities</h3>
                    <a href="midterm.php">View All</a>
                </div>

                <div class="empty-state">
                    <div>📚</div>
                    <h4>No Midterm Activities Yet</h4>
                    <p>Your midterm activities will appear here.</p>
                </div>
            </div>

            <div class="content-card">
                <div class="section-heading">
                    <h3>Finals Activities</h3>
                    <a href="finals.php">View All</a>
                </div>

                <div class="empty-state">
                    <div>📝</div>
                    <h4>No Finals Activities Yet</h4>
                    <p>Your finals activities will appear here.</p>
                </div>
            </div>

        </section>

        <!-- RECENT SUBMISSIONS -->
        <section class="content-card recent-card">

            <div class="section-heading">
                <h3>Recent Submissions</h3>
                <a href="submissions.php">View All</a>
            </div>

            <div class="empty-state">
                <div>📂</div>
                <h4>No Submissions Yet</h4>
                <p>Your uploaded activities will appear here.</p>
            </div>

        </section>

        <footer class="dashboard-footer">
            © 2026 Student Activity Portal. All Rights Reserved.
        </footer>

    </main>

</div>

</body>
</html>
