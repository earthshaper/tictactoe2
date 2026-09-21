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
            'primary' => '#7C3AED',
            'on-primary' => '#FFFFFF',
            'secondary' => '#7C3AED/15',
            'on-secondary' => '#4C1D95',
            'surface' => '#FFFFFF',
            'on-surface' => '#1E1B4B',
            'background' => '#F5F3FF',
            'on-background' => '#1E1B4B',
            'surface-variant' => '#EDE9FE',
            'on-surface-variant' => '#5B21B6',
            'outline' => '#C4B5FD',
            'outline-variant' => '#DDD6FE',
            'destructive' => '#BE123C',
            'on-destructive' => '#FFFFFF',
            'success' => '#15803D',
            'on-success' => '#FFFFFF',
            'accent' => '#C2410C',
            'on-accent' => '#FFFFFF',

            // Game pieces + outcome colors.
            'player-x' => '#E11D48',
            'player-o' => '#0284C7',
            'draw' => '#A16207',
        ],

        'dark' => [
            'primary' => '#A78BFA',
            'on-primary' => '#1E1B4B',
            'secondary' => '#A78BFA/20',
            'on-secondary' => '#EDE9FE',
            'surface' => '#1E1B4B',
            'on-surface' => '#F5F3FF',
            'background' => '#110E2E',
            'on-background' => '#F5F3FF',
            'surface-variant' => '#2E2A66',
            'on-surface-variant' => '#C4B5FD',
            'outline' => '#4C1D95',
            'outline-variant' => '#312E81',
            'destructive' => '#FB7185',
            'on-destructive' => '#1E1B4B',
            'success' => '#4ADE80',
            'on-success' => '#052E16',
            'accent' => '#FDBA74',
            'on-accent' => '#1E1B4B',

            'player-x' => '#FB7185',
            'player-o' => '#38BDF8',
            'draw' => '#FACC15',
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
        'display' => 'LilitaOne-Regular',
    ],

];
