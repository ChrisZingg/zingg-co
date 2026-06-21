<?php
$page_title   = 'Wetter & Links — ChriZ Pilot';
$page_section = 'pilot';
$page_sub     = 'wetter';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="page">

  <section>
    <div class="section-header">
      <span class="section-tag">04</span>
      <h2 class="section-title">METAR / TAF</h2>
    </div>

    <div class="metar-box">
      <div class="metar-label">METAR Abruf — ICAO eingeben</div>
      <div class="metar-row">
        <input class="metar-input" id="icao-input" type="text" placeholder="z.B. LSZB" maxlength="4">
        <button class="metar-btn" onclick="fetchMetar()">ABRUF →</button>
      </div>
      <!-- Schnellauswahl Lieblingsplätze -->
      <div style="display:flex;gap:0.4rem;flex-wrap:wrap;margin-bottom:0.75rem;">
        <?php foreach(['LSZB','LSGS','LSTS','LSZA','EDNY','LOWI'] as $ic): ?>
        <button onclick="document.getElementById('icao-input').value='<?= $ic ?>';fetchMetar();"
          style="background:#1e3248;color:var(--sky-lt);border:1px solid #2e4a66;border-radius:3px;font-family:var(--mono);font-size:11px;padding:3px 9px;cursor:pointer;letter-spacing:0.06em;">
          <?= $ic ?>
        </button>
        <?php endforeach; ?>
      </div>
      <div class="metar-output" id="metar-out">→ ICAO eingeben oder oben auswählen</div>
      <div class="metar-parsed" id="metar-parsed">
        <div class="mp-row" id="mp-row"></div>
      </div>
    </div>
  </section>

  <section>
    <div class="section-header">
      <span class="section-tag">Quick Links</span>
      <h2 class="section-title">Direktlinks</h2>
    </div>

    <!-- SkyBriefing prominent -->
    <a href="https://www.skybriefing.com" target="_blank"
       style="display:flex;align-items:center;gap:1rem;background:var(--sky);color:white;border-radius:var(--radius);padding:1.25rem 1.5rem;text-decoration:none;margin-bottom:1rem;transition:opacity 0.15s;">
      <span style="font-size:2rem;">🛫</span>
      <div>
        <div style="font-size:15px;font-weight:600;">SkyBriefing.com</div>
        <div style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:2px;">Offizielle Flugvorbereitung Schweiz (BAZL) — Flugplan, NOTAM, Wetter, Briefing</div>
      </div>
      <span style="margin-left:auto;font-family:var(--mono);font-size:13px;color:var(--gold);">→</span>
    </a>

    <div class="wx-grid">
      <div class="wx-card">
        <div class="wx-header">Wetter & MET</div>
        <div class="wx-body">
          <a class="wx-link" href="https://www.meteoswiss.admin.ch" target="_blank">MeteoSchweiz<span class="wx-link-desc">Offizieller Schweizer Wetterdienst</span></a>
          <a class="wx-link" href="https://www.windy.com" target="_blank">Windy.com<span class="wx-link-desc">Wind- und Wettervisualisierung</span></a>
          <a class="wx-link" href="https://aviationweather.gov/metar" target="_blank">METAR (NOAA)<span class="wx-link-desc">METARs & TAFs weltweit</span></a>
          <a class="wx-link" href="https://www.skybriefing.com/dabs" target="_blank">DABS Schweiz<span class="wx-link-desc">Daily Airspace Bulletin, täglich 16:00</span></a>
        </div>
      </div>

      <div class="wx-card">
        <div class="wx-header">EFB & Navigation</div>
        <div class="wx-body">
          <a class="wx-link" href="https://www.skydemon.com" target="_blank">SkyDemon<span class="wx-link-desc">Mein EFB — Flugplanung & Karten</span></a>
          <a class="wx-link" href="https://www.skybriefing.com/en/services/flightplan-services" target="_blank">Flugplan einreichen<span class="wx-link-desc">Via SkyBriefing (kostenlos ab CH)</span></a>
          <a class="wx-link" href="https://www.notaminfo.com" target="_blank">NOTAM Info<span class="wx-link-desc">NOTAMs filtern und visualisieren</span></a>
          <a class="wx-link" href="https://dronespace.bazl.admin.ch" target="_blank">Luftraumkarte CH<span class="wx-link-desc">BAZL DroneSpace / Luftraumstruktur</span></a>
        </div>
      </div>

      <div class="wx-card">
        <div class="wx-header">Logbuch & Verwaltung</div>
        <div class="wx-body">
          <a class="wx-link" href="https://www.safelogweb.com" target="_blank">SafeLog Web<span class="wx-link-desc">Mein digitales Logbuch — Export unter Import & Export</span></a>
          <a class="wx-link" href="https://www.bazl.admin.ch" target="_blank">BAZL<span class="wx-link-desc">Bundesamt für Zivilluftfahrt CH</span></a>
          <a class="wx-link" href="https://www.easa.europa.eu" target="_blank">EASA<span class="wx-link-desc">Europäische Luftfahrtbehörde</span></a>
          <a class="wx-link" href="https://www.skyguide.ch" target="_blank">Skyguide<span class="wx-link-desc">Schweizer Flugsicherung</span></a>
        </div>
      </div>

      <div class="wx-card">
        <div class="wx-header">Wissen & Community</div>
        <div class="wx-body">
          <a class="wx-link" href="https://www.boldmethod.com" target="_blank">BoldMethod<span class="wx-link-desc">Theorie, Wetter, Verfahren erklärt</span></a>
          <a class="wx-link" href="https://www.avweb.com" target="_blank">AVweb<span class="wx-link-desc">Luftfahrt-News & Sicherheit</span></a>
          <a class="wx-link" href="https://www.flightradar24.com" target="_blank">FlightRadar24<span class="wx-link-desc">Live-Tracking aller Flüge</span></a>
          <a class="wx-link" href="https://www.bazl.admin.ch/aip" target="_blank">AIP Schweiz<span class="wx-link-desc">Amtliche Luftfahrtpublikation BAZL</span></a>
        </div>
      </div>
    </div>
  </section>

</div>

<?php include '../includes/footer.php'; ?>
