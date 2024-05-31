<?php
$page = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php">
                    <h4 class="m-0">TaskForge</h4>
                </a>
            </div>
            <ul class="nav-links">
                <li class="<?php echo $page == 'index.php' ? 'active' : ''; ?>"><a href="index.php">Αρχική</a></li>
                <?php if (!isset($_SESSION['userId'])): ?>
                    <li class="<?php echo $page == 'register.php' ? 'active' : ''; ?>"><a href="register.php">Εγγραφή</a>
                    </li>
                    <li class="<?php echo $page == 'login.php' ? 'active' : ''; ?>"><a href="login.php">Είσοδος</a></li>
                <?php endif; ?>
                <li class="<?php echo $page == 'faq.php' ? 'active' : ''; ?>"><a href="faq.php">Συχνές Ερωτήσεις</a>
                </li>
                <li id="toggleButton"><a>Αλλαγή Θέματος</a></li>
                <?php if (isset($_SESSION['userId'])): ?>
                    <li class="dropdown">
                        <a class="dropbtn">Λίστες</a>
                        <div class="dropdown-content">
                            <a href="create_list.php">Δημιουργία Λίστας</a>
                            <hr>
                            <a href="show_lists.php">Οι Λίστες εργασιών μου</a>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="dropbtn">Καλώς ήρθες <?= $_SESSION['userName'] ?></a>
                        <div class="dropdown-content">
                            <a href="account.php">Προφίλ</a>
                            <hr>
                            <a href="logout.php">Αποσύνδεση</a>
                        </div>
                    </li>

                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<script src="js/light-dark-mode.js"></script>