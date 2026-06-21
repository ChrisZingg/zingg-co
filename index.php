<?php
$page_title   = 'ChriZ — zingg.co';
$page_section = 'home';
$base_path    = './';
include 'includes/header.php';
?>

<div class="hero">
  <div class="hero-inner">
    <p class="hero-eyebrow">zingg.co — persönliche Seite</p>
    <h1>Hallo, ich bin<br><strong>ChriZ</strong></h1>
    <p class="hero-sub">Privatpilot, Unternehmer, Tüftler. Hier findest du meine persönlichen Bereiche — von der Fliegerei bis zu weiteren Projekten.</p>
  </div>
</div>

<div class="page">

  <div class="section-header">
    <span class="section-tag">Bereiche</span>
    <h2 class="section-title">Was dich hier erwartet</h2>
  </div>

  <div class="areas-grid">

    <a href="pilot/index.php" class="area-card active-area">
      <span class="area-tag">Aktiv</span>
      <span class="area-icon">✈️</span>
      <div class="area-title">Privatpilot</div>
      <div class="area-desc">PPL(A), Logbuch, Flugplätze, Routen, Wetter-Tools und nützliche Links für die Fliegerei.</div>
    </a>

    <div class="area-card placeholder">
      <span class="area-tag">Bald</span>
      <span class="area-icon">🪁</span>
      <div class="area-title">Hobbys & Projekte</div>
      <div class="area-desc">Weitere persönliche Projekte — kommt demnächst.</div>
    </div>

    <div class="area-card placeholder">
      <span class="area-tag">Bald</span>
      <span class="area-icon">📸</span>
      <div class="area-title">Fotos & Erinnerungen</div>
      <div class="area-desc">Ausflüge, Flüge, besondere Momente — demnächst.</div>
    </div>

    <div class="area-card placeholder">
      <span class="area-tag">Bald</span>
      <span class="area-icon">🔗</span>
      <div class="area-title">Weitere Bereiche</div>
      <div class="area-desc">Platzhalter für künftige Inhalte.</div>
    </div>

  </div>

</div>

<?php include 'includes/footer.php'; ?>
