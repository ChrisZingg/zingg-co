<?php
$page_title   = 'Logbuch — ChriZ Pilot';
$page_section = 'pilot';
$page_sub     = 'logbuch';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="page">

  <section>
    <div class="section-header">
      <span class="section-tag">02</span>
      <h2 class="section-title">Logbuch</h2>
    </div>

    <!-- SafeLog CSV Import -->
    <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:1.5rem;">
      <div style="background:var(--surface);border-bottom:1px solid var(--border);padding:0.6rem 1rem;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-family:var(--mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);">SafeLog CSV Import</span>
        <a href="https://www.safelogweb.com" target="_blank" style="font-size:11px;color:var(--sky);text-decoration:none;font-family:var(--mono);">safelogweb.com →</a>
      </div>
      <div style="padding:1.25rem;">
        <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
          Export in SafeLog: <strong>safelogweb.com → Import &amp; Export → CSV Export</strong>. Dann hier laden:
        </p>
        <div class="upload-zone" id="csv-drop" onclick="document.getElementById('csv-file').click()">
          <div class="upload-icon">📂</div>
          <strong style="font-size:14px;">SafeLog CSV hier ablegen</strong>
          <p>oder klicken zum Auswählen</p>
        </div>
        <input type="file" id="csv-file" accept=".csv,.txt" style="display:none">
        <div id="import-status" style="margin-top:0.75rem;font-size:12px;font-family:var(--mono);color:var(--muted);"></div>
      </div>
    </div>

    <!-- Stats row -->
    <div id="stats-row" style="display:none;display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem;">
      <div class="card" style="flex:1;min-width:120px;">
        <div class="card-label">Flüge</div>
        <div class="card-value" id="stat-flights-count">—</div>
      </div>
      <div class="card" style="flex:1;min-width:120px;">
        <div class="card-label">Total Stunden</div>
        <div class="card-value" id="stat-total-hours">—</div>
      </div>
      <div class="card" style="flex:1;min-width:120px;">
        <div class="card-label">Solo</div>
        <div class="card-value" id="stat-solo">—</div>
      </div>
      <div class="card" style="flex:1;min-width:120px;">
        <div class="card-label">Letzter Flug</div>
        <div class="card-value" id="stat-last">—</div>
      </div>
    </div>

    <!-- Filter row -->
    <div style="display:flex;gap:0.5rem;margin-bottom:0.75rem;flex-wrap:wrap;align-items:center;">
      <input type="text" id="log-filter" placeholder="Suchen (ICAO, Datum, Kennzeichen…)" style="flex:1;min-width:180px;border:1px solid var(--border);border-radius:4px;padding:6px 10px;font-size:13px;outline:none;">
      <select id="log-type-filter" style="border:1px solid var(--border);border-radius:4px;padding:6px 10px;font-size:13px;background:var(--white);outline:none;">
        <option value="">Alle Typen</option>
        <option value="SOLO">Solo</option>
        <option value="DUAL">Dual</option>
        <option value="NAV">Navigation</option>
      </select>
      <button class="btn btn-green" onclick="showAddForm()">+ Flug</button>
      <button class="btn" onclick="exportCSV()" style="background:var(--muted);">↓ CSV</button>
    </div>

    <table class="log-table" id="log-table">
      <thead>
        <tr>
          <th>Datum</th>
          <th>Von → Nach</th>
          <th>Reg.</th>
          <th>Dauer</th>
          <th>Art</th>
          <th>Bemerkung</th>
        </tr>
      </thead>
      <tbody id="log-tbody"></tbody>
    </table>
    <div style="margin-top:0.5rem;font-size:11px;color:var(--muted);font-family:var(--mono);" id="log-count"></div>

    <!-- Add flight form -->
    <div id="add-form" style="display:none;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-top:1rem;">
      <div style="font-family:var(--mono);font-size:10px;color:var(--muted);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:1rem;">Neuer Logeintrag</div>
      <div class="form-grid">
        <div class="form-field"><label>Datum</label><input type="date" id="fl-date"></div>
        <div class="form-field"><label>Von</label><input type="text" id="fl-from" placeholder="LSZB"></div>
        <div class="form-field"><label>Nach</label><input type="text" id="fl-to" placeholder="LSGS"></div>
        <div class="form-field"><label>Kennzeichen</label><input type="text" id="fl-reg" placeholder="HB-PXY"></div>
        <div class="form-field"><label>Dauer (h:mm)</label><input type="text" id="fl-dur" placeholder="1:20"></div>
        <div class="form-field"><label>Art</label>
          <select id="fl-type">
            <option value="SOLO">Solo</option>
            <option value="DUAL">Dual</option>
            <option value="NAV">Navigation</option>
          </select>
        </div>
      </div>
      <div class="form-field" style="margin-bottom:0.75rem;">
        <label>Bemerkung</label>
        <input type="text" id="fl-remark" placeholder="z.B. Platzrunden Birrfeld, VMC">
      </div>
      <div style="display:flex;gap:0.5rem;">
        <button class="btn btn-green" onclick="addFlight()">Eintragen</button>
        <button class="btn btn-muted" onclick="document.getElementById('add-form').style.display='none'">Abbrechen</button>
      </div>
    </div>

  </section>
