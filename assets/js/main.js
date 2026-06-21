// ── Local storage notes ──
function saveNote(id, okId) {
  localStorage.setItem('note_' + id, document.getElementById(id).value);
  const ok = document.getElementById(okId);
  ok.style.display = 'inline';
  setTimeout(() => ok.style.display = 'none', 2000);
}
function loadNote(id) {
  const v = localStorage.getItem('note_' + id);
  const el = document.getElementById(id);
  if (v && el) el.value = v;
}

// ── METAR fetcher (via PHP proxy auf zingg.co) ──
async function fetchMetar() {
  const icao = document.getElementById('icao-input').value.trim().toUpperCase();
  if (icao.length < 3) return;
  const out    = document.getElementById('metar-out');
  const parsed = document.getElementById('metar-parsed');
  out.textContent = '→ Lade METAR für ' + icao + ' …';
  if (parsed) parsed.style.display = 'none';

  const proxy = '/pilot/metar_proxy.php?icao=' + encodeURIComponent(icao);

  try {
    const r    = await fetch(proxy);
    const data = await r.json();
    if (!data.found || !data.metar) {
      out.textContent = '⚠ Kein METAR gefunden für ' + icao;
      return;
    }
    out.textContent = data.metar;
    parseMetar(data.metar);
  } catch(e) {
    out.textContent = '⚠ Abruf fehlgeschlagen — Proxy nicht erreichbar?';
  }
}

function parseMetar(raw) {
  const parsed = document.getElementById('metar-parsed');
  const row = document.getElementById('mp-row');
  if (!parsed || !row) return;
  row.innerHTML = '';
  const wind  = raw.match(/(\d{3}|VRB)(\d{2,3})(G\d{2,3})?KT/);
  const vis   = raw.match(/\s(\d{4})\s|\s(\d+)SM\s/);
  const cloud = raw.match(/(FEW|SCT|BKN|OVC)(\d{3})/);
  const temp  = raw.match(/\s(M?\d{2})\/(M?\d{2})\s/);
  const qnh   = raw.match(/Q(\d{4})/);
  function add(lbl, val, cls='') {
    const d = document.createElement('div');
    d.className = 'mp-item';
    d.innerHTML = `<span class="mp-lbl">${lbl}</span><span class="mp-val ${cls}">${val}</span>`;
    row.appendChild(d);
  }
  if (wind) {
    const g = wind[3] ? ' G'+wind[3].replace('G','') : '';
    const kt = parseInt(wind[2]);
    add('Wind', wind[1]+'°/'+wind[2]+g+' kt', kt>25?'crit':kt>15?'warn':'ok');
  }
  if (vis) {
    const v = vis[1]||vis[2];
    const vm = vis[1] ? parseInt(v) : parseInt(v)*1852;
    add('Sicht', vis[1]?(parseInt(v)/1000).toFixed(1)+' km':v+' SM', vm<3000?'crit':vm<5000?'warn':'ok');
  }
  if (cloud) {
    const ft = parseInt(cloud[2])*100;
    add('Wolken', cloud[1]+' '+ft.toLocaleString()+' ft', ft<1000?'crit':ft<3000?'warn':'ok');
  }
  if (temp) add('Temp/Dew', temp[1].replace('M','-')+'° / '+temp[2].replace('M','-')+'°');
  if (qnh)  add('QNH', qnh[1]+' hPa');
  if (row.children.length) parsed.style.display = 'block';
}

document.addEventListener('DOMContentLoaded', () => {
  const inp = document.getElementById('icao-input');
  if (inp) inp.addEventListener('keydown', e => { if (e.key==='Enter') fetchMetar(); });
});
