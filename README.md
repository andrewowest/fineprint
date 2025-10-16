# Fineprint

Fineprint is a tiny DSL for composing UI layouts. The template manifest describes the hierarchy of blocks to render; the theme manifest describes the design tokens those blocks should use. A runtime walks the tree, calls your partials, and hands them the token bag so they can output HTML and CSS variables. No component framework required.

## Folder tour

```
fineprint/
├── README.md                ← this file
├── examples/
│   ├── templates/
│   │   ├── demo/template.fine
│   │   └── blocks/
│   │       ├── layout-root.php
│   │       ├── layout-stack.php
│   │       └── summary.php
│   └── themes/default/theme.fine
└── src/runtime.php          ← thin bootstrap for the parser/theme/runtime
```

- `examples/templates/demo/template.fine` shows a small layout using the standard blocks.
- `examples/templates/blocks/*.php` turn tokens into markup and CSS custom properties.
- `examples/themes/default/theme.fine` contains the design tokens consumed by the blocks.
- `src/runtime.php` wires everything together so you can `require` one file and render.

## Reading a template manifest

Fineprint templates are indentation-based. Prefix each line with `>` to move deeper into the tree.

```
>layout-root
>>layout-stack:gap=lg
>>>summary
```

- `layout-root` maps to `examples/templates/blocks/layout-root.php`.
- `layout-stack` maps to the stack helper block and receives `gap=lg` via `$blockParams`.
- `summary` is another block rendering whatever HTML you want.
- Directives like `!if:condition_key` and `!loop:list_key` handle conditional rendering and iteration without embedding raw PHP.

## Defining a theme

A theme is just `path.to.token value` lines:

```
theme default

palette.background.base #050506
palette.text.primary #e8efff
spacing.stack.lg 32px
component.summary.padding 24px 28px
```

Tokens can be nested arbitrarily. Blocks call `Fineprint\theme_token($themeTokens, 'component.summary.padding')` (see `examples/templates/blocks/*.php`) to fetch the value at runtime.

## Runtime flow

1. `Fineprint\load_layout('examples/templates/demo')` parses the template into a tree and caches it.
2. `Fineprint\load_theme('examples/themes/default')` does the same for tokens.
3. `Fineprint\render_tree($layout['tree'], $context, $themeTokens)` walks the nodes, invoking blocks and honoring logic directives.
4. Blocks call `$render_children()` when they want to render their nested nodes.

That’s all you need to render a page with structure and styling separated.

## Built-in layout primitives

- **`layout-root`** – emits CSS custom properties for palette, typography, spacing, shadows, transitions, and container sizing. Wrap your entire page in it once.
- **`layout-stack`** – vertical flex utility. `gap=<key>` resolves to `spacing.stack.<key>` in the theme (`xs`, `sm`, `md`, `lg`, `section`, etc.). Optional `variant` adds a modifier class.

## Using the starter

1. Copy this directory next to your project.
2. Require the runtime:

   ```php
   require __DIR__.'/fineprint/src/runtime.php';

   $layout = Fineprint\load_layout('examples/templates/demo');
   $theme  = Fineprint\load_theme('examples/themes/default');

   Fineprint\render_tree($layout['tree'], $context, $theme);
   ```

3. Swap in your own context data and block partials.
4. Delete `build/layouts/*.json` or `build/themes/*.json` whenever you edit `.fine` files (the runtime also checks timestamps, but nuking the cache guarantees fresh output).
5. Point your CSS at the emitted `--fp-*` variables instead of hardcoding values.

## Authoring habits that help

- **Keep logic out of templates.** Prepare data before rendering; the manifest should stay declarative.
- **Keep design in the theme.** If it can change per theme, store it there.
- **Let blocks stay dumb.** Grab tokens using `theme_token()`, respect `$blockParams`, call `$render_children()`.
- **Review diffs.** `.fine` manifests are pure text; structural changes are easy to spot in version control.

## Not included on purpose

- No CSS framework. You write your own styles based on the tokens provided.
- No templating engine swap. Blocks are whatever language you’re already using (PHP in these examples).
- No data orchestration. You hand in the `$context` array.

## License

MIT. Rename it, swap out the runtime, drop it into a different stack—whatever makes sense. If you build something fun with Fineprint, let me know.
