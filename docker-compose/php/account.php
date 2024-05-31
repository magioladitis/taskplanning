<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');

require 'conn.php';

$userId = $_SESSION['userId'] ?? null;

if (!$userId) {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: login.php");
    exit;
}

try {
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $_SESSION['errorMsg'] = "Ο χρήστης δεν βρέθηκε.";
        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['errorMsg'] = "Σφάλμα κατά την ανάκτηση του χρήστη: " . $e->getMessage();
    header("Location: login.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

?>
<div class="container container-sm">
    <form action="./submit-form/update_user.php" method="post" class="edit-user-form">
        <h1>Το προφίλ μου</h1>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="surname">Επώνυμο:</label>
        <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>

        <label for="name">Όνομα:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <button type="submit">Επεξεργασία</button>

        <?php if (isset($_SESSION['errorMsg'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['errorMsg']); ?></div>
            <?php unset($_SESSION['errorMsg']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['successMsg'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['successMsg']); ?></div>
            <?php unset($_SESSION['successMsg']); ?>
        <?php endif; ?>
    </form>
    <form action="./submit-form/delete_profile.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <input type="submit" class="delete-btn" value="Διαγραφή Λογαριασμού"
            onclick="return confirm('Είστε βέβαιοι ότι θέλετε να διαγράψετε το προφίλ σας; Αυτή η πράξη δε μπορεί να αναιρεθεί.');">
    </form>
</div>

<?php include ('./partials/footer.php'); ?>
