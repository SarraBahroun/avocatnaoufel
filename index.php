<?php
require __DIR__ . '/config/site.php';
require __DIR__ . '/config/lang.php';

$e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
$t = fn($k) => $e($T[$k]);
$ltr = fn($s) => '<span dir="ltr">' . $e($s) . '</span>';   // numéros / emails dans la page arabe

$statut = $_GET['envoi'] ?? '';
$emails = array_values(array_filter([$SITE['email'] ?? '', $SITE['email2'] ?? '']));
$mailto = 'mailto:' . implode(',', $emails);
$mapSrc = "https://maps.google.com/maps?q=" . rawurlencode($SITE['maps_query']) . "&z=17&hl={$lang}&output=embed";
$mapLnk = "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($SITE['maps_query']);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t('meta_title') ?></title>
    <meta name="description" content="<?= $t('meta_desc') ?>">
    <meta name="theme-color" content="#0a192f">
    <?php foreach ($LANGS as $code => $_): ?>
    <link rel="alternate" hreflang="<?= $code ?>" href="?lang=<?= $code ?>">
    <?php endforeach; ?>
    <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <a href="?lang=<?= $lang ?>" class="nav-logo-container">
        <img src="assets/images/logo.svg" alt="Logo">
        <div class="logo-text">
            <span class="logo-name"><?= $t('logo_name') ?></span>
            <span class="logo-slogan"><?= $t('logo_title') ?></span>
        </div>
    </a>
    <button class="burger" aria-label="Menu" aria-expanded="false"><i class="fas fa-bars"></i></button>
    <nav class="nav-links">
        <a href="#about"><?= $t('nav_cabinet') ?></a>
        <a href="#expertises"><?= $t('nav_expertises') ?></a>
        <a href="#zone"><?= $t('nav_zone') ?></a>
        <?php if (!empty($SITE['videos'])): ?><a href="#medias"><?= $t('nav_medias') ?></a><?php endif; ?>
        <a href="#contact"><?= $t('nav_contact') ?></a>
        <a href="#rendez-vous" class="btn-nav"><?= $t('nav_rdv') ?></a>
        <div class="lang-switch">
            <?php foreach ($LANGS as $code => $label): ?>
                <a href="?lang=<?= $code ?>" class="<?= $code === $lang ? 'active' : '' ?>" lang="<?= $code ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <img src="assets/images/logo.svg" alt="" class="hero-logo animate-text">
        <h1 class="animate-text"><?= $t('hero_h1') ?></h1>
        <div class="line-grow"></div>
        <p class="hero-name delay-2"><?= $t('hero_name') ?></p>
        <p class="hero-title delay-3"><?= $t('hero_title') ?></p>
        <div class="hero-cta delay-4">
            <a href="#rendez-vous" class="btn btn-gold"><i class="far fa-calendar-check"></i> <?= $t('btn_rdv') ?></a>
            <a href="tel:<?= $e($SITE['tel_raw']) ?>" class="btn btn-outline"><i class="fas fa-phone"></i> <?= $t('btn_call') ?></a>
        </div>
    </div>
</section>

<section id="about" class="section-padding">
    <div class="flex-bio">
        <div class="bio-img"><img src="assets/images/maitre-robe.png" alt="<?= $t('hero_name') ?>" loading="lazy"></div>
        <div class="bio-text">
            <h2><?= $t('about_h2') ?></h2>
            <div class="quote"><?= $t('quote') ?></div>
            <p><?= $t('about_p1') ?></p>
            <p><?= $t('about_p2') ?></p>
            <ul class="parcours">
                <?php foreach ($T['parcours'] as [$icon, $txt]): ?>
                    <li><i class="fas <?= $icon ?>"></i><span><?= $e($txt) ?></span></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<section id="expertises" class="section-padding" style="background: rgba(255,255,255,0.02);">
    <h2 class="section-title"><?= $t('exp_h2') ?></h2>
    <p class="section-sub"><?= $t('exp_sub') ?></p>
    <div class="carousel" data-carousel>
        <button type="button" class="car-btn car-prev" aria-label="<?= $t('car_prev') ?>"><i class="fas fa-chevron-left"></i></button>
        <div class="car-track">
        <?php foreach ($T['expertises'] as [$icon, $titre, $items]): ?>
            <div class="spec-card">
                <i class="fas <?= $icon ?>"></i>
                <h3><?= $e($titre) ?></h3>
                <ul><?php foreach ($items as $it): ?><li><?= $e($it) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="car-btn car-next" aria-label="<?= $t('car_next') ?>"><i class="fas fa-chevron-right"></i></button>
    </div>
    <div class="car-dots"></div>
