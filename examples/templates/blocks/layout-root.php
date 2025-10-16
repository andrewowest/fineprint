<?php
/**
 * Base wrapper that exposes theme tokens as CSS custom properties.
 *
 * @var array $ctx
 * @var array $themeTokens
 * @var array $blockParams
 * @var callable $render_children
 */

use function Fineprint\theme_token;

$cssVars = [
    '--fp-color-background' => 'palette.background.base',
    '--fp-color-surface' => 'palette.background.surface',
    '--fp-color-panel' => 'palette.background.panel',
    '--fp-color-border' => 'palette.border.default',
    '--fp-color-border-strong' => 'palette.border.strong',
    '--fp-color-accent' => 'palette.accent.base',
    '--fp-color-accent-subtle' => 'palette.accent.subtle',
    '--fp-color-accent-hover' => 'palette.accent.hover',
    '--fp-color-text' => 'palette.text.primary',
    '--fp-color-text-muted' => 'palette.text.muted',
    '--fp-color-text-subtle' => 'palette.text.subtle',
    '--fp-color-danger' => 'palette.feedback.danger',
    '--fp-font-body' => 'typography.body.family',
    '--fp-font-body-size' => 'typography.body.size',
    '--fp-font-body-line-height' => 'typography.body.line-height',
    '--fp-font-body-weight' => 'typography.body.weight',
    '--fp-font-heading' => 'typography.heading.family',
    '--fp-font-heading-weight' => 'typography.heading.weight',
    '--fp-font-heading-transform' => 'typography.heading.transform',
    '--fp-font-mono' => 'typography.mono.family',
    '--fp-font-mono-size' => 'typography.mono.size',
    '--fp-font-mono-weight' => 'typography.mono.weight',
    '--fp-spacing-stack-xs' => 'spacing.stack.xs',
    '--fp-spacing-stack-sm' => 'spacing.stack.sm',
    '--fp-spacing-stack-md' => 'spacing.stack.md',
    '--fp-spacing-stack-lg' => 'spacing.stack.lg',
    '--fp-spacing-section' => 'spacing.section',
    '--fp-radius-card' => 'radius.card',
    '--fp-radius-badge' => 'radius.badge',
    '--fp-radius-pill' => 'radius.pill',
    '--fp-shadow-soft' => 'shadow.soft',
    '--fp-shadow-card' => 'shadow.card',
    '--fp-transition-fast' => 'transition.fast',
    '--fp-transition-default' => 'transition.default',
    '--fp-root-max-width' => 'layout.container.width',
    '--fp-root-gutter' => 'layout.container.gutter',
];

$style = [];
foreach ($cssVars as $var => $token) {
    $value = theme_token($themeTokens, $token);
    if ($value !== null && $value !== '') {
        $style[] = $var.': '.$value;
    }
}

$styleAttr = $style ? ' style="'.htmlspecialchars(implode(';', $style), ENT_QUOTES).'"' : '';
?>
<div class="fp-root"<?=$styleAttr?>>
  <?php $render_children(); ?>
</div>
