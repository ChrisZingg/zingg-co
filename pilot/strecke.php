<?php
$page_title   = 'Streckenwetter — ChriZ Pilot';
$page_section = 'pilot';
$page_sub     = 'strecke';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="page">
  <section>
    <div class="section-header">
      <span class="section-tag">Strecke</span>
      <h2 class="section-title">Streckenwetter-Briefing</h2>
    </div>
    <p style="font-size:13px;color:var(--muted);margin-bottom:1.5rem;">
      Start und Ziel eingeben — Zwischenplätze entlang der Route werden automatisch vorgeschlagen.
    </p>

    <!-- Route Builder -->
    <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1rem;">
      <div style="font-family:var(--mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:1rem;">Route</div>

      <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;margin-bottom:1.25rem;">
        <div class="form-field" style="flex:1;min-width:120px;">
          <label>Abflug</label>
          <input type="text" id="dep" placeholder="LSZB" maxlength="4"
            style="text-transform:uppercase;border:1px solid var(--border);border-radius:4px;padding:7px 10px;font-family:var(--mono);font-size:14px;font-weight:700;color:var(--sky);width:100%;outline:none;">
        </div>
        <div style="padding-bottom:8px;color:var(--muted);font-size:20px;">→</div>
        <div class="form-field" style="flex:1;min-width:120px;">
          <label>Ziel</label>
          <input type="text" id="arr" placeholder="LSGS" maxlength="4"
            style="text-transform:uppercase;border:1px solid var(--border);border-radius:4px;padding:7px 10px;font-family:var(--mono);font-size:14px;font-weight:700;color:var(--sky);width:100%;outline:none;">
        </div>
        <div class="form-field">
          <label>Abflugzeit (lokal)</label>
          <input type="datetime-local" id="dep-time"
            style="border:1px solid var(--border);border-radius:4px;padding:7px 10px;font-size:13px;outline:none;background:var(--surface);">
        </div>
        <div style="padding-bottom:8px;">
          <button class="btn" onclick="suggestWaypoints()" style="padding:8px 16px;">🔍 Zwischenplätze vorschlagen</button>
        </div>
      </div>

      <!-- Suggested waypoints -->
      <div id="suggestions-box" style="display:none;margin-bottom:1rem;">
        <div style="font-family:var(--mono);font-size:10px;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:0.5rem;">
          Vorgeschlagene Zwischenplätze <span style="color:var(--gold);">(klicken zum Hinzufügen/Entfernen)</span>
        </div>
        <div id="suggestions-list" style="display:flex;flex-wrap:wrap;gap:0.4rem;"></div>
      </div>

      <!-- Active waypoints -->
      <div style="font-family:var(--mono);font-size:10px;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:0.5rem;">
        Gewählte Zwischenhalte
      </div>
      <div id="waypoints-list" style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:0.75rem;min-height:32px;align-items:center;">
        <span style="font-size:12px;color:var(--border);font-style:italic;" id="wp-empty">Keine Zwischenhalte</span>
      </div>
      <div style="display:flex;gap:0.5rem;">
        <input type="text" id="wp-input" placeholder="ICAO manuell" maxlength="4"
          style="width:130px;border:1px solid var(--border);border-radius:4px;padding:5px 9px;font-family:var(--mono);font-size:13px;text-transform:uppercase;outline:none;">
        <button class="btn" onclick="addWaypoint()">+ Hinzufügen</button>
      </div>
    </div>

    <!-- Route Preview -->
    <div id="route-preview" style="display:none;" class="route-visual">
      <div class="route-title">Geplante Route</div>
      <div class="route-line" id="route-line"></div>
      <div class="route-meta" id="route-dist"></div>
    </div>

    <button class="btn btn-green" onclick="startBriefing()" style="font-size:14px;padding:10px 24px;margin-top:1rem;margin-bottom:2rem;">
      ✈ Wetter-Briefing abrufen
    </button>

    <!-- Results -->
    <div id="briefing-result" style="display:none;">
      <div class="section-header">
        <span class="section-tag">METARs</span>
        <h2 class="section-title">Aktuelle Stationsmeldungen</h2>
      </div>
      <div id="metar-cards" style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:2rem;"></div>

      <div class="section-header">
        <span class="section-tag">Beurteilung</span>
        <h2 class="section-title">Wetterbeurteilung Strecke</h2>
      </div>
      <div id="ai-briefing-box" style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
        <div style="background:var(--sky);padding:0.75rem 1.25rem;display:flex;align-items:center;gap:0.75rem;">
          <span style="font-size:1.1rem;">🤖</span>
          <span style="font-family:var(--mono);font-size:11px;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.7);">KI-Wetterbeurteilung</span>
          <span style="margin-left:auto;font-size:11px;color:rgba(255,255,255,0.4);font-family:var(--mono);">Kein Ersatz für offizielles Briefing</span>
        </div>
        <div id="ai-briefing-text" style="padding:1.5rem;font-size:15px;line-height:1.8;color:var(--ink);"></div>
      </div>
      <div style="margin-top:0.75rem;font-size:11px;color:var(--muted);font-family:var(--mono);">
        ⚠ Diese Beurteilung ersetzt kein offizielles Flugwetter-Briefing (SkyBriefing, DABS).
      </div>
    </div>
  </section>