</section>

<section id="zone" class="section-padding">
    <h2 class="section-title"><?= $t('zone_h2') ?></h2>
    <p class="section-sub"><?= $t('zone_sub') ?></p>
    <div class="grid-3">
        <?php foreach ($T['zone'] as [$icon, $titre, $txt]): ?>
            <div class="zone-card">
                <i class="fas <?= $icon ?>"></i>
                <h3><?= $e($titre) ?></h3>
                <p><?= $e($txt) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="stats-bar">
    <?php foreach ($T['stats'] as [$icon, $num, $label]): ?>
        <div class="stat-item"><i class="fas <?= $icon ?>"></i><span class="stat-number"><?= $e($num) ?></span><span class="stat-label"><?= $e($label) ?></span></div>
    <?php endforeach; ?>
</section>

<section id="methode" class="section-padding">
    <h2 class="section-title" style="margin-bottom:50px;"><?= $t('met_h2') ?></h2>
    <div class="method-container">
        <?php foreach ($T['methode'] as $i => [$titre, $txt]): ?>
            <div class="method-item"><div class="step-num">0<?= $i + 1 ?></div><h3><?= $e($titre) ?></h3><p><?= $e($txt) ?></p></div>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($SITE['videos'])): ?>
<div class="divider" aria-hidden="true"><span></span><i class="fas fa-tv"></i><span></span></div>
<section id="medias" class="section-padding">
    <h2 class="section-title"><?= $t('media_h2') ?></h2>
    <p class="section-sub"><?= $t('media_sub') ?></p>
    <div class="videos">
        <?php foreach ($SITE['videos'] as $v): ?>
            <div class="video-wrap">
                <iframe src="https://www.facebook.com/plugins/video.php?href=<?= rawurlencode($v) ?>&amp;show_text=false&amp;locale=<?= ['fr' => 'fr_FR', 'ar' => 'ar_AR', 'en' => 'en_US'][$lang] ?? 'fr_FR' ?>"
                        title="<?= $t('media_video') ?>" loading="lazy" allowfullscreen
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section id="rendez-vous" class="section-padding" style="background: rgba(255,255,255,0.02);">
    <h2 class="section-title"><?= $t('rdv_h2') ?></h2>
    <p class="section-sub"><?= $t('rdv_sub') ?></p>

    <div class="rdv-wrap">
        <div class="form-card">
            <?php if ($statut === 'ok'): ?>
                <div class="alert alert-ok"><i class="fas fa-circle-check"></i> <?= $t('ok') ?></div>
            <?php elseif ($statut === 'erreur'): ?>
                <div class="alert alert-err"><i class="fas fa-triangle-exclamation"></i> <?= $t('err') ?></div>
            <?php elseif ($statut === 'invalide'): ?>
                <div class="alert alert-err"><i class="fas fa-triangle-exclamation"></i> <?= $t('invalid') ?></div>
            <?php endif; ?>

            <!-- Formulaire DEMANDE DE RENDEZ-VOUS -->
            <form action="contact.php" method="POST">
                <input type="hidden" name="type" value="rdv">
                <input type="hidden" name="lang" value="<?= $lang ?>">
                <input type="text" name="site_web" class="hp" tabindex="-1" autocomplete="off">
                <div class="form-grid">
                    <div class="field"><label><?= $t('f_name') ?> *</label><input type="text" name="nom" required maxlength="100"></div>
                    <div class="field"><label><?= $t('f_phone') ?> *</label><input type="tel" name="telephone" required maxlength="30" placeholder="+216 ..." dir="ltr"></div>
                    <div class="field full"><label><?= $t('f_email') ?></label><input type="email" name="email" maxlength="150" dir="ltr"></div>
                    <div class="field"><label><?= $t('f_date') ?> *</label><input type="date" name="date" required min="<?= date('Y-m-d') ?>"></div>
                    <div class="field"><label><?= $t('f_slot') ?> *</label>
                        <select name="creneau" required>
                            <?php foreach ($T['slots'] as $s): ?><option value="<?= $e($s) ?>"><?= $e($s) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field full"><label><?= $t('f_domain') ?></label>
                        <select name="domaine">
                            <?php foreach ($T['domains'] as $d): ?><option value="<?= $e($d) ?>"><?= $e($d) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field full"><label><?= $t('f_object') ?></label><textarea name="message" maxlength="3000" placeholder="<?= $t('f_object_ph') ?>"></textarea></div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-gold"><i class="fas fa-paper-plane"></i> <?= $t('send_rdv') ?></button>
                </div>
                <p class="form-note"><?= $t('note_rdv') ?></p>
                <p class="form-note"><?= $t('note_q') ?></p>
            </form>
        </div>

        <aside>
            <div class="quick-card">
                <h3><?= $t('quick_h3') ?></h3>
                <p><?= $t('quick_p') ?></p>
                <a href="tel:<?= $e($SITE['tel_raw']) ?>" class="btn btn-gold"><i class="fas fa-phone"></i> <?= $ltr($SITE['tel']) ?></a>
                <a href="<?= $e($mailto) ?>" class="btn btn-outline"><i class="fas fa-envelope"></i> <?= $t('btn_mail') ?></a>
            </div>
            <div class="quick-card">
                <h3><i class="far fa-clock"></i> <?= $t('hours_h3') ?></h3>
                <table class="hours">
                    <?php foreach ($T['hours'] as $jour => $h): ?>
                        <tr><td><?= $e($jour) ?></td><td><?= $ltr($h) ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </aside>
    </div>
