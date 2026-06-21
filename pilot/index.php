<?php
$page_title   = 'Pilot — ChriZ';
$page_section = 'pilot';
$page_sub     = 'overview';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="hero">
  <div class="hero-inner">
    <p class="hero-eyebrow">PPL(A) // Flugclub // Schweiz</p>
    <h1>Fliegen ist<br><strong>Freiheit in drei Dimensionen</strong></h1>
    <p class="hero-sub">Meine persönliche Pilotenecke — Logbuch, Routen, Flugplätze und alles, was dazu gehört.</p>
    <div class="hero-stats">
      <div>
        <span class="hero-stat-val">247</span>
        <div class="hero-stat-lbl">Gesamtstunden</div>
      </div>
      <div>
        <span class="hero-stat-val">312</span>
        <div class="hero-stat-lbl">Flüge total</div>
      </div>
      <div>
        <span class="hero-stat-val">18</span>
        <div class="hero-stat-lbl">Besuchte Flugplätze</div>
      </div>
      <div>
        <span class="hero-stat-val">2019</span>
        <div class="hero-stat-lbl">Lizenz seit</div>
      </div>
    </div>
  </div>
</div>

<div class="page">

  <div class="areas-grid">
    <a href="flugzeug.php" class="area-card active-area">
      <span class="area-icon">🛩️</span>
      <div class="area-title">Flugzeug & Ausrüstung</div>
      <div class="area-desc">Piper PA-28-161 Warrior II, HB-PXY — technische Daten, Avionik, persönliche Ausrüstung.</div>
    </a>
    <a href="logbuch.php" class="area-card active-area">
      <span class="area-icon">📋</span>
      <div class="area-title">Logbuch</div>
      <div class="area-desc">Alle Flüge, CSV-Import aus SafeLog, Statistiken.</div>
    </a>
    <a href="flugplaetze.php" class="area-card active-area">
      <span class="area-icon">🗺️</span>
      <div class="area-title">Flugplätze & Routen</div>
      <div class="area-desc">Heimatplatz, besuchte Airports, Lieblingsrouten inkl. SkyDemon .flightplan Import.</div>
    </a>
    <a href="wetter.php" class="area-card active-area">
      <span class="area-icon">🌤️</span>
      <div class="area-title">Wetter & Links</div>
      <div class="area-desc">METAR-Viewer, SkyBriefing, MeteoSchweiz und alle wichtigen Piloten-Links.</div>
    </a>
  </div>

</div>

<?php include '../includes/footer.php'; ?>
