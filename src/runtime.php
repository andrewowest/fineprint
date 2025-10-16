<?php

namespace Fineprint; // reuse the namespace from the main project

require_once __DIR__.'/../../nofollow.club/inc/fineprint/parser.php';
require_once __DIR__.'/../../nofollow.club/inc/fineprint/theme.php';
require_once __DIR__.'/../../nofollow.club/inc/fineprint/runtime.php';

// This file intentionally re-exports helper functions from the forum project so the examples
// in this folder can be executed by including `fineprint/src/runtime.php` directly.
//
// Usage:
//   require __DIR__.'/fineprint/src/runtime.php';
//   $layout = Fineprint\load_layout('examples/templates/forum');
//   $theme = Fineprint\load_theme('examples/themes/default');
//   Fineprint\render_tree($layout['tree'], $context, $theme);