</div>

<script>
// ── Flugplatz-Datenbank mit Koordinaten ──
// Format: ICAO: [lat, lon, name, land]
const AIRPORTS = {
  // Schweiz
  LSZB: [46.914,  7.497,  'Bern-Belp',          'CH'],
  LSZH: [47.458,  8.548,  'Zürich',              'CH'],
  LSGG: [46.238,  6.109,  'Genf',                'CH'],
  LSGS: [46.220,  7.327,  'Sion',                'CH'],
  LSZA: [46.004,  8.911,  'Lugano-Agno',         'CH'],
  LSMM: [47.277,  8.105,  'Männedorf',           'CH'],
  LSPD: [47.594,  9.047,  'Amlikon',             'CH'],
  LSPM: [47.114,  8.782,  'Mollis',              'CH'],
  LSPL: [47.126,  7.987,  'Langenthal',          'CH'],
  LSPA: [47.387,  8.325,  'Amlikon-Bissegg',     'CH'],
  LSPN: [47.463,  8.871,  'Triengen',            'CH'],
  LSPU: [47.066,  8.415,  'Kägiswil',            'CH'],
  LSPG: [47.175,  9.063,  'Grabs-Haag',          'CH'],
  LSPH: [47.297,  8.516,  'Winterthur-Häuti',    'CH'],
  LSPE: [47.484,  8.042,  'Bazenheid',           'CH'],
  LSPF: [47.196,  7.541,  'Bleienbach',          'CH'],
  LSZN: [47.483,  8.878,  'Hausen am Albis',     'CH'],
  LSGC: [46.160,  6.267,  'La Côte',             'CH'],
  LSGE: [46.799,  6.588,  'Yverdon',             'CH'],
  LSGL: [46.545,  6.618,  'Lausanne-Blécherette', 'CH'],
  LSGN: [46.878,  6.866,  'Neuchâtel',           'CH'],
  LSGS: [46.220,  7.327,  'Sion',                'CH'],
  LSTS: [46.499,  7.418,  'St. Stephan',         'CH'],
  LSZR: [47.542,  7.529,  'Basel-Rheinfelden',   'CH'],
  LFSB: [47.590,  7.530,  'Basel-Mulhouse',      'CH'],
  LSZO: [47.179,  8.108,  'Olten',               'CH'],
  // Deutschland
  EDNY: [47.671,  9.511,  'Friedrichshafen',     'D'],
  EDTF: [48.023,  7.832,  'Freiburg',            'D'],
  EDTB: [48.794,  8.083,  'Baden-Baden',         'D'],
  EDDS: [48.690,  9.222,  'Stuttgart',           'D'],
  EDMA: [48.425, 10.931,  'Augsburg',            'D'],
  EDDM: [48.354, 11.786,  'München',             'D'],
  EDJA: [47.989, 10.240,  'Memmingen',           'D'],
  EDTL: [48.369,  7.828,  'Lahr',                'D'],
  EDTY: [48.638,  9.786,  'Schwäbisch Hall',     'D'],
  // Österreich
  LOWI: [47.260, 11.344,  'Innsbruck',           'A'],
  LOIH: [47.505,  9.770,  'Hohenems',            'A'],
  LOXZ: [47.202, 14.743,  'Zeltweg',             'A'],
  LOWL: [48.234, 14.188,  'Linz',                'A'],
  LOWW: [48.110, 16.570,  'Wien',                'A'],
  LOWS: [47.793, 13.004,  'Salzburg',            'A'],
  LOKG: [47.596, 13.993,  'Kapfenberg',          'A'],
  // Frankreich
  LFLL: [45.726,  5.091,  'Lyon',                'F'],
  LFMN: [43.658,  7.215,  'Nizza',               'F'],
  LFML: [43.439,  5.221,  'Marseille',           'F'],
  LFGJ: [47.270,  5.090,  'Dole',                'F'],
  LFLB: [45.638,  5.880,  'Chambéry',            'F'],
  LFLS: [45.363,  5.330,  'Grenoble',            'F'],
  // Italien
  LIMC: [45.630,  8.723,  'Mailand-Malpensa',    'I'],
  LIME: [45.669,  9.704,  'Bergamo',             'I'],
  LIPQ: [45.828, 13.474,  'Triest',              'I'],
  LIMF: [45.200,  7.649,  'Turin',               'I'],
};

