<?php
/**
 * Calculadora Elo - Sistema de puntuación para partidas competitivas
 */
header('Content-Type: text/html; charset=utf-8');

$actual = $oponente = $resultadoPartida = '';
$nuevoElo = null; $cambio = null; $esperado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual          = (int)($_POST['actual'] ?? 0);
    $oponente        = (int)($_POST['oponente'] ?? 0);
    $resultadoPartida = $_POST['resultado'] ?? '';

    if ($actual > 0 && $oponente > 0 && in_array($resultadoPartida, ['1','0.5','0'], true)) {
        $K = 32; // Factor K estándar
        $esperado = 1 / (1 + pow(10, ($oponente - $actual) / 400));
        $nuevoElo = round($actual + $K * ($resultadoPartida - $esperado));
        $cambio = $nuevoElo - $actual;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calculadora Elo Online | ConfiguroWeb</title>
<meta name="description" content="Calcula tu puntuación Elo tras una partida. Sistema de rating para ajedrez, videojuegos y competencias. Gratis en ConfiguroWeb.">
<meta name="keywords" content="calculadora elo, rating elo, puntuacion ajedrez, mmr, matchmaking">
<meta property="og:type" content="website">
<meta property="og:title" content="Calculadora Elo Online">
<meta property="og:description" content="Calcula tu puntuación Elo tras una partida.">
<link rel="canonical" href="https://demoscweb.com/github/php-calculadora-elo/">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebApplication","name":"Calculadora Elo","applicationCategory":"UtilitiesApplication","operatingSystem":"Any","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"author":{"@type":"Person","name":"ConfiguroWeb","url":"https://configuroweb.com"}}
</script>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>🏆 Calculadora Elo</h1>
  <p class="subtitle">Calcula tu nuevo rating tras una partida</p>
</header>
<main>
  <form method="POST">
    <label for="actual">Tu Elo actual</label>
    <input type="number" name="actual" id="actual" value="<?php echo htmlspecialchars($actual); ?>" placeholder="1200" required>

    <label for="oponente">Elo del oponente</label>
    <input type="number" name="oponente" id="oponente" value="<?php echo htmlspecialchars($oponente); ?>" placeholder="1300" required>

    <label for="resultado">Resultado de la partida</label>
    <select name="resultado" id="resultado">
      <option value="1" <?php echo $resultadoPartida==='1'?'selected':''; ?>>🏆 Gané</option>
      <option value="0.5" <?php echo $resultadoPartida==='0.5'?'selected':''; ?>>🤝 Empaté</option>
      <option value="0" <?php echo $resultadoPartida==='0'?'selected':''; ?>>😞 Perdí</option>
    </select>

    <button type="submit" class="btn-primary">🏆 Calcular nuevo Elo</button>
  </form>

  <?php if ($nuevoElo !== null): ?>
  <div class="resultados">
    <h2>Resultados</h2>
    <div class="tarjeta-destacada">
      <span class="etiqueta">Nuevo Elo</span>
      <span class="valor-grande"><?php echo $nuevoElo; ?></span>
    </div>
    <div class="grid-3">
      <div class="tarjeta-sm">
        <span class="etiqueta">Elo anterior</span>
        <span class="valor-sm"><?php echo $actual; ?></span>
      </div>
      <div class="tarjeta-sm">
        <span class="etiqueta">Cambio</span>
        <span class="valor-sm <?php echo $cambio >= 0 ? 'pos' : 'neg'; ?>">
          <?php echo $cambio >= 0 ? '+' : ''; ?><?php echo $cambio; ?>
        </span>
      </div>
      <div class="tarjeta-sm">
        <span class="etiqueta">Probabilidad esperada</span>
        <span class="valor-sm"><?php echo round($esperado * 100, 1); ?>%</span>
      </div>
    </div>
    <p class="interpretacion">
      <?php if ($cambio > 0): ?>
        📈 Subiste <strong><?php echo $cambio; ?></strong> puntos. Tu probabilidad de ganar era del <?php echo round($esperado*100,1); ?>%.
      <?php elseif ($cambio < 0): ?>
        📉 Bajaste <strong><?php echo abs($cambio); ?></strong> puntos. Tu probabilidad de ganar era del <?php echo round($esperado*100,1); ?>%.
      <?php else: ?>
        ➡️ Sin cambios. Tu probabilidad de ganar era del <?php echo round($esperado*100,1); ?>%.
      <?php endif; ?>
    </p>
  </div>
  <?php endif; ?>

  <section class="info">
    <h2>¿Qué es el sistema Elo?</h2>
    <p>El sistema Elo es un método de cálculo de la fuerza relativa de los jugadores en juegos competitivos como ajedrez y videojuegos. Fue inventado por Arpad Elo, profesor de física y maestro de ajedrez.</p>
    <p class="formula">Esperado = 1 / (1 + 10^((Roponente - Ractual) / 400))</p>
  </section>
</main>
<footer>
  <p>Desarrollado por <a href="https://configuroweb.com" target="_blank">ConfiguroWeb</a> ·
     <a href="https://appscweb.com/citas/" target="_blank">Sistema de Citas</a> ·
     <a href="https://appscweb.com/negocios/" target="_blank">Gestión de Negocios</a></p>
  <p>&copy; <?php echo date('Y'); ?> ConfiguroWeb</p>
</footer>
<script src="assets/script.js"></script>
</body>
</html>