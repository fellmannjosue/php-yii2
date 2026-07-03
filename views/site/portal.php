<?php
/** Vista compartida. Espera en scope: $FW, $functions, $compare, $formResult. */
$current = $FW['name'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hola Mundo · <?= htmlspecialchars($FW['name']) ?></title>
<style>
:root{--accent: <?= $FW['accent'] ?>;--accent2: <?= $FW['accent2'] ?>;
  --bg:#0f1115;--panel:#171a21;--line:#242833;--text:#e7ebf3;--muted:#98a2b3;}
*{box-sizing:border-box}
body{margin:0;font-family:"Segoe UI",system-ui,-apple-system,Roboto,Arial,sans-serif;
  background:radial-gradient(1000px 600px at 50% -10%,color-mix(in srgb,var(--accent) 22%,transparent),transparent 60%),var(--bg);
  color:var(--text);line-height:1.5}
.wrap{max-width:1080px;margin:0 auto;padding:2.5rem 1.25rem 4rem}
.badge{display:inline-flex;gap:.5rem;align-items:center;font-size:.78rem;font-weight:600;letter-spacing:.04em;
  text-transform:uppercase;color:var(--accent);background:color-mix(in srgb,var(--accent) 14%,transparent);
  border:1px solid color-mix(in srgb,var(--accent) 35%,transparent);padding:.3rem .7rem;border-radius:999px}
.hero{text-align:center;padding:2rem 0 2.5rem}
.hero h1{font-size:clamp(2.2rem,6vw,3.4rem);margin:.9rem 0 .4rem;letter-spacing:-.02em}
.hero h1 b{background:linear-gradient(90deg,var(--accent),var(--accent2));-webkit-background-clip:text;background-clip:text;color:transparent}
.hero p{color:var(--muted);max-width:640px;margin:.4rem auto 0}
.hola{font-size:1.05rem;color:var(--muted);margin-top:1rem}
h2{font-size:1.35rem;margin:2.6rem 0 1.1rem;display:flex;align-items:center;gap:.6rem}
h2 .n{font-size:.85rem;color:var(--accent);border:1px solid color-mix(in srgb,var(--accent) 35%,transparent);border-radius:8px;padding:.1rem .5rem}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem}
.card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:1.2rem}
.card h3{margin:.2rem 0 .5rem;font-size:1.08rem;display:flex;align-items:center;gap:.5rem}
.tag{margin-left:auto;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;padding:.15rem .5rem;border-radius:6px}
.tag.live{color:#052e1a;background:#34d399}
.tag.info{color:#0b1a3a;background:#93c5fd}
.card p{color:var(--muted);font-size:.9rem;margin:.2rem 0 .8rem}
pre{background:#0c0e13;border:1px solid var(--line);border-radius:10px;padding:.8rem;overflow:auto;font-size:.8rem;color:#cdd6e6;margin:0}
.card a.try{display:inline-block;margin-top:.7rem;color:var(--accent);font-size:.85rem;font-weight:600;text-decoration:none}
form.mini{margin-top:.7rem;display:flex;gap:.4rem}
form.mini input{flex:1;background:#0c0e13;border:1px solid var(--line);border-radius:8px;color:var(--text);padding:.5rem .7rem}
form.mini button{background:var(--accent);border:none;border-radius:8px;color:#fff;font-weight:600;padding:.5rem .9rem;cursor:pointer}
.res{margin-top:.5rem;font-size:.85rem;padding:.4rem .6rem;border-radius:8px}
.res.ok{background:color-mix(in srgb,#34d399 18%,transparent);color:#a7f3d0}
.res.no{background:color-mix(in srgb,#f87171 18%,transparent);color:#fecaca}
table{width:100%;border-collapse:collapse;font-size:.86rem;overflow:hidden;border-radius:12px;border:1px solid var(--line)}
th,td{padding:.7rem .8rem;text-align:left;border-bottom:1px solid var(--line)}
th{background:#12151c;color:var(--muted);font-weight:600;text-transform:uppercase;font-size:.72rem;letter-spacing:.04em}
tr.me{background:color-mix(in srgb,var(--accent) 12%,transparent)}
tr.me td:first-child{font-weight:700;color:var(--accent)}
.tblwrap{overflow-x:auto}
footer{margin-top:3rem;text-align:center;color:var(--muted);font-size:.85rem}
footer a{color:var(--accent);text-decoration:none}
</style>
</head>
<body>
<div class="wrap">
  <div style="text-align:center"><span class="badge">● <?= htmlspecialchars($FW['kind']) ?> · PHP</span></div>
  <div class="hero">
    <h1>Hola Mundo desde <b><?= htmlspecialchars($FW['name']) ?></b></h1>
    <p><?= htmlspecialchars($FW['tagline']) ?></p>
    <div class="hola">👋 <code>echo "Hola Mundo";</code> — servido con <?= htmlspecialchars($FW['name']) ?> · PHP <?= PHP_VERSION ?></div>
  </div>

  <h2><span class="n">5</span> Funciones que puede hacer <?= htmlspecialchars($FW['name']) ?></h2>
  <div class="grid">
    <?php foreach ($functions as $f): ?>
    <div class="card">
      <h3><?= $f['icon'] ?> <?= htmlspecialchars($f['title']) ?>
        <span class="tag <?= $f['live'] ? 'live' : 'info' ?>"><?= $f['live'] ? 'En vivo' : 'Info' ?></span></h3>
      <p><?= htmlspecialchars($f['desc']) ?></p>
      <pre><?= htmlspecialchars($f['code']) ?></pre>
      <?php if (!empty($f['link'])): ?>
        <a class="try" href="<?= $f['link'] ?>" target="_blank"><?= htmlspecialchars($f['linkText']) ?></a>
      <?php endif; ?>
      <?php if (!empty($f['form'])): ?>
        <form class="mini" method="get" action="">
          <input type="text" name="email" placeholder="escribe un correo…" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
          <button>Validar</button>
        </form>
        <?php if ($formResult): ?><div class="res <?= $formResult['ok'] ? 'ok' : 'no' ?>"><?= htmlspecialchars($formResult['msg']) ?></div><?php endif; ?>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <h2>📊 Cuadro comparativo · 9 frameworks PHP</h2>
  <div class="tblwrap"><table>
    <thead><tr><th>Framework</th><th>Tipo / enfoque</th><th>Complejidad</th><th>Uso actual</th><th>Ideal para</th></tr></thead>
    <tbody>
      <?php foreach ($compare as $row): ?>
      <tr class="<?= $row[0] === $current ? 'me' : '' ?>"><?php foreach ($row as $c): ?><td><?= htmlspecialchars($c) ?></td><?php endforeach; ?></tr>
      <?php endforeach; ?>
    </tbody>
  </table></div>

  <footer>Demostración de <a href="<?= htmlspecialchars($FW['site']) ?>" target="_blank"><?= htmlspecialchars($FW['name']) ?></a>
    · Uno de los 9 portales de frameworks PHP · Hecho para comparar.</footer>
</div>
</body>
</html>
