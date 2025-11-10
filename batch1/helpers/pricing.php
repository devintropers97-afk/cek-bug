<?php
function calculate_price($pages) { return $pages * 350000; }
function get_tier($orders) {
    if ($orders >= 75) return ['tier' => 'MAX', 'commission' => 55];
    if ($orders >= 50) return ['tier' => '3', 'commission' => 50];
    if ($orders >= 10) return ['tier' => '2', 'commission' => 40];
    return ['tier' => '1', 'commission' => 30];
}
