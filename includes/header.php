<?php
// includes/header.php
// Usage: define $page_section ('home','pilot') and $page_sub (optional) before including
$section = $page_section ?? 'home';
$base    = $base_path ?? '../';   // path back to root
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title ?? 'ChriZ — zingg.co') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
</head>
<body>

<nav class="site-nav">
  <a class="brand" href="<?= $base ?>index.php">ChriZ<span>.co</span></a>
  <div class="nav-links">
    <a href="<?= $base ?>pilot/index.php" <?= $section==='pilot' ? 'class="active"' : '' ?>>✈ Pilot</a>
    <a href="#" class="placeholder" style="opacity:0.35; cursor:default;">⛵ Coming soon</a>
  </div>
</nav>

<?php if (($page_sub ?? '') !== ''): ?>
<nav class="sub-nav">
  <a href="<?= $base ?>pilot/index.php"       <?= $page_sub==='overview'  ? 'class="active"' : '' ?>>Übersicht</a>
  <a href="<?= $base ?>pilot/flugzeug.php"    <?= $page_sub==='flugzeug'  ? 'class="active"' : '' ?>>Flugzeug</a>
  <a href="<?= $base ?>pilot/logbuch.php"     <?= $page_sub==='logbuch'   ? 'class="active"' : '' ?>>Logbuch</a>
  <a href="<?= $base ?>pilot/flugplaetze.php" <?= $page_sub==='plaetze'   ? 'class="active"' : '' ?>>Flugplätze</a>
  <a href="<?= $base ?>pilot/wetter.php"      <?= $page_sub==='wetter'    ? 'class="active"' : '' ?>>Wetter & Links</a>
  <a href="<?= $base ?>pilot/strecke.php"    <?= $page_sub==='strecke'   ? 'class="active"' : '' ?>>✈ Streckenwetter</a>
</nav>
<?php endif; ?>
