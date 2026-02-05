<?php
$institution = "Hospital de Puerto Aysén";
$portalTitle = "Plataformas Internas";
$subtitle    = "Acceso centralizado a sistemas institucionales.";

$logoUrl = "/logo_HPA.png";
$backgroundUrl = "/fondo_Platafromas.png";

$platforms = [
  [
    "name"   => "Soporte TI",
    "desc"   => "Mesa de ayuda, tickets e inventario TI.",
    "url"    => "http://10.3.190.10/soporte-ti",
    "status" => "operativa",
    "emoji"  => "🖥️",
  ],
  [
    "name"   => "Vacunas",
    "desc"   => "Inventario y control de stock / trazabilidad.",
    "url"    => "http://10.3.190.10/vax-control/public",
    "status" => "operativa",
    "emoji"  => "💉",
  ],
  [
    "name"   => "Gestión Mantención",
    "desc"   => "Órdenes de trabajo, equipos y pautas (en preparación).",
    "url"    => "",
    "status" => "pronto",
    "emoji"  => "🛠️",
  ],
];

function badge($status){
  $s = strtolower($status);
  if ($s === "pronto") {
    return ["Próximamente", "bg-slate-500/15 text-slate-700 ring-slate-500/25 dark:text-slate-200 dark:ring-slate-100/15"];
  }
  return ["Operativa", "bg-emerald-500/15 text-emerald-700 ring-emerald-500/25 dark:text-emerald-200 dark:ring-emerald-300/15"];
}

$year = date("Y");
?>
<!doctype html>
<html lang="es" class="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($portalTitle) ?> | <?= htmlspecialchars($institution) ?></title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<script>
tailwind.config = {
  darkMode: 'class',
  theme: { extend: { fontFamily: { sans: ['Inter','sans-serif'] } } }
}
</script>

<style>
body { font-family: Inter, sans-serif; }

.portal-bg{
  min-height:100vh;
  background-image:
    linear-gradient(180deg,rgba(255,255,255,.88),rgba(255,255,255,.94)),
    url("<?= htmlspecialchars($backgroundUrl) ?>");
  background-size:cover;
  background-position:center;
  background-repeat:no-repeat;
  background-attachment:fixed;
}
html.dark .portal-bg{
  background-image:
    linear-gradient(180deg,rgba(2,6,23,.80),rgba(2,6,23,.92)),
    url("<?= htmlspecialchars($backgroundUrl) ?>");
}

.surface{
  background:rgba(255,255,255,.86);
  border:1px solid rgba(15,23,42,.10);
  box-shadow:0 18px 55px rgba(15,23,42,.12);
  backdrop-filter:blur(10px);
  -webkit-backdrop-filter:blur(10px);
}
html.dark .surface{
  background:rgba(15,23,42,.65);
  border:1px solid rgba(148,163,184,.18);
  box-shadow:0 26px 70px rgba(0,0,0,.40);
}