// ── Geo-Hilfsfunktionen ──
function toRad(d) { return d * Math.PI / 180; }

function distNM(lat1, lon1, lat2, lon2) {
  const R = 3440.065; // NM
  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);
  const a = Math.sin(dLat/2)**2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon/2)**2;
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// Senkrechter Abstand eines Punktes zur Strecke (great circle approximation)
function crossTrackDistNM(lat1, lon1, lat2, lon2, latP, lonP) {
  const d13 = distNM(lat1, lon1, latP, lonP);
  const brng13 = bearing(lat1, lon1, latP, lonP);
  const brng12  = bearing(lat1, lon1, lat2, lon2);
  return Math.abs(d13 * Math.sin(toRad(brng13 - brng12)));
}

// Along-track distance (how far along the route the point is, as fraction 0–1)
function alongTrackFraction(lat1, lon1, lat2, lon2, latP, lonP) {
  const total = distNM(lat1, lon1, lat2, lon2);
  if (total < 1) return 0.5;
  const d1P = distNM(lat1, lon1, latP, lonP);
  return Math.max(0, Math.min(1, d1P / total));
}

function bearing(lat1, lon1, lat2, lon2) {
  const dLon = toRad(lon2 - lon1);
  const y = Math.sin(dLon) * Math.cos(toRad(lat2));
  const x = Math.cos(toRad(lat1)) * Math.sin(toRad(lat2)) -
            Math.sin(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.cos(dLon);
  return (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
}

// ── Zwischenplatz-Vorschlag ──
function suggestWaypoints() {
  const dep = document.getElementById('dep').value.trim().toUpperCase();
  const arr = document.getElementById('arr').value.trim().toUpperCase();
  if (!dep || !arr) { alert('Bitte Abflug und Ziel eingeben.'); return; }
  if (!AIRPORTS[dep]) { alert('Abflugort ' + dep + ' nicht in Datenbank.'); return; }
  if (!AIRPORTS[arr]) { alert('Ziel ' + arr + ' nicht in Datenbank.'); return; }

  const [lat1, lon1] = AIRPORTS[dep];
  const [lat2, lon2] = AIRPORTS[arr];
  const totalDist = distNM(lat1, lon1, lat2, lon2);

  // Korridor: 25 NM quer, und nur Plätze zwischen 10% und 90% der Strecke
  const CORRIDOR_NM = 25;
  const suggestions = [];

  for (const [icao, data] of Object.entries(AIRPORTS)) {
    if (icao === dep || icao === arr) continue;
    const [latP, lonP] = data;
    const xtd = crossTrackDistNM(lat1, lon1, lat2, lon2, latP, lonP);
    const frac = alongTrackFraction(lat1, lon1, lat2, lon2, latP, lonP);
    if (xtd <= CORRIDOR_NM && frac > 0.1 && frac < 0.9) {
      suggestions.push({ icao, name: data[2], land: data[3], frac, xtd });
    }
  }

  // Sortieren nach Position entlang der Route
  suggestions.sort((a, b) => a.frac - b.frac);

  // Max 6 vorschlagen, bei langen Routen mehr
  const maxSuggest = totalDist > 200 ? 8 : 6;
  const shown = suggestions.slice(0, maxSuggest);

  const box = document.getElementById('suggestions-box');
  const list = document.getElementById('suggestions-list');
  list.innerHTML = '';

  if (!shown.length) {
    box.style.display = 'block';
    list.innerHTML = '<span style="font-size:12px;color:var(--muted);font-style:italic;">Keine Plätze im Streckenkorridor (±25 NM) gefunden.</span>';
    return;
  }

  shown.forEach(s => {
    const btn = document.createElement('button');
    btn.id = 'sug-' + s.icao;
    btn.onclick = () => toggleSuggestion(s.icao, s.name);
    btn.style.cssText = 'background:var(--surface);border:1px solid var(--border);border-radius:4px;padding:5px 12px;cursor:pointer;font-family:var(--mono);font-size:12px;transition:all 0.12s;';
    btn.innerHTML = `<strong>${s.icao}</strong> <span style="color:var(--muted);font-size:10px;">${s.name} · ${Math.round(s.xtd)} NM</span>`;
    list.appendChild(btn);
  });

  box.style.display = 'block';
  updateRoutePreview();
}

function toggleSuggestion(icao, name) {
  const btn = document.getElementById('sug-' + icao);
  if (waypoints.includes(icao)) {
    waypoints = waypoints.filter(w => w !== icao);
    if (btn) { btn.style.background='var(--surface)'; btn.style.borderColor='var(--border)'; btn.style.color=''; }
  } else {
    waypoints.push(icao);
    // Re-sort by along-track position
    const dep = document.getElementById('dep').value.trim().toUpperCase();
    const arr = document.getElementById('arr').value.trim().toUpperCase();
    if (AIRPORTS[dep] && AIRPORTS[arr]) {
      const [lat1,lon1] = AIRPORTS[dep];
      const [lat2,lon2] = AIRPORTS[arr];
      waypoints.sort((a,b) => {
        const fa = AIRPORTS[a] ? alongTrackFraction(lat1,lon1,lat2,lon2,...AIRPORTS[a].slice(0,2)) : 0.5;
        const fb = AIRPORTS[b] ? alongTrackFraction(lat1,lon1,lat2,lon2,...AIRPORTS[b].slice(0,2)) : 0.5;
        return fa - fb;
      });
    }
    if (btn) { btn.style.background='var(--sky)'; btn.style.borderColor='var(--sky)'; btn.style.color='white'; }
  }
  renderWaypoints();
  updateRoutePreview();
}

let waypoints = [];

function addWaypoint() {
  const inp = document.getElementById('wp-input');
  const icao = inp.value.trim().toUpperCase().substring(0,4);
  if (icao.length < 3 || waypoints.includes(icao)) { inp.value=''; return; }
  waypoints.push(icao);
  inp.value = '';
  renderWaypoints();
  updateRoutePreview();
}

function removeWaypoint(icao) {
  waypoints = waypoints.filter(w => w !== icao);
  const btn = document.getElementById('sug-' + icao);
  if (btn) { btn.style.background='var(--surface)'; btn.style.borderColor='var(--border)'; btn.style.color=''; }
  renderWaypoints();
  updateRoutePreview();
}

function renderWaypoints() {
  const list = document.getElementById('waypoints-list');
  const empty = document.getElementById('wp-empty');
  list.querySelectorAll('.wp-tag').forEach(e => e.remove());
  if (!waypoints.length) { empty.style.display='inline'; return; }
  empty.style.display = 'none';
  waypoints.forEach(icao => {
    const span = document.createElement('span');
    span.className = 'wp-tag';
    span.style.cssText = 'display:inline-flex;align-items:center;gap:5px;background:var(--sky-lt);color:var(--sky);font-family:var(--mono);font-size:12px;font-weight:700;padding:4px 10px;border-radius:3px;';
    const name = AIRPORTS[icao] ? ' <span style="font-weight:400;font-size:10px;color:var(--muted);">'+AIRPORTS[icao][2]+'</span>' : '';
    span.innerHTML = icao + name + `<button onclick="removeWaypoint('${icao}')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:13px;padding:0 0 0 4px;line-height:1;">×</button>`;
    list.appendChild(span);
  });
}

function updateRoutePreview() {
  const dep = document.getElementById('dep').value.trim().toUpperCase();
  const arr = document.getElementById('arr').value.trim().toUpperCase();
  const preview = document.getElementById('route-preview');
  if (!dep && !arr) { preview.style.display='none'; return; }
  const all = [dep, ...waypoints, arr].filter(Boolean);
  document.getElementById('route-line').innerHTML = all.map((w,i) =>
    `<span class="route-wp">${w}</span>` + (i < all.length-1 ? '<span class="route-arrow">→</span>' : '')
  ).join('');

  // Total distance
  let dist = 0;
  for (let i = 0; i < all.length-1; i++) {
    const a = AIRPORTS[all[i]], b = AIRPORTS[all[i+1]];
    if (a && b) dist += distNM(a[0],a[1],b[0],b[1]);
  }
  document.getElementById('route-dist').textContent = dist > 0 ? '≈ ' + Math.round(dist) + ' NM total' : '';
  preview.style.display = 'block';
}

// ── METAR fetch ──
async function fetchMetarForIcao(icao) {
  const r = await fetch('/pilot/metar_proxy.php?icao=' + encodeURIComponent(icao));
  const data = await r.json();
  return data.found ? data.metar.split('\n')[0] : null;
}

function parseMiniMetar(raw) {
  if (!raw) return {};
  return {
    wind:  raw.match(/(\d{3}|VRB)(\d{2,3})(G\d{2,3})?KT/),
    vis:   raw.match(/\s(\d{4})\s/),
    cloud: raw.match(/(FEW|SCT|BKN|OVC)(\d{3})/g),
    temp:  raw.match(/\s(M?\d{2})\/(M?\d{2})\s/),
    qnh:   raw.match(/Q(\d{4})/),
    wx:    raw.match(/\s(RA|SN|DZ|TS|FG|BR|HZ|TSRA|SHRA|FZRA)\s/)
  };
}

function metarToHtml(icao, raw, index, total) {
  const isFirst = index === 0, isLast = index === total-1;
  const label = isFirst ? '🛫 Abflug' : isLast ? '🛬 Ziel' : '📍 Via';
  const apName = AIRPORTS[icao] ? AIRPORTS[icao][2] : '';

  if (!raw) return `<div class="card" style="border-left:3px solid var(--muted);">
    <div style="font-family:var(--mono);font-size:11px;color:var(--muted);">${label}</div>
    <div style="font-family:var(--mono);font-size:18px;font-weight:700;color:var(--sky);">${icao} <span style="font-size:13px;font-weight:400;color:var(--muted);">${apName}</span></div>
    <div style="margin-top:0.5rem;font-size:12px;color:var(--muted);font-family:var(--mono);">⚠ Kein METAR verfügbar</div>
  </div>`;

  const p = parseMiniMetar(raw);
  const kt = p.wind ? parseInt(p.wind[2]) : 0;
  const vm = p.vis  ? parseInt(p.vis[1])  : 9999;
  const lowestCloud = p.cloud ? Math.min(...p.cloud.map(c => parseInt(c.match(/\d+/)[0])*100)) : 9999;
  let status = 'ok';
  if (kt > 25 || vm < 3000 || lowestCloud < 1000) status = 'crit';
  else if (kt > 15 || vm < 5000 || lowestCloud < 3000 || p.wx) status = 'warn';
  const col  = status==='crit' ? '#b03a2e' : status==='warn' ? 'var(--gold)' : 'var(--green)';
  const icon = status==='crit' ? '🔴' : status==='warn' ? '🟡' : '🟢';

  const windStr  = p.wind  ? p.wind[1]+'°/'+p.wind[2]+(p.wind[3]?' G'+p.wind[3].replace('G',''):'')+' kt' : '—';
  const visStr   = p.vis   ? (parseInt(p.vis[1])/1000).toFixed(0)+' km' : '>10 km';
  const cloudStr = p.cloud ? p.cloud.map(c=>{ const m=c.match(/(FEW|SCT|BKN|OVC)(\d+)/); return m?m[1]+' '+(parseInt(m[2])*100)+' ft':''; }).join(', ') : 'CAVOK';
  const tempStr  = p.temp  ? p.temp[1].replace('M','-')+'°/'+p.temp[2].replace('M','-')+'°' : '—';
  const qnhStr   = p.qnh   ? p.qnh[1]+' hPa' : '—';

  return `<div class="card" style="border-left:3px solid ${col};">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div>
        <div style="font-family:var(--mono);font-size:11px;color:var(--muted);">${label}</div>
        <div style="font-family:var(--mono);font-size:18px;font-weight:700;color:var(--sky);line-height:1.1;">${icao} ${icon} <span style="font-size:12px;font-weight:400;color:var(--muted);">${apName}</span></div>
      </div>
      <div style="font-size:10px;font-family:var(--mono);color:var(--muted);text-align:right;max-width:50%;word-break:break-all;line-height:1.4;">${raw.substring(0,60)}…</div>
    </div>
    <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
      <div><div style="font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">Wind</div><div style="font-size:14px;font-weight:500;">${windStr}</div></div>
      <div><div style="font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">Sicht</div><div style="font-size:14px;font-weight:500;">${visStr}</div></div>
      <div><div style="font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">Wolken</div><div style="font-size:14px;font-weight:500;">${cloudStr}</div></div>
      <div><div style="font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">Temp/Dew</div><div style="font-size:14px;font-weight:500;">${tempStr}</div></div>
      <div><div style="font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">QNH</div><div style="font-size:14px;font-weight:500;">${qnhStr}</div></div>
    </div>
  </div>`;
}

async function startBriefing() {
  const dep = document.getElementById('dep').value.trim().toUpperCase();
  const arr = document.getElementById('arr').value.trim().toUpperCase();
  const depTime = document.getElementById('dep-time').value;
  if (!dep || !arr) { alert('Bitte Abflug und Ziel eingeben.'); return; }

  const allIcao = [dep, ...waypoints, arr];
  document.getElementById('briefing-result').style.display = 'block';
  document.getElementById('metar-cards').innerHTML = '<div style="font-family:var(--mono);font-size:12px;color:var(--muted);">⏳ Lade METARs für ' + allIcao.join(', ') + '…</div>';
  document.getElementById('ai-briefing-text').innerHTML = '<span style="color:var(--muted);font-family:var(--mono);font-size:12px;">⏳ Warte auf METARs…</span>';
  document.getElementById('briefing-result').scrollIntoView({ behavior:'smooth', block:'start' });

  const results = await Promise.all(allIcao.map(icao => fetchMetarForIcao(icao).catch(()=>null)));
  const metarMap = {};
  allIcao.forEach((icao,i) => metarMap[icao] = results[i]);

  document.getElementById('metar-cards').innerHTML =
    allIcao.map((icao,i) => metarToHtml(icao, metarMap[icao], i, allIcao.length)).join('');

  const timeStr = depTime ? new Date(depTime).toLocaleString('de-CH',{weekday:'short',day:'2-digit',month:'2-digit',hour:'2-digit',minute:'2-digit'}) : 'unbekannte Zeit';
  const metarLines = allIcao.map((icao,i) => {
    const role = i===0 ? 'Abflug' : i===allIcao.length-1 ? 'Ziel' : 'Via';
    const name = AIRPORTS[icao] ? ' ('+AIRPORTS[icao][2]+')' : '';
    return `${role} ${icao}${name}: ${metarMap[icao] || 'Kein METAR'}`;
  }).join('\n');

  const prompt = `Du bist ein erfahrener Fluglehrer und Meteorologe für die Schweizer Luftfahrt.
Erstelle eine knappe, praxisorientierte Wetterbeurteilung auf Deutsch für folgenden Flug:

Route: ${allIcao.join(' → ')}
Geplante Abflugzeit: ${timeStr}

Aktuelle METARs:
${metarLines}

Beurteile:
1. Wetterlage entlang der Strecke (kurz, prägnant)
2. Kritische Punkte oder Einschränkungen (Sicht, Wind, Wolkenuntergrenze, Gewitter)
3. Go / Caution / No-Go Empfehlung mit kurzer Begründung

Schreibe in natürlichem Deutsch, als würdest du mit einem PPL-Piloten sprechen. Maximal 200 Wörter.`;

  document.getElementById('ai-briefing-text').innerHTML = '<span style="color:var(--muted);font-family:var(--mono);font-size:12px;">⏳ KI-Beurteilung wird erstellt…</span>';

  try {
    const resp = await fetch('https://api.anthropic.com/v1/messages', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ model:'claude-sonnet-4-6', max_tokens:1000, messages:[{role:'user',content:prompt}] })
    });
    const data = await resp.json();
    const text = (data.content?.[0]?.text || 'Keine Antwort.').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>').replace(/\n\n/g,'</p><p style="margin-top:0.75rem;">').replace(/\n/g,'<br>');
    document.getElementById('ai-briefing-text').innerHTML = '<p>' + text + '</p>';
  } catch(e) {
    document.getElementById('ai-briefing-text').innerHTML = '<span style="color:var(--muted);font-family:var(--mono);font-size:12px;">⚠ KI-Beurteilung nicht verfügbar</span>';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  ['dep','arr'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener('input', e => { e.target.value = e.target.value.toUpperCase(); updateRoutePreview(); });
  });
  document.getElementById('wp-input').addEventListener('keydown', e => {
    e.target.value = e.target.value.toUpperCase();
    if (e.key === 'Enter') addWaypoint();
  });
  const now = new Date(); now.setHours(now.getHours()+1); now.setMinutes(now.getMinutes()<30?0:30,0,0);
  document.getElementById('dep-time').value = now.toISOString().slice(0,16);
});
</script>

<?php include '../includes/footer.php'; ?>
