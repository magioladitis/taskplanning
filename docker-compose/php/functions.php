<?php


function translateToGreek($word)
{
    $translations = array(
        "Pending" => "Σε αναμονή",
        "In Progress" => "Σε εξέλιξη",
        "Completed" => "Ολοκληρωμένη"
    );

    if (array_key_exists($word, $translations)) {
        return $translations[$word];
    } else {
        return "Translation not available";
    }
}