<?php
// includes/fetch.php

// 1. NAVIGATION LINKS
$navigationLinks = $pdo->query("SELECT link_name, link_url FROM navigation")
                     ->fetchAll(PDO::FETCH_ASSOC);

// 2. HERO SECTION
$heroContent = $pdo->query("SELECT image_url, title, subtitle FROM hero_section")
                 ->fetchAll(PDO::FETCH_ASSOC);

// 3. ABOUT SECTION
$aboutContent = $pdo->query("SELECT image_url, title, description FROM about_section")
                  ->fetch(PDO::FETCH_ASSOC);

// 4. EVENTS - SINGLE OPTIMIZED QUERY VERSION
$events = $pdo->query("
    SELECT 
        id,
        title,
        description,
        image_url,
        date,
        time,
        location,
        registration_url,
        gallery_url,
        report_url,
        DATE_FORMAT(date, '%Y-%m-%d') AS date_iso,
        DATE_FORMAT(date, '%b %e') AS short_date,
        DATE_FORMAT(time, '%h:%i %p') AS formatted_time,
        CASE 
            WHEN date >= CURDATE() THEN 'upcoming'
            ELSE 'past'
        END AS event_status
    FROM events
    ORDER BY date ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Split into upcoming/past arrays
$upcoming_events = array_filter($events, function($event) {
    return $event['event_status'] === 'upcoming';
});

$past_events = array_filter($events, function($event) {
    return $event['event_status'] === 'past';
});

// Limit past events to 6 most recent
$past_events = array_slice($past_events, 0, 6);

// 5. LEADERS
$leaders = $pdo->query("SELECT image_url, name, position FROM leaders")
             ->fetchAll(PDO::FETCH_ASSOC);

// 6. MINISTRIES
$ministries = $pdo->query("SELECT image_url, title, description FROM ministries")
                ->fetchAll(PDO::FETCH_ASSOC);

// 7. ORGANIZATIONS
$organizations = $pdo->query("SELECT image_url, name, description FROM organizations")
                   ->fetchAll(PDO::FETCH_ASSOC);

// DEBUGGING - Remove after confirming it works
file_put_contents('fetch_debug.log', 
    "Upcoming Events: " . count($upcoming_events) . "\n" .
    "Past Events: " . count($past_events) . "\n" .
    print_r($upcoming_events, true) . "\n" .
    print_r($past_events, true)
);