<?php
/**
 * Forum summary header block.
 * Mirrors the production implementation but simplified for the example.
 *
 * @var array $ctx
 * @var array $themeTokens
 * @var array $blockParams
 * @var callable $render_children
 */

use function Fineprint\theme_token;

$filters = $ctx['filters'] ?? [];
$currentFilter = $ctx['current_filter'] ?? 'latest';
$viewTabs = $ctx['view_tabs'] ?? [];
$currentMode = $ctx['current_mode'] ?? 'latest';
$pageUrl = $ctx['page_url'] ?? '/index.php';
$newThreadUrl = $ctx['new_thread_url'] ?? '/new_thread.php';
$isLoggedIn = $ctx['is_logged_in'] ?? false;

$activeFilterLabel = 'active';
foreach ($filters as $filter) {
    if (($filter['value'] ?? null) === $currentFilter) {
        $activeFilterLabel = $filter['label'] ?? $activeFilterLabel;
        break;
    }
}

$style = [];
$map = [
    '--fp-page-summary-background' => 'component.page-summary.background',
    '--fp-page-summary-border' => 'component.page-summary.border',
    '--fp-page-summary-shadow' => 'component.page-summary.shadow',
    '--fp-page-summary-padding' => 'component.page-summary.padding',
    '--fp-page-summary-gap' => 'component.page-summary.gap',
];

foreach ($map as $var => $token) {
    $value = theme_token($themeTokens, $token);
    if ($value !== null && $value !== '') {
        $style[] = $var.': '.$value;
    }
}

$styleAttr = $style ? ' style="'.htmlspecialchars(implode(';', $style), ENT_QUOTES).'"' : '';
?>
<section class="page-summary" aria-label="thread filters"<?=$styleAttr?>>
  <div class="summary-filters">
    <div class="filter-select" data-dropdown>
      <button type="button" class="filter-select__trigger" data-dropdown-trigger>
        <span class="filter-select__value"><?=htmlspecialchars($activeFilterLabel, ENT_QUOTES)?></span>
        <svg class="filter-select__arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
          <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
      <div class="filter-select__menu" data-dropdown-menu>
        <?php foreach ($filters as $filterOption): 
          $requiresLogin = $filterOption['requires_login'] ?? false;
          if ($requiresLogin && !$isLoggedIn) {
              continue;
          }
          $value = $filterOption['value'] ?? '';
          $isActive = $value === $currentFilter;
          $href = $pageUrl.'?'.http_build_query([
              'mode' => $currentMode,
              'filter' => $value,
          ]);
        ?>
          <a href="<?=htmlspecialchars($href, ENT_QUOTES)?>" class="filter-select__option<?=$isActive ? ' is-active' : ''?>"><?=htmlspecialchars($filterOption['label'] ?? $value, ENT_QUOTES)?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <nav class="view-toggle" role="tablist" aria-label="view mode">
      <?php foreach ($viewTabs as $tab):
        $value = $tab['value'] ?? '';
        $isCurrent = $currentMode === $value;
        $href = $pageUrl.'?'.http_build_query([
            'mode' => $value,
            'filter' => $currentFilter,
        ]);
      ?>
        <a class="view-toggle__item<?=$isCurrent ? ' is-active' : ''?>" href="<?=htmlspecialchars($href, ENT_QUOTES)?>" role="tab" aria-selected="<?=$isCurrent ? 'true' : 'false'?>"><?=htmlspecialchars($tab['label'] ?? $value, ENT_QUOTES)?></a>
      <?php endforeach; ?>
    </nav>
  </div>
  <a href="<?=htmlspecialchars($newThreadUrl, ENT_QUOTES)?>" class="btn btn--primary summary-cta">+ NEW THREAD</a>
</section>