</section>

<section id="contact" class="section-padding" style="background: var(--navy-light);">
    <h2 class="section-title" style="margin-bottom:50px;"><?= $t('contact_h2') ?></h2>
    <div class="grid-4">
        <a class="contact-card" href="tel:<?= $e($SITE['tel_raw']) ?>"><i class="fas fa-phone"></i><h4><?= $t('c_phone') ?></h4><p><?= $t('c_tel') ?> <?= $ltr($SITE['tel']) ?><br><?= $t('c_fax') ?> <?= $ltr($SITE['fax']) ?></p></a>
        <a class="contact-card" href="#rendez-vous"><i class="far fa-clock"></i><h4><?= $t('c_hours') ?></h4><p><?= $T['c_hours_short'] ?></p></a>
        <a class="contact-card" href="<?= $e($mailto) ?>"><i class="fas fa-envelope"></i><h4><?= $t('c_email') ?></h4><p><?= implode('<br>', array_map($ltr, $emails)) ?></p></a>
        <a class="contact-card" href="<?= $e($mapLnk) ?>" target="_blank" rel="noopener"><i class="fas fa-location-dot"></i><h4><?= $t('c_addr') ?></h4><p><?= $t('addr1') ?><br><?= $t('addr2') ?></p></a>
    </div>
</section>

<div class="map-wrap">
    <iframe src="<?= $e($mapSrc) ?>" title="<?= $t('map_title') ?>" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

<footer>
    <img src="assets/images/logo.svg" alt="">
    <p class="foot-name"><?= $t('hero_name') ?></p>
    <div class="foot-links">
        <a href="tel:<?= $e($SITE['tel_raw']) ?>"><?= $ltr($SITE['tel']) ?></a>
        <?php foreach ($emails as $m): ?><a href="mailto:<?= $e($m) ?>"><?= $ltr($m) ?></a><?php endforeach; ?>
        <a href="<?= $e($mapLnk) ?>" target="_blank" rel="noopener"><?= $t('addr1') ?></a>
    </div>
    <p>© <?= date('Y') ?> <?= $t('hero_name') ?>. <?= $t('footer') ?></p>