.btn-primary{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
.disabled{opacity:.65;cursor:not-allowed}

/* ===== LINTERN SOLO EN LA TARJETA ===== */
.card{
  position: relative;
  overflow: hidden; /* clave: que el spotlight no salga */
  transition: transform .20s ease, box-shadow .20s ease;
}
.card:hover{ transform: translateY(-3px); }

/* capa spotlight (se activa con hover via JS) */
.card::before{
  content:"";
  position:absolute;
  inset:-1px;
  background: radial-gradient(
    220px circle at var(--mx, 50%) var(--my, 50%),
    rgba(255,255,255,.55) 0%,
    rgba(255,255,255,.18) 35%,
    rgba(255,255,255,0) 70%
  );
  opacity: 0;
  transition: opacity .18s ease;
  pointer-events:none;
}

html.dark .card::before{
  background: radial-gradient(
    220px circle at var(--mx, 50%) var(--my, 50%),
    rgba(255,255,255,.20) 0%,
    rgba(255,255,255,.08) 35%,
    rgba(255,255,255,0) 70%
  );
}

.card.lantern-on::before{
  opacity: 1;
}
</style>
</head>

<body class="portal-bg text-slate-900 dark:text-slate-100">
<div class="min-h-screen flex flex-col">

<main class="max-w-6xl mx-auto w-full px-5 py-10">

  <!-- HEADER -->
  <div class="surface rounded-3xl p-6 flex items-center justify-between gap-4">
    <div class="flex items-center gap-4">
      <img src="<?= htmlspecialchars($logoUrl) ?>" class="h-14 w-auto object-contain" alt="Hospital de Puerto Aysén">
      <div>
        <div class="text-sm text-slate-600 dark:text-slate-300"><?= htmlspecialchars($institution) ?></div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?= htmlspecialchars($portalTitle) ?></h1>
        <p class="text-slate-600 dark:text-slate-300 mt-1"><?= htmlspecialchars($subtitle) ?></p>
      </div>
    </div>

    <!-- Toggle tema (icono) -->
    <button id="themeToggle" class="surface w-11 h-11 rounded-full flex items-center justify-center" title="Cambiar tema">
      <span id="sun" class="hidden text-xl">☀️</span>
      <span id="moon" class="text-xl">🌙</span>
    </button>
  </div>

  <!-- PLATAFORMAS -->
  <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($platforms as $p):
      [$label, $badgeClass] = badge($p["status"]);
      $disabled = empty($p["url"]);
      $href = $disabled ? "javascript:void(0)" : $p["url"];
    ?>

      <a href="<?= htmlspecialchars($href) ?>"
         class="<?= $disabled ? 'disabled' : '' ?>"
         <?= $disabled ? '' : 'target="_blank" rel="noopener noreferrer"' ?>>

        <div class="surface card rounded-3xl p-6 h-full flex flex-col border border-slate-900/5 dark:border-slate-100/10">
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3 min-w-0">
              <div class="w-12 h-12 shrink-0 rounded-2xl bg-slate-900/5 dark:bg-slate-100/10 flex items-center justify-center text-2xl">
                <?= htmlspecialchars($p["emoji"]) ?>
              </div>

              <div class="min-w-0">
                <div class="font-bold text-lg leading-tight"><?= htmlspecialchars($p["name"]) ?></div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300 line-clamp-2">
                  <?= htmlspecialchars($p["desc"]) ?>
                </div>
              </div>
            </div>

            <span class="shrink-0 self-start whitespace-nowrap px-3 py-1 text-xs font-semibold rounded-full ring-1 <?= $badgeClass ?>">
              <?= htmlspecialchars($label) ?>
            </span>
          </div>

          <div class="mt-auto pt-6">
            <?php if($disabled): ?>
              <span class="text-sm font-semibold text-slate-500 dark:text-slate-300">Disponible pronto</span>
            <?php else: ?>
              <span class="btn-primary text-white px-4 py-2 rounded-xl font-semibold inline-flex items-center gap-2">
                Entrar <span aria-hidden="true">→</span>
              </span>
            <?php endif; ?>
          </div>
        </div>
      </a>

    <?php endforeach; ?>
  </section>
</main>

<!-- FOOTER -->
<footer class="mt-auto pb-8 text-center text-sm text-slate-600 dark:text-slate-300">
  <div class="surface inline-flex items-center gap-2 px-5 py-3 rounded-2xl">
    <strong><?= htmlspecialchars($institution) ?></strong> • Unidad de Informática • <?= htmlspecialchars($year) ?>
  </div>
</footer>

</div>

<script>
  // ===== Tema =====
  const html=document.documentElement;
  const sun=document.getElementById('sun');
  const moon=document.getElementById('moon');
  const btn=document.getElementById('themeToggle');

  function setTheme(t){
    if(t==='dark'){
      html.classList.add('dark');
      sun.classList.remove('hidden');
      moon.classList.add('hidden');
    } else {
      html.classList.remove('dark');
      moon.classList.remove('hidden');
      sun.classList.add('hidden');
    }
    localStorage.setItem('portal_theme',t);
  }
  setTheme(localStorage.getItem('portal_theme')||'dark');
  btn.onclick=()=>setTheme(html.classList.contains('dark')?'light':'dark');

  // ===== Linterna SOLO en cada tarjeta =====
  const cards = document.querySelectorAll('.card');

  cards.forEach(card => {
    card.addEventListener('mouseenter', () => card.classList.add('lantern-on'));
    card.addEventListener('mouseleave', () => card.classList.remove('lantern-on'));

    card.addEventListener('mousemove', (e) => {
      const r = card.getBoundingClientRect();
      const x = e.clientX - r.left;
      const y = e.clientY - r.top;
      card.style.setProperty('--mx', x + 'px');
      card.style.setProperty('--my', y + 'px');
    });
  });
</script>

</body>
</html>
