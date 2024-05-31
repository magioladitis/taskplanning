<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');
?>

<div class="container">
    <div class="accordion">
        <div class="accordion-item">
            <button class="accordion-header">Συνοπτική Περιγραφή</button>
            <div class="accordion-content">
                <p>Η πλατφόρμα μας παρέχει έναν διαδραστικό ιστότοπο για τη διαχείριση λιστών εργασιών, επιτρέποντας
                    στους χρήστες να οργανώνουν, να παρακολουθούν και να προωθούν τις εργασίες τους με απόλυτη ευκολία
                    και αποτελεσματικότητα</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Ανάθεση Εργασιών</button>
            <div class="accordion-content">
                <p>Αναθέστε εργασίες σε άλλους χρήστες της πλατφόρμας, διευκολύνοντας την ομαδική εργασία και την
                    καλύτερη διαχείριση των προθεσμιών.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Προσθήκη Εργασιών</button>
            <div class="accordion-content">
                <p>Οι χρήστες μπορούν να προσθέτουν νέες εργασίες σε λίστες, ορίζοντας τίτλο, περιγραφή, προθεσμία και
                    προτεραιότητα για κάθε εργασία.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Επεξεργασία και Διαγραφή Εργασιών</button>
            <div class="accordion-content">
                <p>Επεξεργαστείτε λεπτομέρειες ή διαγράψτε εργασίες εύκολα. Κάθε εργασία μπορεί να τροποποιηθεί σε
                    περίπτωση αλλαγής στις απαιτήσεις ή τις προθεσμίες.</p>
            </div>
        </div>
    </div>

</div>
<script src="js/index.js"></script>
<?php
include ('./partials/footer.php');
?>