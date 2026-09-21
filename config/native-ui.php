<?php

/**
 * Native UI — Theme Tokens
 *
 * Published via `php artisan vendor:publish --tag=native-ui-config`.
 * Edit to customize your app's visual identity in one place.
 *
 * For dynamic per-tenant theming, use Native\Mobile\UI\Theme::merge([...])
 * from a service provider. Runtime merges deep-merge on top of these values.
 *
 * Decision log: /docs/NATIVE-UI-REWRITE-PLAN.md (D — theme layer)
 */

return [

    /*
    |---------------------------------------------------------------------------
    | Theme
    |---------------------------------------------------------------------------
    |
    | Color tokens (open-ended map), 4 radii, 4 font sizes, font family.
    |
    | "on-X" means "color of content placed ON a surface of color X"
    |   — i.e., text/icons on that background.
    |
    | The token map is OPEN-ENDED: add any key your design needs (e.g. a
    | `warning` pair) to both blocks and `bg-theme-warning` /
    | `text-theme-on-warning` / `border-theme-warning` resolve immediately.
    | Theme classes also accept opacity modifiers — `bg-theme-primary/15`
    | is the tonal-fill idiom (the alpha applies to the dark companion
    | too). In PHP (layout chrome builders, dynamic styling) read tokens
    | with the appearance-aware `theme()` helper: `theme('primary')`.
    |
    | Color tokens accept:
    |   - CSS hex: '#B91C1C', '#F00', or with alpha '#8B5CF680' (#RRGGBBAA)
    |   - Tailwind palette names: 'red-300', 'orange-800'
    |   - Opacity modifiers on either: 'red-300/20', '#8B5CF6/50'
    |
    | Dark mode is auto-derived from `light` when `dark` is not set. To opt
    | into explicit dark tokens, fill out the `dark` block.
    |
    | The default pairs meet WCAG AA (4.5:1) — if you customize, keep each
    | `on-*` color at 4.5:1 contrast against its background token.
    |
    */

    'theme' => [

        'light' => [
            // Halloween night: pumpkin orange + witch purple on midnight.
            'primary' => '#F97316',
            'on-primary' => '#1C0A00',
            'secondary' => '#A855F7/25',
            'on-secondary' => '#F3E8FF',
            'surface' => '#231433',
            'on-surface' => '#FDF4E7',
            'background' => '#120A1C',
            'on-background' => '#FDF4E7',
            'surface-variant' => '#34204A',
            'on-surface-variant' => '#D8B4FE',
            'outline' => '#6B21A8',
            'outline-variant' => '#3B1D5C',
            'destructive' => '#EF4444',
            'on-destructive' => '#FFFFFF',
            'success' => '#84CC16',
            'on-success' => '#1A2E05',
            'accent' => '#A3E635',
            'on-accent' => '#1A2E05',

            // Game pieces + outcome colors.
            'player-x' => '#FB923C',
            'player-o' => '#A3E635',
            'draw' => '#C084FC',
        ],

        'dark' => [
            'primary' => '#F97316',
            'on-primary' => '#1C0A00',
            'secondary' => '#A855F7/25',
            'on-secondary' => '#F3E8FF',
            'surface' => '#1C1029',
            'on-surface' => '#FDF4E7',
            'background' => '#0B0612',
            'on-background' => '#FDF4E7',
            'surface-variant' => '#2B1A3E',
            'on-surface-variant' => '#D8B4FE',
            'outline' => '#6B21A8',
            'outline-variant' => '#3B1D5C',
            'destructive' => '#F87171',
            'on-destructive' => '#1C0A00',
            'success' => '#A3E635',
            'on-success' => '#1A2E05',
            'accent' => '#A3E635',
            'on-accent' => '#1A2E05',

            'player-x' => '#FB923C',
            'player-o' => '#A3E635',
            'draw' => '#C084FC',
        ],

        // Corner radii (points / dp).
        'radius-sm' => 4,
        'radius-md' => 8,
        'radius-lg' => 20,
        'radius-full' => 9999,

        // Font size scale (points / sp).
        'font-sm' => 14,
        'font-md' => 16,
        'font-lg' => 20,
        'font-xl' => 24,

    ],

    /*
    |---------------------------------------------------------------------------
    | Fonts
    |---------------------------------------------------------------------------
    |
    | Semantic names for bundled fonts (resources/fonts/ file tokens, minus
    | the extension). Use an alias anywhere a font token works — the `font`
    | attribute (`font="accent"`), chrome ->font() builders, or the layout
    | $font property. The `default` alias is the app-wide default font:
    | 'System' resolves to the platform face (San Francisco on iOS, Roboto
    | on Android); set a bundled token to apply it everywhere. Download one
    | with `php artisan native:font Inter --default`. Per-element `font`
    | attributes and font-serif / font-mono classes still win over the default.
    |
    |   'fonts' => [
    |       'default' => 'Inter-Regular',
    |       'accent'  => 'DynaPuff-Regular',
    |   ],
    |
    */

    'fonts' => [
        'default' => 'Fredoka-Regular',
        'body' => 'Fredoka-Regular',
        'display' => 'Creepster-Regular',
    ],

];