</footer>

<a href="#" class="to-top" aria-label="Haut de page"><i class="fas fa-arrow-up"></i></a>

<script>
    // Flèche "retour en haut" : apparaît quand on descend
    const toTop = document.querySelector('.to-top');
    const majFleche = () => toTop.classList.toggle('show', window.scrollY > 500);
    window.addEventListener('scroll', majFleche, { passive: true });
    majFleche();
    toTop.addEventListener('click', ev => { ev.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // Menu mobile
    const burger = document.querySelector('.burger'), nav = document.querySelector('.nav-links');
    burger.addEventListener('click', () => {
        const open = nav.classList.toggle('open');
        burger.setAttribute('aria-expanded', open);
    });
    nav.querySelectorAll('a[href^="#"]').forEach(a => a.addEventListener('click', () => nav.classList.remove('open')));

    // Carrousel « Domaines d'expertise » : flèches, points, défilement automatique
    document.querySelectorAll('[data-carousel]').forEach(car => {
        const track = car.querySelector('.car-track'), cards = [...track.children];
        const dots = car.nextElementSibling, rtl = document.dir === 'rtl' ? -1 : 1;
        const pas = () => cards[0].offsetWidth + parseFloat(getComputedStyle(track).columnGap || 0);
        const pos = () => Math.abs(track.scrollLeft), max = () => track.scrollWidth - track.clientWidth - 2;
        const aller = sens => {
            if (sens > 0 && pos() >= max()) track.scrollTo({ left: 0, behavior: 'smooth' });          // fin → retour au début
            else if (sens < 0 && pos() <= 2) track.scrollTo({ left: rtl * track.scrollWidth, behavior: 'smooth' });
            else track.scrollBy({ left: sens * rtl * pas(), behavior: 'smooth' });
        };
        car.querySelector('.car-prev').addEventListener('click', () => { aller(-1); pause(); });
        car.querySelector('.car-next').addEventListener('click', () => { aller(1); pause(); });

        // Un point par position possible (6 cartes, 3 visibles → 4 positions)
        const nbPos = () => Math.max(1, Math.round(max() / pas()) + 1);
        const majPoints = () => {
            const i = pos() >= max() ? dots.children.length - 1 : Math.round(pos() / pas());
            [...dots.children].forEach((d, k) => d.classList.toggle('active', k === i));
        };
        const creerPoints = () => {
            if (dots.children.length === nbPos()) return majPoints();
            dots.innerHTML = '';
            for (let i = 0; i < nbPos(); i++) {
                const d = document.createElement('button');
                d.type = 'button'; d.setAttribute('aria-label', String(i + 1));
                d.addEventListener('click', () => { track.scrollTo({ left: rtl * i * pas(), behavior: 'smooth' }); pause(); });
                dots.appendChild(d);
            }
            majPoints();
        };
        track.addEventListener('scroll', majPoints, { passive: true });
        window.addEventListener('resize', creerPoints);
        creerPoints();

        // Effet cinéma : les cartes à moitié visibles sont atténuées
        const io = new IntersectionObserver(es => es.forEach(en => en.target.classList.toggle('in', en.intersectionRatio > .85)),
            { root: track, threshold: [0, .85, 1] });
        cards.forEach(c => io.observe(c));

        // Défilement automatique (s'arrête au survol, au toucher, ou si l'utilisateur préfère moins d'animations)
        let timer = null, reprise = null;
        const lancer = () => { if (!matchMedia('(prefers-reduced-motion: reduce)').matches) { clearInterval(timer); timer = setInterval(() => aller(1), 4500); } };
        const pause = () => { clearInterval(timer); clearTimeout(reprise); reprise = setTimeout(lancer, 9000); };
        car.addEventListener('mouseenter', () => clearInterval(timer));
        car.addEventListener('mouseleave', lancer);
        track.addEventListener('touchstart', pause, { passive: true });
        lancer();
    });
</script>
</body>
</html>