</div>

<script>
const TYPE_CLASS = { SOLO:'badge-solo', DUAL:'badge-dual', NAV:'badge-nav' };

let flights = JSON.parse(localStorage.getItem('flights') || '[]');
if (!flights.length) {
  flights = [
    { date:'2026-06-14', from:'LSZB', to:'LSTS', reg:'HB-PXY', dur:'0:45', type:'SOLO', remark:'Flugclub-Ausflug St. Stephan' },
    { date:'2026-05-28', from:'LSZB', to:'EDNY', reg:'HB-PXY', dur:'1:10', type:'NAV',  remark:'Grenzflug Friedrichshafen' },
    { date:'2026-05-10', from:'LSZB', to:'LSZB', reg:'HB-PXY', dur:'0:55', type:'SOLO', remark:'Platzrunden + Außenlandung' },
    { date:'2026-04-20', from:'LSZB', to:'LSGS', reg:'HB-PXY', dur:'1:25', type:'NAV',  remark:'Alpentransit Sion' },
    { date:'2026-03-15', from:'LSZB', to:'LSPD', reg:'HB-PXY', dur:'0:40', type:'DUAL', remark:'BFR mit Fluglehrer' },
  ];
}

function durToMin(s) {
  const p = s.split(':');
  return parseInt(p[0]||0)*60 + parseInt(p[1]||0);
}
function minToDur(m) {
  return Math.floor(m/60)+':'+(m%60).toString().padStart(2,'0');
}

