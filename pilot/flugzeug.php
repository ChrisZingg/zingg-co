<?php
$page_title   = 'Flugzeug & Ausrüstung — ChriZ Pilot';
$page_section = 'pilot';
$page_sub     = 'flugzeug';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="page">

  <section>
    <div class="section-header">
      <span class="section-tag">01</span>
      <h2 class="section-title">Flugzeug & Ausrüstung</h2>
    </div>

    <div class="card-main">
      <div class="card-main-icon">✈️</div>
      <div>
        <div class="card-main-name">Piper PA-28-161 Warrior II</div>
        <div class="card-main-sub">HB-PXY</div>
        <div class="card-main-desc">Tiefdecker, 4-sitzig — Vereinsmaschine des Flugclubs. Zuverlässig und ideal für Überlandflüge in den Alpenvorraum.</div>
      </div>
    </div>

    <div class="card-grid">
      <div class="card"><div class="card-label">Motor</div><div class="card-value">Lycoming O-320-D3G</div></div>
      <div class="card"><div class="card-label">Leistung</div><div class="card-value">160 PS</div></div>
      <div class="card"><div class="card-label">Reisegeschwindigkeit</div><div class="card-value">≈ 105 kt (195 km/h)</div></div>
      <div class="card"><div class="card-label">Reichweite</div><div class="card-value">≈ 520 NM mit Reserven</div></div>
      <div class="card"><div class="card-label">Avionik</div><div class="card-value">Garmin G5, GTN 650</div></div>
      <div class="card"><div class="card-label">MTOW</div><div class="card-value">1 055 kg</div></div>
    </div>

    <div class="section-header" style="margin-top:2rem;">
      <span class="section-tag">Persönliche Ausrüstung</span>
    </div>

    <div class="card-grid">
      <div class="card"><div class="card-label">EFB / App</div><div class="card-value">SkyDemon (iPad mini)</div></div>
      <div class="card"><div class="card-label">Headset</div><div class="card-value">Bose A20</div></div>
      <div class="card"><div class="card-label">Kamera</div><div class="card-value">GoPro Hero 12</div></div>
      <div class="card"><div class="card-label">Notfall</div><div class="card-value">PLB 406 MHz, ELT</div></div>
      <div class="card"><div class="card-label">Logbuch (digital)</div><div class="card-value">SafeLog (safelogweb.com)</div></div>
      <div class="card"><div class="card-label">Flugplanung</div><div class="card-value">SkyBriefing.com</div></div>
    </div>

    <div class="notes-box" style="margin-top:1.5rem;">
      <div class="notes-hdr">
        <span>Notizen Flugzeug / Wartung</span>
        <div>
          <button class="btn" onclick="saveNote('ac-notes','ac-ok')">Speichern</button>
          <span class="save-ok" id="ac-ok">✓ Gespeichert</span>
        </div>
      </div>
      <textarea class="notes-area" id="ac-notes" placeholder="z.B. Letzte 100h-Kontrolle, AD-Status, Squawks, nächste Inspektion…"></textarea>
    </div>
  </section>

</div>

<script>document.addEventListener('DOMContentLoaded',()=>loadNote('ac-notes'));</script>
<?php include '../includes/footer.php'; ?>
