# CHARTE GRAPHIQUE — RÉFÉRENTIEL DESIGN SIBEA
## Design System exécutable Tailwind CSS v4 + Flux UI Free

> Référentiel technique issu du CDC V1.6 §1. Ce document est la source de vérité design → code. Aucune couleur codée en dur hors tokens.

---

### 1. Identité
**Positionnement** `Solidité · Expertise · Innovation · Territoire` → fiable, techniquement compétente, structurée, moderne, projets complexes, engagement infrastructures.
**Univers** architecture, ingénierie, infrastructures, cartographie, plans techniques, topographie, réseaux, énergie. Éviter imagerie BTP banale (engins/casques/grues seules).

### 2. Tokens — `resources/css/app.css:11` `@theme`
```css
--color-primary-900: #0B1F33; /* header, footer, H1, sections institutionnelles */
--color-primary-800: #123A5A;
--color-primary-700: #185078;
--color-primary-100: #EAF2F7; /* fonds clair */

--color-neutral-950: #11181F; /* texte */
--color-neutral-900: #20282F; /* anthracite */
--color-neutral-700: #4A5560;
--color-neutral-500: #7B8792;
--color-neutral-300: #D9E0E6; /* bordures */
--color-neutral-100: #F4F6F8; /* fonds */
--color-background: #F4F6F8;
--color-surface: #FFFFFF;
--color-border: #D9E0E6;
--color-text: #20282F;

--color-accent-400: #F5B400; /* CTA, liens importants, chiffres clés */
--color-accent-300: #FFD45A; /* hover */
--color-success-600: #16805C; /* durable uniquement, ne concurrence pas bleu */
--color-warning: #F5B400; --color-danger:#DC2626; --color-info:#185078;

--font-display: 'Montserrat' 600/700/800; /* H1-H3, stats, CTA */
--font-sans: 'Inter' 400/500/600; /* body, nav, formulaires */

--radius-sm: .25rem; --radius-md:.375rem; /* architectural CDC 12 */
--color-accent-content:#11181F;
```
Zinc mappé sur neutres charte pour compat `app.css:35`.

### 3. Typo CDC 5-8
- **H1** `font-display 48-64/700-800` (mobile 36-44), **H2** 36-48/700, **H3** 28-32/600-700, **Body 16/400**, **Small 14/400-500**, **Caption 12-13/500**
- Hiérarchie stricte `H1 → H2 → H3 → H4`, un seul `H1` par page
- Titres courts, affirmatifs, scannables : *Construire aujourd'hui les infrastructures de demain*

### 4. Espacement & Grille CDC 9-11
Échelle `4,8,12,16,24,32,48,64,80,96,128` → `py-16/20/24/32` sections. Grille `12 desktop / 8 tablet / 4 mobile`, conteneur `max-w-[1280px]` `px-4 sm:px-6 lg:px-8 mx-auto`.

### 5. Boutons CDC 14 + Flux mapping `app.css:58` `@layer components`
- **Primary** `bg-accent text-primary-900 rounded-sm font-display font-semibold` `hover:bg-accent-300`
- **Secondary** `bg-primary-900 text-white rounded-sm`
- **Tertiary** `transparent border text-primary-900`
- États `Default|Hover|Focus ring-2 ring-accent|Active|Disabled` — focus visible sans jaune seul.

### 6. Cards CDC 15
`Image 4:3/16:9 object-cover → catégorie 11px uppercase → titre display 16-18/600 → description neutre-700 → infos → action` — `border-border bg-surface rounded-md shadow-[0_1px_2px_rgba(11,31,51,0.06)]` `@layer components [data-flux-card]`

### 7. Images CDC 16-18
Photos réelles chantiers/ouvrages/équipes/aériennes, `object-cover`, ratios `Hero 16:9`, `Réalisations 4:3`, `Actualités 16:9`, overlay `bg-primary-900/70`, pas de filtre lourd.

### 8. Icono CDC 19 + motifs CDC 20
`line/outline géométrique minimaliste` épaisseur cohérente. Motifs topographiques/grilles/plans en `opacity-[0.04]` secondaire.

### 9. Hero CDC 21
`IMAGE FORTE + OVERLAY primary-900/70 + SUR-TITRE accent 12px tracking-[0.2em] + H1 display 56px + DESCRIPTION 18px white/80 + CTA accent + CTA secondaire outline` → `resources/views/public/home.blade.php:3` `bg-primary-900` + `bg-accent`.

### 10. Sections CDC 22
`Eyebrow (accent 12px) → Titre display 36px primary-900 → Intro neutral-700 → Contenu → Action` — alternance `bg-surface / bg-background / bg-primary-900`.

### 11. Navigation CDC 23
Header `sticky bg-primary-900 text-white border-primary-900/10`, nav `text-white/80 hover:text-accent`, active `text-accent`, CTA `bg-accent text-primary-900`, mobile `flux:sidebar` — `resources/views/layouts/public.blade.php:24` + `components/layouts/public.blade.php`.

### 12. Footer CDC 24
`bg-primary-900` 4 colonnes `Entreprise|Activités|Réalisations|Contact` + `Mentions légales|Copyright` `border-white/10`, trait `h-px w-12 bg-accent`.

### 13. Formulaires CDC 25
`label + champ + aide + error`, `border-border bg-surface rounded-sm`, focus `ring-2 ring-accent`, visible sans jaune seul. Labels Flux `data-flux-label`.

### 14. Badges CDC 26
`rounded-full compact` `bg-primary-100 / neutral-100 / accent / success` — `[data-flux-badge]`.

### 15. Animations CDC 27
Rapide, discrète : `transition hover:shadow-sm, scale-[1.02], color` — pas de rotation/3D/parallax.

### 16. Responsive CDC 28 — Mobile First → Tablet → Desktop → Large, `12/8/4` colonnes, H1 réduit `36-44`.

### 17. Accessibilité CDC 29
Contraste `primary-900/white AA`, texte `16px`, focus `ring-2`, boutons `44px`, `alt` images.

### 18. Règles d'usage CDC 32
Faire : blanc, photos réelles, jaune parcimonie, hiérarchie forte, lignes géométriques, bleu institutionnel. Éviter : surcharge jaune/icônes, gradients, ombres lourdes, multiples fonts/boutons, template.

### 19. Mapping Flux CDC 31
`Button Primary→Accent, Secondary→Primary, Ghost→transparent | Card Surface→white Border→neutral Radius→md | Badge compact` — `app.css:58`.

### 20. Implémentation
- Tokens : `resources/css/app.css:11`
- Fonts : `@import bunny.net Montserrat|Inter` `app.css:1` + `vite.config.js:15` `bunny('Instrument Sans')` fallback, `font-display`/`font-sans`
- Build : `npm run build` OK `public/build/assets/app-*.css` `267kB`

> Règle d'or CDC 35 : `Réalisation > Image > Expertise > Données > Explication > CTA` — prouvé dans `public/home.blade.php` (hero → expertises → réalisations → actualités → témoignages → partenaires → CTA).
