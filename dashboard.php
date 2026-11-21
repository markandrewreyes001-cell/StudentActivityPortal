
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: index.php");
    exit();
}

require_once "config/database.php";

$studentId = (int) $_SESSION["student_id"];
$fullname = htmlspecialchars($_SESSION["fullname"]);

$sql = "SELECT
            a.id,
            a.title,
            a.description,
            a.term,
            a.deadline,
            a.created_at,
            s.file_name,
            s.file_path,
            s.submitted_at
        FROM submissions s
        INNER JOIN activities a ON s.activity_id = a.id
        WHERE s.student_id = ?
        ORDER BY s.submitted_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();

$result = $stmt->get_result();

$midtermActivities = [];
$finalsActivities = [];
$recentSubmissions = [];

$totalActivities = 0;
$totalMidterm = 0;
$totalFinals = 0;
$totalSubmissions = 0;

$seenActivities = [];

while ($row = $result->fetch_assoc()) {

    $totalSubmissions++;

    $recentSubmissions[] = $row;

    if (!isset($seenActivities[$row["id"]])) {

        $seenActivities[$row["id"]] = true;
        $totalActivities++;

        if ($row["term"] === "Midterm") {
            $totalMidterm++;
            $midtermActivities[] = $row;
        }

        if ($row["term"] === "Finals") {
            $totalFinals++;
            $finalsActivities[] = $row;
        }
    }
}

$stmt->close();

function displayActivities($activities) {

    if (empty($activities)) {
        echo '
            <div class="empty-state">
                <div>📚</div>
                <h4>No Activities Yet</h4>
                <p>Your uploaded activities will appear here.</p>
            </div>
        ';
        return;
    }

    echo '<div class="activity-list">';

    foreach ($activities as $activity) {

        $title = htmlspecialchars($activity["title"]);
        $description = htmlspecialchars($activity["description"]);
        $date = date("M d, Y", strtotime($activity["submitted_at"]));
        $filePath = htmlspecialchars($activity["file_path"], ENT_QUOTES, "UTF-8");
        $fileName = htmlspecialchars($activity["file_name"]);

        echo '
            <a href="' . $filePath . '"
               target="_blank"
               rel="noopener noreferrer"
               class="activity-item activity-clickable">

                <div class="activity-icon">📄</div>

                <div class="activity-details">
                    <h4>' . $title . '</h4>
                    <p>' . $description . '</p>
                    <small>' . $fileName . '</small><br>
                    <small>Uploaded: ' . $date . '</small>
                </div>

                <span class="activity-status">View File ↗</span>

            </a>
        ';
    }

    echo '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            background: rgba(255,255,255,0.03);
        }

        .activity-icon {
            font-size: 25px;
            min-width: 35px;
        }

        .activity-details {
            flex: 1;
            min-width: 0;
        }

        .activity-details h4 {
            margin: 0 0 5px;
        }

        .activity-details p {
            margin: 0 0 5px;
            font-size: 13px;
            opacity: 0.75;
        }

        .activity-details small {
            font-size: 12px;
            opacity: 0.65;
        }

        .activity-status {
            color: #4ade80;
            font-size: 12px;
            font-weight: bold;
        }

        .activity-clickable {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .activity-clickable:hover {
            background: rgba(59,130,246,0.12);
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .activity-clickable .activity-status {
            white-space: nowrap;
        }

        .activity-clickable:hover .activity-status {
            color: #60a5fa;
        }

        .recent-submission {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .recent-submission:last-child {
            border-bottom: none;
        }

        .recent-submission h4 {
            margin: 0 0 5px;
        }

        .recent-submission p {
            margin: 0;
            font-size: 12px;
            opacity: 0.7;
        }

        .term-label {
            font-size: 11px;
            font-weight: bold;
            padding: 5px 9px;
            border-radius: 20px;
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            white-space: nowrap;
        }
    </style>
</head>

<body class="dashboard-body">

<div class="dashboard-container">

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

        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon blue">▤</div>
                <div>
                    <p>Total Activities</p>
                    <h2><?php echo $totalActivities; ?></h2>
                    <small>Your uploaded activities</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">◷</div>
                <div>
                    <p>Midterm Activities</p>
                    <h2><?php echo $totalMidterm; ?></h2>
                    <small>Uploaded Midterm activities</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">▣</div>
                <div>
                    <p>Finals Activities</p>
                    <h2><?php echo $totalFinals; ?></h2>
                    <small>Uploaded Finals activities</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">✓</div>
                <div>
                    <p>My Submissions</p>
                    <h2><?php echo $totalSubmissions; ?></h2>
                    <small>Total uploaded submissions</small>
                </div>
            </div>

        </section>

        <section class="content-grid">

            <div class="content-card">

                <div class="section-heading">
                    <h3>Midterm Activities</h3>
                    <a href="midterm.php">View All</a>
                </div>

                <?php displayActivities($midtermActivities); ?>

            </div>

            <div class="content-card">

                <div class="section-heading">
                    <h3>Finals Activities</h3>
                    <a href="finals.php">View All</a>
                </div>

                <?php displayActivities($finalsActivities); ?>

            </div>

        </section>

        <section class="content-card recent-card">

            <div class="section-heading">
                <h3>Recent Submissions</h3>
                <a href="submissions.php">View All</a>
            </div>

            <?php if (empty($recentSubmissions)): ?>

                <div class="empty-state">
                    <div>📂</div>
                    <h4>No Submissions Yet</h4>
                    <p>Your uploaded activities will appear here.</p>
                </div>

            <?php else: ?>

                <?php
                $recentCount = 0;

                foreach ($recentSubmissions as $submission):

                    if ($recentCount >= 5) {
                        break;
                    }

                    $recentCount++;

                    $recentTitle = htmlspecialchars($submission["title"]);
                    $recentFile = htmlspecialchars($submission["file_name"]);
                    $recentTerm = htmlspecialchars($submission["term"]);
                    $recentDate = date(
                        "M d, Y h:i A",
                        strtotime($submission["submitted_at"])
                    );

                    $recentPath = htmlspecialchars(
                        $submission["file_path"],
                        ENT_QUOTES,
                        "UTF-8"
                    );
                ?>

                    <a href="<?php echo $recentPath; ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="recent-submission activity-clickable">

                        <div>
                            <h4><?php echo $recentTitle; ?></h4>

                            <p>
                                <?php echo $recentFile; ?> •
                                <?php echo $recentDate; ?>
                            </p>
                        </div>

                        <span class="term-label">
                            <?php echo $recentTerm; ?> ↗
                        </span>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

        <footer class="dashboard-footer">
            © 2026 Student Activity Portal. All Rights Reserved.
        </footer>

    </main>

</div>

</body>
</html>