function renderLog() {
  const flt  = document.getElementById('log-filter').value.toLowerCase();
  const type = document.getElementById('log-type-filter').value;
  const tbody = document.getElementById('log-tbody');
  tbody.innerHTML = '';

  let shown = [...flights]
    .sort((a,b) => b.date.localeCompare(a.date))
    .filter(f => {
      if (type && f.type !== type) return false;
      if (!flt) return true;
      return [f.date, f.from, f.to, f.reg, f.remark, f.type].join(' ').toLowerCase().includes(flt);
    });

  shown.forEach(f => {
    const d = new Date(f.date);
    const ds = d.toLocaleDateString('de-CH',{day:'2-digit',month:'2-digit',year:'numeric'});
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="mono">${ds}</td>
      <td><strong>${f.from}</strong> <span style="color:var(--muted)">→</span> <strong>${f.to}</strong></td>
      <td class="mono">${f.reg||''}</td>
      <td class="dur">${f.dur}</td>
      <td><span class="badge ${TYPE_CLASS[f.type]||''}">${f.type}</span></td>
      <td style="color:var(--muted);font-size:12px;">${f.remark||''}</td>`;
    tbody.appendChild(tr);
  });
  document.getElementById('log-count').textContent = shown.length + ' von ' + flights.length + ' Einträgen';
  updateStats();
}

function updateStats() {
  const row = document.getElementById('stats-row');
  if (!flights.length) { row.style.display='none'; return; }
  row.style.display = 'flex';
  const totalMin = flights.reduce((s,f) => s + durToMin(f.dur), 0);
  const solo = flights.filter(f => f.type === 'SOLO').length;
  const last = [...flights].sort((a,b)=>b.date.localeCompare(a.date))[0];
  const ld = new Date(last.date);
  document.getElementById('stat-flights-count').textContent = flights.length;
  document.getElementById('stat-total-hours').textContent   = minToDur(totalMin) + ' h';
  document.getElementById('stat-solo').textContent          = solo + ' Flüge';
  document.getElementById('stat-last').textContent          = ld.toLocaleDateString('de-CH',{day:'2-digit',month:'2-digit',year:'numeric'});
}

function saveFlight() { localStorage.setItem('flights', JSON.stringify(flights)); }

function showAddForm() {
  const f = document.getElementById('add-form');
  f.style.display = f.style.display === 'none' ? 'block' : 'none';
  if (f.style.display === 'block') {
    document.getElementById('fl-date').value = new Date().toISOString().split('T')[0];
    document.getElementById('fl-reg').value  = 'HB-PXY';
  }
}

function addFlight() {
  const date   = document.getElementById('fl-date').value;
  const from   = document.getElementById('fl-from').value.trim().toUpperCase();
  const to     = document.getElementById('fl-to').value.trim().toUpperCase();
  const reg    = document.getElementById('fl-reg').value.trim().toUpperCase();
  const dur    = document.getElementById('fl-dur').value.trim();
  const type   = document.getElementById('fl-type').value;
  const remark = document.getElementById('fl-remark').value.trim();
  if (!date || !from || !to || !dur) { alert('Bitte Datum, Von, Nach und Dauer ausfüllen.'); return; }
  flights.push({ date, from, to, reg: reg||'HB-PXY', dur, type, remark });
  saveFlight();
  renderLog();
  document.getElementById('add-form').style.display = 'none';
  ['fl-from','fl-to','fl-dur','fl-remark'].forEach(id => document.getElementById(id).value='');
}

function exportCSV() {
  const hdr = 'Datum,Von,Nach,Kennzeichen,Dauer,Art,Bemerkung\n';
  const rows = [...flights].sort((a,b)=>b.date.localeCompare(a.date))
    .map(f => [f.date,f.from,f.to,f.reg,f.dur,f.type,'"'+( f.remark||'').replace(/"/g,'""')+'"'].join(',')).join('\n');
  const blob = new Blob([hdr+rows], {type:'text/csv'});
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
  a.download = 'logbuch_export.csv'; a.click();
}

// ── SafeLog CSV Import ──
function parseSafelogCSV(text) {
  const lines = text.split(/\r?\n/).filter(l => l.trim());
  if (lines.length < 2) return 0;
  const delim = lines[0].includes(';') ? ';' : ',';
  const headers = lines[0].split(delim).map(h => h.replace(/^["']|["']$/g,'').trim().toLowerCase());

  const col = name => headers.indexOf(name);
  // Common SafeLog column names (may vary by export settings)
  const dateIdx    = col('date') > -1              ? col('date')            : col('flight date');
  const fromIdx    = col('departure airport') > -1 ? col('departure airport'): col('from');
  const toIdx      = col('destination airport')>-1 ? col('destination airport'):col('to');
  const regIdx     = col('aircraft id') > -1       ? col('aircraft id')     : col('registration');
  const durIdx     = col('total time') > -1        ? col('total time')      : col('duration');
  const picIdx     = col('pic time') > -1          ? col('pic time')        : -1;
  const dualIdx    = col('dual given') > -1        ? col('dual given')      : col('dual received');
  const remarkIdx  = col('remarks') > -1           ? col('remarks')         : col('comments');

  let imported = 0;
  const existing = new Set(flights.map(f => f.date+'|'+f.from+'|'+f.to));

  for (let i = 1; i < lines.length; i++) {
    const cols = lines[i].split(delim).map(c => c.replace(/^["']|["']$/g,'').trim());
    if (cols.length < 3) continue;
    const date = (cols[dateIdx]||'').replace(/(\d{2})\.(\d{2})\.(\d{4})/,'$3-$2-$1');
    const from = (cols[fromIdx]||'').toUpperCase().substring(0,4);
    const to   = (cols[toIdx]||'').toUpperCase().substring(0,4);
    if (!date || !from || !to) continue;
    const key  = date+'|'+from+'|'+to;
    if (existing.has(key)) continue;

    const dur    = cols[durIdx]  || '0:00';
    const reg    = (cols[regIdx] || 'HB-PXY').toUpperCase();
    const remark = cols[remarkIdx] || '';
    const dualT  = dualIdx > -1 ? parseFloat(cols[dualIdx]||0) : 0;
    const picT   = picIdx  > -1 ? parseFloat(cols[picIdx]||0)  : 0;
    const type   = dualT > 0 ? 'DUAL' : 'SOLO';

    flights.push({ date, from, to, reg, dur, type, remark });
    existing.add(key);
    imported++;
  }
  return imported;
}

document.addEventListener('DOMContentLoaded', () => {
  renderLog();
  document.getElementById('log-filter').addEventListener('input', renderLog);
  document.getElementById('log-type-filter').addEventListener('change', renderLog);

  const fileInput = document.getElementById('csv-file');
  const drop = document.getElementById('csv-drop');

  fileInput.addEventListener('change', e => handleFile(e.target.files[0]));

  drop.addEventListener('dragover', e => { e.preventDefault(); drop.style.borderColor='var(--sky-mid)'; });
  drop.addEventListener('dragleave', () => drop.style.borderColor='');
  drop.addEventListener('drop', e => {
    e.preventDefault();
    drop.style.borderColor='';
    if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
  });

  function handleFile(file) {
    if (!file) return;
    const status = document.getElementById('import-status');
    status.textContent = '⏳ Lese ' + file.name + ' …';
    const reader = new FileReader();
    reader.onload = ev => {
      const n = parseSafelogCSV(ev.target.result);
      if (n > 0) {
        saveFlight();
        renderLog();
        status.style.color = 'var(--green)';
        status.textContent = '✓ ' + n + ' neue Flüge aus SafeLog importiert';
      } else {
        status.style.color = 'var(--muted)';
        status.textContent = '⚠ Keine neuen Einträge gefunden (bereits vorhanden oder Format unbekannt)';
      }
    };
    reader.readAsText(file);
  }
});
</script>

<?php include '../includes/footer.php'; ?>
