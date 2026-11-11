<?php
// Choix de la langue : ?lang=fr|ar|en  →  cookie  →  langue du navigateur  →  français
$LANGS = ['fr' => 'FR', 'ar' => 'ع', 'en' => 'EN'];

$lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? '';
if (!isset($LANGS[$lang])) {
    $nav  = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2));
    $lang = isset($LANGS[$nav]) ? $nav : 'fr';
}
if (isset($_GET['lang']) && !headers_sent()) {
    setcookie('lang', $lang, time() + 365 * 24 * 3600, '/');
}

$T   = require __DIR__ . '/../lang/' . $lang . '.php';
$dir = $lang === 'ar' ? 'rtl' : 'ltr';
