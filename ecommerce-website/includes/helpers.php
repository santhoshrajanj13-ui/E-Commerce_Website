<?php
/**
 * Helper Functions
 * Used across product listing/details pages (placeholder visuals
 * are used instead of binary image files so the project runs
 * immediately without needing external image assets).
 */

function categoryEmoji($category_id) {
    $map = [
        1 => '💻', // Electronics
        2 => '👕', // Fashion
        3 => '🍳', // Home & Kitchen
        4 => '📚', // Books
        5 => '⚽', // Sports
    ];
    return isset($map[$category_id]) ? $map[$category_id] : '📦';
}

function formatDate($datetime) {
    return date('d M Y, h:i A', strtotime($datetime));
}
?>
