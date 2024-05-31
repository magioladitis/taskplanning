<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');
?>

<?php if (isset($_SESSION['userId'])): ?>
<div class="container container-sm">
    <h2>Εγγραφή Χρήστη</h2>
    <p>Είστε ήδη εγγεγραμμένος.</p>
</div>
<?php else: ?>
    <div class="container container-sm">
    <form method="post" action="./submit-form/register.php" class="register-form">
        <h2>Εγγραφή Χρήστη</h2>

        <?php
        if (isset($_SESSION['errorMsg'])) {
            ?>
            <div class="error-alert"><?php echo $_SESSION['errorMsg']; ?></div>
            <?php
            unset($_SESSION['errorMsg']);
        }

        if (isset($_SESSION['successMsg'])) {
            ?>
            <div class="success-alert"><?php echo $_SESSION['successMsg']; ?></div>
            <?php
            unset($_SESSION['successMsg']);
        }
        ?>

        <label for="email">Email *</label>
        <input type="email" id="email" name="email">
        <span class="error-message" id="error-email"></span>

        <label for="password">Κωδικός *</label>
        <input type="password" id="password" name="password">
        <span class="error-message" id="error-password"></span>

        <label for="username">Username *</label>
        <input type="text" id="username" name="username">
        <span class="error-message" id="error-username"></span>

        <label for="name">Όνομα</label>
        <input type="text" id="name" name="name">

        <label for="surname">Επώνυμο</label>
        <input type="text" id="surname" name="surname">

        <label for="surname">Simplepush key</label>
        <input type="text" id="simplepush_key" name="simplepush_key">

        <button type="submit">Εγγραφή</button>

    </form>
</div>
<?php endif; ?>

<script src="js/register.js"></script>
<?php
include ('./partials/footer.php');
?>