<?php
include 'includes/admin-auth.php';
include 'includes/admin-header.php';
include 'includes/flash-alert.php';
include '../config/dbconn.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
$tourIdSearch = '';

$adminRole = $_SESSION['admin_role'];
$adminId = (int) $_SESSION['admin_id'];

$sessionFilter = $adminRole === 'driver'
    ? " AND admin_id = $adminId"
    : "";

$pendingSessions = mysqli_query($conn, "
    SELECT *
    FROM tour_sessions
    WHERE status = 'Pending' $sessionFilter
    ORDER BY created_at DESC
");

$activeSessions = mysqli_query($conn, "
    SELECT *
    FROM tour_sessions
    WHERE status = 'Active' $sessionFilter
    ORDER BY activated_at DESC
");

$inactiveSessions = mysqli_query($conn, "
    SELECT *
    FROM tour_sessions
    WHERE status = 'Inactive' $sessionFilter
    ORDER BY updated_at DESC
");

$endedSessions = mysqli_query($conn, "
    SELECT *
    FROM tour_sessions
    WHERE status = 'Ended' $sessionFilter
    ORDER BY ended_at DESC
");

$expiredSessions = mysqli_query($conn, "
    SELECT *
    FROM tour_sessions
    WHERE status = 'Expired' $sessionFilter
    ORDER BY expired_at DESC
");
?>

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/admin-sidebar.php'; ?>

        <main class="col-md-10 col-lg-10 px-3 px-md-4 py-4">

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Access Management</h3>
                <p class="text-muted mb-0">
                    Manage visitor access requests and tour sessions.
                </p>
            </div>
            
            <div class="row g-3 align-items-center mb-4">
                <div class="col-12 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="tourIdSearch" class="form-control"
                            placeholder="Search Tour ID..."
                            value="<?= htmlspecialchars($tourIdSearch) ?>">
                        <button type="button" id="clearTourSearch" class="btn btn-outline-secondary">
                            Clear
                        </button>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-start" id="sessionTabs">
                        <button type="button" class="btn btn-warning active" data-target="pendingCard">
                            Pending
                        </button>
                        <button type="button" class="btn btn-outline-success" data-target="activeCard">
                            Active
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-target="inactiveCard">
                            Inactive
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-target="endedCard">
                            Ended
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-target="expiredCard">
                            Expired
                        </button>
                    </div>
                </div>
            </div>


            <div class="card border-0 shadow-sm session-card" id="pendingCard">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-hourglass-split text-warning me-2"></i>
                        Pending Requests
                    </h5>
                    <small class="text-muted">Visitors waiting for administrator approval</small>
                </div>              
                <div class="card-body p-0">
                    <?php if ($pendingSessions && mysqli_num_rows($pendingSessions) > 0): ?>
                        <div class="row g-2">
                            <?php while ($session = mysqli_fetch_assoc($pendingSessions)): ?>
                                <div class="col-12 col-lg-8 col-xxl-4">
                                    <div class="border border-0 p-3 h-100 visitor-card"
                                        data-tour-id="<?= htmlspecialchars($session['tour_id']) ?>">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">
                                                        <?= htmlspecialchars($session['tour_id']) ?>
                                                    </h6>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3 mb-2 small">
                                                    <span>
                                                        <span class="text-muted">Access Code:</span>
                                                        <strong><?= htmlspecialchars($session['access_code']) ?></strong>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-device-ssd text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['device_type'] ?? 'Unknown') ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-globe text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['browser'] ?? 'Unknown') ?>
                                                    </span>
                                                </div>

                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    Requested: <?= htmlspecialchars($session['created_at']) ?>
                                                </small>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="controller/accessController.php" method="POST">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="activate">

                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle me-1"></i> Activate
                                                    </button>
                                                </form>

                                                <form action="controller/accessController.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to expire this access request?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="expire">

                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-x-circle me-1"></i> Expire
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No pending access requests.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
      
            <div class="card border-0 shadow-sm mt-4 session-card" id="activeCard">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-people-fill text-success me-2"></i>
                        Active Sessions
                    </h5>
                    <small class="text-muted">Visitors currently allowed to access the map</small>
                </div>

                <div class="card-body p-0">
                    <?php if ($activeSessions && mysqli_num_rows($activeSessions) > 0): ?>
                        <div class="row g-2">
                            <?php while ($session = mysqli_fetch_assoc($activeSessions)): ?>
                                <div class="col-12 col-lg-8 col-xxl-4">
                                    <div class="border border-0 p-3 h-100 visitor-card"
                                        data-tour-id="<?= htmlspecialchars($session['tour_id']) ?>">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">
                                                        <?= htmlspecialchars($session['tour_id']) ?>
                                                    </h6>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3 mb-2 small">
                                                    <span>
                                                        <span class="text-muted">Access Code:</span>
                                                        <strong><?= htmlspecialchars($session['access_code']) ?></strong>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-device-ssd text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['device_type'] ?? 'Unknown') ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-globe text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['browser'] ?? 'Unknown') ?>
                                                    </span>
                                                </div>

                                                <div class="d-flex flex-column gap-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        Activated: <?= htmlspecialchars($session['activated_at'] ?? '') ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        Last Activity: <?= htmlspecialchars($session['last_activity_at'] ?? '') ?>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <form action="controller/accessController.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to deactivate this visitor access?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="deactivate">

                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="bi bi-pause-circle me-1"></i> Deactivate
                                                    </button>
                                                </form>

                                                <form action="controller/accessController.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently end this tour session? This action cannot be undone.');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="end">

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-stop-circle me-1"></i> End Tour
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No active sessions found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
  
            <div class="card border-0 shadow-sm mt-4 session-card" id="inactiveCard">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pause-circle text-warning me-2"></i>
                        Inactive Sessions
                    </h5>
                    <small class="text-muted">Visitors whose access has been temporarily deactivated</small>
                </div>

                <div class="card-body p-0">
                    <?php if ($inactiveSessions && mysqli_num_rows($inactiveSessions) > 0): ?>
                        <div class="row g-2">
                            <?php while ($session = mysqli_fetch_assoc($inactiveSessions)): ?>
                                <div class="col-12 col-lg-8 col-xxl-4">
                                    <div class="border border-0 p-3 h-100 visitor-card"
                                        data-tour-id="<?= htmlspecialchars($session['tour_id']) ?>">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">
                                                        <?= htmlspecialchars($session['tour_id']) ?>
                                                    </h6>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3 mb-2 small">
                                                    <span>
                                                        <span class="text-muted">Access Code:</span>
                                                        <strong><?= htmlspecialchars($session['access_code']) ?></strong>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-device-ssd text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['device_type'] ?? 'Unknown') ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-globe text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['browser'] ?? 'Unknown') ?>
                                                    </span>
                                                </div>

                                                <small class="text-muted">
                                                    <i class="bi bi-clock-history me-1"></i>
                                                    Last Activity: <?= htmlspecialchars($session['last_activity_at'] ?? '') ?>
                                                </small>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="controller/accessController.php" method="POST"
                                                    onsubmit="return confirm('Reactivate this visitor access?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="reactivate">

                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-arrow-clockwise me-1"></i> Reactivate
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No inactive sessions found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
     
            <div class="card border-0 shadow-sm mt-4 session-card" id="endedCard">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-stop-circle text-danger me-2"></i>
                        Ended Sessions
                    </h5>
                    <small class="text-muted">History of completed visitor tour sessions</small>
                </div>

                <div class="card-body p-0">
                    <?php if ($endedSessions && mysqli_num_rows($endedSessions) > 0): ?>
                        <div class="row g-2">
                            <?php while ($session = mysqli_fetch_assoc($endedSessions)): ?>
                                <div class="col-12 col-lg-8 col-xxl-4">
                                    <div class="border border-0 p-3 h-100 visitor-card"
                                        data-tour-id="<?= htmlspecialchars($session['tour_id']) ?>">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">
                                                        <?= htmlspecialchars($session['tour_id']) ?>
                                                    </h6>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3 mb-2 small">
                                                    <span>
                                                        <span class="text-muted">Access Code:</span>
                                                        <strong><?= htmlspecialchars($session['access_code']) ?></strong>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-device-ssd text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['device_type'] ?? 'Unknown') ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-globe text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['browser'] ?? 'Unknown') ?>
                                                    </span>
                                                </div>

                                                <div class="d-flex flex-column gap-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        Activated: <?= htmlspecialchars($session['activated_at'] ?? '') ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-x me-1"></i>
                                                        Ended: <?= htmlspecialchars($session['ended_at'] ?? '') ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No ended sessions found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 session-card" id="expiredCard">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clock-history text-warning me-2"></i>
                        Expired Sessions
                    </h5>
                    <small class="text-muted">Expired access requests that can be restored</small>
                </div>

                <div class="card-body p-0">
                    <?php if ($expiredSessions && mysqli_num_rows($expiredSessions) > 0): ?>
                        <div class="row g-2">
                            <?php while ($session = mysqli_fetch_assoc($expiredSessions)): ?>
                                <div class="col-12 col-lg-8 col-xxl-4">
                                    <div class="border border-0 p-3 h-100 visitor-card"
                                        data-tour-id="<?= htmlspecialchars($session['tour_id']) ?>">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">
                                                        <?= htmlspecialchars($session['tour_id']) ?>
                                                    </h6>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3 mb-2 small">
                                                    <span>
                                                        <span class="text-muted">Access Code:</span>
                                                        <strong><?= htmlspecialchars($session['access_code']) ?></strong>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-device-ssd text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['device_type'] ?? 'Unknown') ?>
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-globe text-muted me-1"></i>
                                                        <?= htmlspecialchars($session['browser'] ?? 'Unknown') ?>
                                                    </span>
                                                </div>

                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-x me-1"></i>
                                                    Expired: <?= htmlspecialchars($session['expired_at'] ?? '') ?>
                                                </small>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="controller/accessController.php" method="POST"
                                                    onsubmit="return confirm('Restore this expired request to Pending?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input type="hidden" name="tour_sessions_id" value="<?= $session['tour_sessions_id'] ?>">
                                                    <input type="hidden" name="action" value="restore">

                                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No expired sessions found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>

    </div>
