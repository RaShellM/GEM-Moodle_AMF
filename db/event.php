<?php

/* 
 * for listening to moodle events (db)
 * event : when the user click on quizz submission
 * 
 */
$observers = array(
    array(
        'eventname' => '\mod\quiz\event\attempt_submitted',
        'callback' => '',
    ),
    array(
        
    ),
);
