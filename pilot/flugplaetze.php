<?php
$page_title   = 'Flugplätze & Routen — ChriZ Pilot';
$page_section = 'pilot';
$page_sub     = 'plaetze';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="page">

  <section>
    <div class="section-header">
      <span class="section-tag">03</span>
      <h2 class="section-title">Flugplätze</h2>
    </div>

    <div class="card-grid">
      <div class="ap-card ap-home">
        <div class="ap-icao">LSZB</div>
        <div><div class="ap-name">Bern-Belp ★ Heimatplatz</div><div class="ap-detail">1383 ft AMSL · PPR · Tower 120.100</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LSGS</div>
        <div><div class="ap-name">Sion</div><div class="ap-detail">1585 ft AMSL · Wallis · MIL/CIV</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LSZA</div>
        <div><div class="ap-name">Lugano-Agno</div><div class="ap-detail">915 ft AMSL · Tessin · Gebirgsroute</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LSTS</div>
        <div><div class="ap-name">St. Stephan</div><div class="ap-detail">3304 ft AMSL · Bergflugplatz · Flugclub</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LSPD</div>
        <div><div class="ap-name">Amlikon</div><div class="ap-detail">1467 ft AMSL · Graslandebahn</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LSZH</div>
        <div><div class="ap-name">Zürich Kloten</div><div class="ap-detail">1416 ft AMSL · Nur nach Freigabe</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">EDNY</div>
        <div><div class="ap-name">Friedrichshafen</div><div class="ap-detail">1367 ft AMSL · D · Grenzflug</div></div>
      </div>
      <div class="ap-card">
        <div class="ap-icao">LOWI</div>
        <div><div class="ap-name">Innsbruck</div><div class="ap-detail">1907 ft AMSL · A · Alpenflug</div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="section-header">
      <span class="section-tag">SkyDemon</span>
      <h2 class="section-title">Route importieren</h2>
    </div>

    <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
      In SkyDemon eine Route planen → <strong>Teilen → .flightplan-Datei</strong> exportieren und hier laden:
    </p>

    <div class="upload-zone" id="fp-drop" onclick="document.getElementById('fp-file').click()">
      <div class="upload-icon">🗺️</div>
      <strong style="font-size:14px;">SkyDemon .flightplan hier ablegen</strong>
      <p>oder klicken zum Auswählen (.flightplan / .xml)</p>
    </div>
    <input type="file" id="fp-file" accept=".flightplan,.xml" style="display:none">

    <div id="fp-result" style="display:none;margin-top:1rem;">
      <div class="route-visual">
        <div class="route-title" id="fp-title">Route</div>
        <div class="route-line" id="fp-waypoints"></div>
        <div class="route-meta" id="fp-meta"></div>
      </div>
      <div style="margin-top:0.75rem;">
        <button class="btn btn-green" onclick="saveRoute()">Route speichern</button>
        <span class="save-ok" id="route-ok">✓ Gespeichert</span>
      </div>
    </div>
  </section>

  <section id="saved-routes">
    <div class="section-header">
      <span class="section-tag">Routen</span>
      <h2 class="section-title">Gespeicherte Routen</h2>
    </div>
    <div id="routes-list"></div>
  </section>

</div>

<script>
let pendingRoute = null;

function renderRoutes() {
  const routes = JSON.parse(localStorage.getItem('routes') || '[]');
  const list = document.getElementById('routes-list');
  if (!routes.length) {
    list.innerHTML = '<p style="font-size:13px;color:var(--muted);">Noch keine Routen gespeichert. Importiere eine .flightplan-Datei aus SkyDemon.</p>';
    return;
  }
  list.innerHTML = routes.map((r,i) => `
    <div class="route-visual" style="margin-bottom:0.75rem;">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div class="route-title">${r.name}</div>
        <button onclick="deleteRoute(${i})" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:12px;font-family:var(--mono);">✕ Löschen</button>
      </div>
      <div class="route-line">${r.waypoints.map(w=>`<span class="route-wp">${w}</span>`).join('<span class="route-arrow">→</span>')}</div>
      <div class="route-meta">${r.meta||''}</div>
    </div>`).join('');
}

function saveRoute() {
  if (!pendingRoute) return;
  const routes = JSON.parse(localStorage.getItem('routes') || '[]');
  routes.push(pendingRoute);
  localStorage.setItem('routes', JSON.stringify(routes));
  renderRoutes();
  document.getElementById('fp-result').style.display = 'none';
  const ok = document.getElementById('route-ok');
  ok.style.display = 'inline'; setTimeout(()=>ok.style.display='none',2000);
  pendingRoute = null;
}

function deleteRoute(i) {
  const routes = JSON.parse(localStorage.getItem('routes') || '[]');
  routes.splice(i, 1);
  localStorage.setItem('routes', JSON.stringify(routes));
  renderRoutes();
}

function parseSkyDemonFile(text) {
  try {
    const parser = new DOMParser();
    const doc = parser.parseFromString(text, 'text/xml');

    // SkyDemon .flightplan XML structure
    const routeEl   = doc.querySelector('Route');
    const routeName = doc.querySelector('Name')?.textContent || 'SkyDemon Route';
    const waypoints = [];
    const wps = doc.querySelectorAll('Waypoint, Point, Fix');
    wps.forEach(wp => {
      const icao = wp.getAttribute('Identifier') || wp.getAttribute('identifier') ||
                   wp.querySelector('Identifier')?.textContent || wp.textContent?.trim();
      if (icao && icao.length >= 2 && icao.length <= 6) waypoints.push(icao.toUpperCase());
    });

    // Fallback: look for all text nodes that look like ICAO codes
    if (!waypoints.length) {
      const all = text.match(/\b([A-Z]{4})\b/g);
      if (all) [...new Set(all)].forEach(w => waypoints.push(w));
    }

    const dist = doc.querySelector('Distance')?.textContent || '';
    const time = doc.querySelector('Time, Duration')?.textContent || '';
    const meta = [dist ? '≈ '+dist+' NM' : '', time ? 'ETE '+time : ''].filter(Boolean).join(' · ');

    return { name: routeName, waypoints, meta: meta || 'Importiert aus SkyDemon' };
  } catch(e) {
    return null;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  renderRoutes();

  const drop = document.getElementById('fp-drop');
  const fileInput = document.getElementById('fp-file');

  fileInput.addEventListener('change', e => handleFP(e.target.files[0]));
  drop.addEventListener('dragover', e => { e.preventDefault(); drop.style.borderColor='var(--sky-mid)'; });
  drop.addEventListener('dragleave', () => drop.style.borderColor='');
  drop.addEventListener('drop', e => {
    e.preventDefault(); drop.style.borderColor='';
    if (e.dataTransfer.files[0]) handleFP(e.dataTransfer.files[0]);
  });

  function handleFP(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
      const route = parseSkyDemonFile(ev.target.result);
      if (!route || !route.waypoints.length) {
        alert('Datei konnte nicht gelesen werden. Bitte eine gültige SkyDemon .flightplan-Datei verwenden.');
        return;
      }
      pendingRoute = route;
      document.getElementById('fp-title').textContent = route.name;
      const wl = document.getElementById('fp-waypoints');
      wl.innerHTML = route.waypoints.map(w=>`<span class="route-wp">${w}</span>`).join('<span class="route-arrow">→</span>');
      document.getElementById('fp-meta').textContent = route.meta;
      document.getElementById('fp-result').style.display = 'block';
    };
    reader.readAsText(file);
  }
});
</script>

<?php include '../includes/footer.php'; ?>
