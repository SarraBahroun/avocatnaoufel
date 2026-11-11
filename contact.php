<?php
// Reçoit les formulaires "Rendez-vous" et "Question" et les envoie par email au cabinet.
require __DIR__ . '/config/site.php';

$langue = in_array($_POST['lang'] ?? '', ['fr', 'ar', 'en'], true) ? $_POST['lang'] : 'fr';

function retour(string $statut): void {
    global $langue;
    header('Location: index.php?lang=' . $langue . '&envoi=' . $statut . '#rendez-vous');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') retour('');

// Anti-spam : champ caché rempli => robot
if (!empty($_POST['site_web'])) retour('ok');

$clean = fn($k) => trim(str_replace(["\r", "\n"], ' ', strip_tags($_POST[$k] ?? '')));

$type      = ($_POST['type'] ?? '') === 'question' ? 'question' : 'rdv';
$nom       = $clean('nom');
$telephone = $clean('telephone');
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
$message   = trim(strip_tags($_POST['message'] ?? ''));
$date      = $clean('date');
$creneau   = $clean('creneau');
$domaine   = $clean('domaine');

// Validation
if ($nom === '') retour('invalide');
if ($type === 'rdv' && ($telephone === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))) retour('invalide');
if ($type === 'question' && ($email === '' || $message === '')) retour('invalide');

if ($type === 'rdv') {
    $sujet = "Demande de rendez-vous — $nom";
    $corps = "NOUVELLE DEMANDE DE RENDEZ-VOUS\n\n"
           . "Nom : $nom\nTéléphone : $telephone\nEmail : " . ($email ?: '—') . "\n"
           . "Date souhaitée : " . date('d/m/Y', strtotime($date)) . "\nCréneau : $creneau\nDomaine : $domaine\n\n"
           . "Objet :\n" . ($message ?: '—') . "\n";
} else {
    $sujet = "Question d'un visiteur — $nom";
    $corps = "NOUVELLE QUESTION\n\n"
           . "Nom : $nom\nEmail : $email\nTéléphone : " . ($telephone ?: '—') . "\n\n"
           . "Question :\n$message\n";
}
$corps .= "\n---\nEnvoyé depuis le site du cabinet (version " . strtoupper($langue) . ") le " . date('d/m/Y à H:i');

$domaineSite = preg_replace('/^www\./', '', $_SERVER['SERVER_NAME'] ?? 'localhost');
$headers  = "From: Site Cabinet Bahroun <no-reply@$domaineSite>\r\n";
if ($email) $headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n";

$ok = @mail($SITE['email_reception'], '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $headers);
retour($ok ? 'ok' : 'erreur');
