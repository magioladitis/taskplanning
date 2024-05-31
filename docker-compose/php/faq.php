<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');
?>
<div class="container">
    <div class="accordion">
        <div class="accordion-item">
            <button class="accordion-header">Πώς μπορώ να τροποποιήσω το θέμα της σελίδας</button>
            <div class="accordion-content">
                <p>Πατήστε τo εικονίδιo Αλλαγή Θέματος που βρίσκονται στην επάνω γραμμή του μενού.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Εγγραφή στην ιστοσελίδα μας</button>
            <div class="accordion-content">
                <p>Μπορείτε να εγγραφείτε ανατρέχοντας σε αυτόν τον <a href="register.php">σύνδεσμο.</a></p>
            </div>
        </div>
    </div>

</div>
<script src="js/index.js"></script>
<?php
include ('./partials/footer.php');
?>