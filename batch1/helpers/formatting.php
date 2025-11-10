<?php
function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
function format_date($d) { return date('d M Y', strtotime($d)); }
