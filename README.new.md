# Fineprint

Fineprint is the little DSL that keeps `nofollow.club` sane. A template manifest tells you *what* gets rendered and in what order. A theme manifest tells you *how* it should look. The runtime walks the manifest tree, calls your PHP partials, and hands them the token bag so they can spit out real HTML + CSS variables. Think layout composer, not yet another component framework.

## What’s in this folder

```
fineprint/
├── README.md                ← this doc
├── examples/
│   ├── templates/
│   │   ├── forum/template.fine
│   │   └── blocks/
│   │       ├── layout-root.php
│   │       ├── layout-stack.php
│   │       └── page-summary.php
│   └── themes/default/theme.fine
└── src/runtime.php          ← bootstraps the parser/theme/runtime from the forum project
```

- `examples/templates/forum/template.fine` is the real manifest powering the forum.
- `examples/templates/blocks/*.php` show how tokens land as CSS variables.
- `examples/themes/default/theme.fine` is the token catalog (palette, spacing, component chrome).
- `src/runtime.php` is a tiny shim that reuses the parser/runtime sitting in `nofollow.club/inc/fineprint/`.

## How the language reads

Manifest indentation matters. Every `>` nudges a block deeper into the tree.

```
>layout-root
>>layout-stack:variant=page gap=lg
>>>page-summary
>>>!if:has_threads
>>>>layout-stack:variant=thread-area gap=section
>>>>>!loop:thread_groups
>>>>>>thread-group
```

- Block names map to PHP files in `examples/templates/blocks/`.
- `!if:flag` checks `$context['flag']` before rendering its kids.
- `!loop:list_key` iterates `$context['list_key']` and merges each row into the child context.
- Parameters (`:gap=lg`) show up in `$blockParams` and are usually forwarded to design tokens.

Themes are just `path.to.token value` lines:

```
theme default

palette.background.base #0f1115
spacing.stack.lg 24px
component.page-summary.padding 20px 24px
```

Call `theme_token($themeTokens, 'spacing.stack.lg')` inside a block to grab the value. Multi-part tokens (`20px 24px`) survive intact.

## Runtime in one breath

1. `load_layout('examples/templates/forum')` parses & caches `template.fine`.
2. `load_theme('examples/themes/default')` does the same for tokens.
3. `render_tree($layout['tree'], $context, $themeTokens)` walks nodes:
   - Blocks become PHP includes.
   - Logic nodes (`!if`, `!loop`) control traversal.
   - `$render_children()` lets a block render its nested structure when it’s ready.

That’s the whole pipeline.

## Layout primitives you’ll touch

- **`layout-root`**: emits every CSS variable (palette, fonts, spacing, shadows, container width). Wrap the entire page in it once.
- **`layout-stack`**: a flex column with a theme-driven gap. `gap=lg` picks `spacing.stack.lg`; adding `variant=thread-area` gives you an extra class if you need it.

Spacing stacks live under `spacing.stack.*` in the theme. When you write `layout-stack:gap=section`, the block exports `--fp-stack-gap` with whatever value the theme supplies.

## Using the starter

1. Copy this folder next to your app.
2. Pull the runtime into your bootstrap:

   ```php
   require __DIR__.'/fineprint/src/runtime.php';

   $layout = Fineprint\load_layout('examples/templates/forum');
   $theme  = Fineprint\load_theme('examples/themes/default');

   Fineprint\render_tree($layout['tree'], $context, $theme);
   ```

3. Swap in your own `$context` and partials.
4. Delete `build/layouts/*.json` / `build/themes/*.json` when you edit `.fine` files (or let the runtime regenerate them automatically).
5. Point your CSS at the emitted `--fp-*` variables so the theme actually matters.

## Authoring rules of thumb

- **Structure lives in the template.** Prep your data beforehand.
- **Style lives in the theme.** If a design choice might change per theme, drop it in `theme.fine`.
- **Blocks stay dumb.** Use `theme_token()` and `$blockParams`, then hand off to `$render_children()` when you’re done.
- **Diff everything.** `.fine` files are plain text, so layout changes are obvious in Git.

## What Fineprint isn’t

- It doesn’t ship CSS—your stylesheet just consumes the tokens.
- It doesn’t replace PHP—you still write partials in the language you already use.
- It doesn’t manage data—you pass in `$context` yourself.

## License

MIT. Fork it, rename it, aim it at a new runtime—whatever works. If you build something rad, ping me.
