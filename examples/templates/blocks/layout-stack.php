<?php
/**
 * Flex column helper with theme-driven spacing stack.
 *
 * @var array $ctx
 * @var array $themeTokens
 * @var array $blockParams
 * @var callable $render_children
 */

use function Fineprint\theme_token;

$gapKey = $blockParams['gap'] ?? $ctx['gap'] ?? 'md';
$gapToken = 'spacing.stack.'.$gapKey;
$gapValue = theme_token($themeTokens, $gapToken) ?? theme_token($themeTokens, 'spacing.stack.md');

$style = [];
if ($gapValue) {
    $style[] = '--fp-stack-gap: '.$gapValue;
}

$classes = ['fp-stack'];
if (!empty($blockParams['variant'])) {
    $classes[] = 'fp-stack--'.preg_replace('/[^a-z0-9_-]/i', '', $blockParams['variant']);
}

$styleAttr = $style ? ' style="'.htmlspecialchars(implode(';', $style), ENT_QUOTES).'"' : '';
?>
<div class="<?=implode(' ', $classes)?>"<?=$styleAttr?>>
  <?php $render_children(); ?>
</div>
