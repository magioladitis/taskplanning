<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    exit();
}

?>
<div class="container container-sm">

    <form action="./submit-form/create_list.php" method="POST" class="create-list-form">
        <h1>Δημιουργία νέας λίστας εργασιών</h1>
        <label for="title">Τίτλος</label>
        <input type="text" id="title" name="title" required>
        <button type="submit">Δημιουργία</button>
        <div class="mt-1">
            <a href="show_lists.php">Οι λίστες εργασιών μου</a>
        </div>
    </form>

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

</div>

<?php
include ('./partials/footer.php');
?>