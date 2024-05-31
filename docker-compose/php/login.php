<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');

if (isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}
?>
<div class="container container-sm">
    <form method="post" action="./submit-form/login.php" class="login-form">
        <h2>Είσοδος Χρήστη</h2>

        <label for="email">Username ή Email:</label>
        <input type="text" id="email" name="email">
        <span class="error-message" id="error-email"></span>

        <label for="password">Κωδικός</label>
        <input type="password" id="password" name="password">
        <span class="error-message" id="error-password"></span>

        <button type="submit">Είσοδος</button>

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

        if (isset($_GET['msg'])) {
            echo '<p>' . htmlspecialchars($_GET['msg']) . '</p>';
        }
        ?>

    </form>
</div>
<script src="js/login.js"></script>
<?php
include ('./partials/footer.php');
?>