</div>

<script>
    const tourIdSearch = document.getElementById('tourIdSearch');
    const clearTourSearch = document.getElementById('clearTourSearch');

    function filterTourSessions() {
        const searchValue = tourIdSearch.value.trim().toLowerCase();

        document.querySelectorAll('.table tbody').forEach(tbody => {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const sessionRows = rows.filter(row => {
                const firstCell = row.querySelector('td:first-child');
                return firstCell && !firstCell.hasAttribute('colspan');
            });

            let visibleCount = 0;

            sessionRows.forEach(row => {
                const tourId = row.querySelector('td:first-child').textContent.trim().toLowerCase();
                const match = tourId.includes(searchValue);

                row.classList.toggle('d-none', !match);

                if (match) visibleCount++;
            });

            let emptyRow = tbody.querySelector('.search-empty-row');

            if (sessionRows.length > 0 && visibleCount === 0) {
                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.className = 'search-empty-row';
                    emptyRow.innerHTML = `
                        <td colspan="8" class="text-center text-muted py-4">
                            No matching Tour ID found.
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                }
            } else if (emptyRow) {
                emptyRow.remove();
            }
        });
                // Automatically switch to the tab containing the matching Tour ID
        if (searchValue !== '') {

            const matchingCard = Array.from(sessionCards).find(card => {
                return Array.from(card.querySelectorAll('tbody tr')).some(row => {
                    const firstCell = row.querySelector('td:first-child');

                    return firstCell &&
                        !firstCell.hasAttribute('colspan') &&
                        !row.classList.contains('d-none');
                });
            });

            if (matchingCard) {
                const matchingTab = document.querySelector(
                    `#sessionTabs button[data-target="${matchingCard.id}"]`
                );

                if (matchingTab && !matchingCard.classList.contains('d-none')) {
                    matchingTab.click();
                } else if (matchingTab) {
                    matchingTab.click();
                }
            }
        }
    }

    tourIdSearch.addEventListener('input', filterTourSessions);

    clearTourSearch.addEventListener('click', function () {
        tourIdSearch.value = '';
        filterTourSessions();
        tourIdSearch.focus();
    });

    filterTourSessions();

    // Session Tab Navigation
    const sessionTabs = document.querySelectorAll('#sessionTabs button');
    const sessionCards = document.querySelectorAll('.session-card');

    sessionTabs.forEach(button => {
        button.addEventListener('click', function () {

            const targetCard = this.dataset.target;

            // Hide all session cards
            sessionCards.forEach(card => {
                card.classList.add('d-none');
            });

            // Show selected card
            document.getElementById(targetCard).classList.remove('d-none');

            // Reset button styles
            sessionTabs.forEach(tab => {
                tab.classList.remove('active', 'btn-warning', 'btn-success', 'btn-secondary', 'btn-danger');
                tab.classList.add('btn-outline-warning', 'btn-outline-success', 'btn-outline-secondary', 'btn-outline-danger');
            });

            // Highlight selected button
            this.classList.add('active');

            const colors = {
                pendingCard: 'warning',
                activeCard: 'success',
                inactiveCard: 'secondary',
                endedCard: 'danger',
                expiredCard: 'warning'
            };

            const color = colors[targetCard];

            this.classList.remove(`btn-outline-${color}`);
            this.classList.add(`btn-${color}`);
        });
    });

    // Show Pending by default
    sessionCards.forEach(card => card.classList.add('d-none'));
    document.getElementById('pendingCard').classList.remove('d-none');
</script>

<?php include 'includes/admin-footer.php'; ?>