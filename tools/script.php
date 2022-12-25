<h2 id="color-choice"></h2>
<input class="color-range" type="range" min="0" max="100" value="75" title="Drag me, baby.">
<style>
  html,
  body {
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
  }

  body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
  }

  // color range styles
  .color-range {
    position: relative;
    z-index: 1;
    appearance: none;
    border-radius: 0.5em;
    background-color: rgba(0, 0, 0, 0.1);
    height: 0.5em;
    width: 66%;
    display: block;
    outline: none;
    margin: 4rem auto;
    transition: color 0.05s linear;
    background: linear-gradient(to right, rgb(255, 0, 0), rgb(255, 255, 0), rgb(0, 255, 0), rgb(0, 255, 255), rgb(0, 0, 255), rgb(255, 0, 255), rgb(255, 0, 0));

    &:focus {
      outline: none;
    }

    &:active,
    &:hover:active {
      cursor: grabbing;
      cursor: -webkit-grabbing;
    }

    &::-moz-range-track {
      appearance: none;
      opacity: 0;
      outline: none !important;
    }

    &::-ms-track {
      outline: none !important;
      appearance: none;
      opacity: 0;
    }

    &::-webkit-slider-thumb {
      height: 3em;
      width: 3em;
      border-radius: 2em;
      appearance: none;
      background: white;
      cursor: pointer;
      cursor: move;
      cursor: grab;
      cursor: -webkit-grab;
      border: 0.4em solid currentColor;
      transition: border 0.1s ease-in-out, box-shadow 0.2s ease-in-out, transform 0.1s ease-in-out;
      box-shadow: 0 0.4em 1em rgba(0, 0, 0, 0.15);

      &:active,
      &:hover:active {
        cursor: grabbing;
        cursor: -webkit-grabbing;
        transform: scale(0.975);
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
        border: 1.5em solid currentColor;
      }

      &:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      }
    }

    &::-moz-range-thumb {
      height: 3em;
      width: 3em;
      border-radius: 2em;
      appearance: none;
      background: white;
      border: 0.4em solid currentColor;
      cursor: pointer;
      cursor: move;
      cursor: grab;
      cursor: -webkit-grab;
      transition: box-shadow 0.2s ease-in-out, transform 0.1s ease-in-out;
      box-shadow: 0 1px 11px rgba(0, 0, 0, 0);

      &:active,
      &:hover:active {
        cursor: grabbing;
        cursor: -webkit-grabbing;
        transform: scale(0.975);
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
      }

      &:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      }
    }
  }
</style>
<script>
  var colorRange = document.querySelector('.color-range')
  var randomRange = Math.floor(100 * Math.random())
  var colorChoice = document.getElementById("color-choice")

  colorRange.addEventListener('input', function(e) {
    var hue = ((this.value / 100) * 360).toFixed(0)
    var hsl = "hsl(" + hue + ", 100%, 50%)"
    var bgHsl = "hsl(" + hue + ", 100%, 95%)"
    colorRange.style.color = hsl
    colorChoice.style.color = hsl
    colorChoice.innerHTML = hsl
    document.body.style.background = bgHsl
  });
  colorRange.value = randomRange;
  var event = new Event('input');
  colorRange.dispatchEvent(event);
</script>

<?
function rgbToHsl($r, $g, $b)
{
  $r /= 255;
  $g /= 255;
  $b /= 255;

  $max = max($r, $g, $b);
  $min = min($r, $g, $b);
  $l   = ($max + $min) / 2;
  $d   = $max - $min;

  if ($d === 0) {
    $h = $s = 0; // achromatic
  } else {
    $s = $d / (1 - abs(2 * $l - 1));

    switch ($max) {
      case $r:
        $h = 60 * fmod((($g - $b) / $d), 6);
        if ($b > $g) {
          $h += 360;
        }
        break;
      case $g:
        $h = 60 * (($b - $r) / $d + 2);
        break;
      case $b:
        $h = 60 * (($r - $g) / $d + 4);
        break;
    }
  }

  return [round($h, 2), round($s, 2), round($l, 2)];
}

function hslToRgb($h, $s, $l)
{
  $c = (1 - abs(2 * $l - 1)) * $s;
  $x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
  $m = $l - ($c / 2);

  if ($h < 60) {
    $r = $c;
    $g = $x;
    $b = 0;
  } else if ($h < 120) {
    $r = $x;
    $g = $c;
    $b = 0;
  } else if ($h < 180) {
    $r = 0;
    $g = $c;
    $b = $x;
  } else if ($h < 240) {
    $r = 0;
    $g = $x;
    $b = $c;
  } else if ($h < 300) {
    $r = $x;
    $g = 0;
    $b = $c;
  } else {
    $r = $c;
    $g = 0;
    $b = $x;
  }

  $r = ($r + $m) * 255;
  $g = ($g + $m) * 255;
  $b = ($b + $m) * 255;

  return [floor($r), floor($g), floor($b)];
}
