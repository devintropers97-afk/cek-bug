<?php
function get($key, $default = null) { return $_GET[$key] ?? $default; }
function post($key, $default = null) { return $_POST[$key] ?? $default; }
function redirect($url) { header("Location: " . $url); exit; }
function asset($path) { return env('APP_URL') . '/assets/' . ltrim($path, '/'); }
function url($path = '') { return env('APP_URL') . '/' . ltrim($path, '/'); }
