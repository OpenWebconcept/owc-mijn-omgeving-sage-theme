# OWC Mijn Omgeving — Sage Theme

Starter theme for Dutch government "Mijn Omgeving" (citizen portal) projects. Built with [Sage](https://roots.io/sage/) on top of [Yard Brave](https://github.com/yardinternet/brave/), a combination of [Bedrock](https://roots.io/bedrock/), [Acorn](https://roots.io/acorn/) and [Sage](https://roots.io/sage/) with a few modifications and additional features.

> **This theme requires Brave as its project foundation.** Brave provides the build tooling, frontend dependencies, and PHP packages this theme depends on. Do not use this theme outside of a Brave project.

## Requirements

| Requirement | Version    |
| ----------- | ---------- |
| PHP         | >= 8.2     |
| WordPress   | 6.9.\*     |
| Node        | >= 22.12.0 |
| pnpm        | >= 10.17.1 |
| Composer    | >= 2       |

## Setup

### 1. Start from Brave

Clone the Brave boilerplate as your project foundation:

```bash
git clone https://github.com/yardinternet/brave/ my-project
cd my-project
```

Follow the [Brave setup instructions](https://github.com/yardinternet/brave/) to configure your environment (`.env`, database, etc.).

### 2. Add this theme

Add the theme to the root `composer.json`:

```json
{
	"require": {
		"owc/mijn-omgeving-sage-theme": "dev-main"
	}
}
```

When the package is installed via Composer, autoloading is handled by the package's own `composer.json`. The PSR-4 namespace entry below is only needed when developing the theme locally (e.g. as a path repository):

```json
{
	"autoload": {
		"psr-4": {
			"OWC\\MijnOmgeving\\": "web/app/themes/owc-mijn-omgeving/app/"
		}
	}
}
```

Then install:

```bash
composer install
```

The theme installs to `web/app/themes/owc-mijn-omgeving/`.

### 3. Install required plugins

This theme depends on the following Composer packages. Add them to the root `composer.json`:

```json
{
	"require": {
		"gravity/gravityforms": "^2.9",
		"plugin/owc-gravityforms-zgw": "^1.9",
		"plugin/owc-mijn-services": "^0.9",
		"wp-plugin/cmb2": "^2.12",
		"gravitypdf/gravity-pdf": "^6.14"
	}
}
```

> **Note:** `gravity/gravityforms` is a premium plugin. A valid Gravity Forms license and access to the private Composer repository are required to install it.

Additionally, install **one** of the following OpenID Connect plugins depending on your authentication provider:

| Plugin                       | Provider                                    | Version  |
| ---------------------------- | ------------------------------------------- | -------- |
| `plugin/owc-signicat-openid` | Signicat (DigiD / eHerkenning via Signicat) | `^3.2`   |
| `plugin/owc-anoigo-openid`   | Anoigo (DigiD / eHerkenning via Anoigo)     | `^1.1.0` |

**Local development and staging only** — to simulate a DigiD or eHerkenning login without a real identity provider, also add:

```json
{
	"require": {
		"owc/spoof-openid": "^2.0"
	}
}
```

The plugin can be installed on all environments, but must be disabled on production. Use `DISABLED_PLUGINS` in `config/application.php` to ensure it never runs in production:

```php
Config::define('DISABLED_PLUGINS', [
    'spoof-openid/index.php',
]);
```

### 5. Install frontend dependencies

All npm dependencies are managed by Brave's root `package.json`. From the project root:

```bash
nvm use
pnpm install
```

### 6. Build assets

Builds both themes and blocks:

```bash
pnpm run build
```

Watch for changes during development (themes and blocks in parallel):

```bash
pnpm run watch:themes & pnpm run watch:blocks"
```

### 7. Activate the theme

**Via WP-CLI:**

```bash
wp theme activate owc-mijn-omgeving
```

**Manually:**

Log in to the WordPress admin, go to **Appearance → Themes**, find **OWC Mijn Omgeving** and click **Activate**.

## Working with the theme

### Directory structure

```
owc-mijn-omgeving/
├── acf-json/          # ACF field group JSON (synced automatically)
├── app/               # PHP — Providers, Services, Hooks, Blocks, etc.
├── config/            # Laravel/Acorn config files
├── public/
│   └── build/         # Compiled assets (committed, no build step needed)
├── resources/
│   ├── fonts/
│   ├── images/
│   ├── scripts/
│   │   ├── blocks/    # Gutenberg block scripts
│   │   ├── editor/    # Gutenberg editor scripts
│   │   └── frontend/  # Front-end scripts
│   ├── styles/        # CSS source (Tailwind CSS 4)
│   └── views/         # Blade templates
├── storage/
│   └── provision/
│       └── sql/       # Example database export (see "Example database" below)
├── composer.json
├── functions.php
├── style.css          # WordPress theme header
└── theme.json         # WordPress theme.json
```

### Example database

`storage/provision/sql/owc-mijn-omgeving.sql` is a MySQL dump of a working local installation with demo content pre-configured. Import it to get a ready-to-use environment without manual setup:

```bash
wp db import web/app/themes/owc-mijn-omgeving/storage/provision/sql/owc-mijn-omgeving.sql
```

After importing, run a search-replace to update the site URL to your local domain:

```bash
wp search-replace 'https://brave.local' 'https://your-domain.local' --all-tables
```

After importing, log in to the WordPress admin with the demo account:

| Field    | Value                    |
| -------- | ------------------------ |
| Username | `owcdemo`                |
| Password | `8DDfzhXcYwqe!e^IbwWlwUIJ` |

> **Note:** This dump is intended for local development only. Never import it into a staging or production environment.

### Adding PHP classes

All PHP classes live under `app/` and use the `OWC\MijnOmgeving\` PSR-4 namespace. Register new service providers in `config/app.php` or `composer.json`'s `extra.acorn.providers`.

### Tailwind CSS

Styles use [Tailwind CSS v4](https://tailwindcss.com/). The main entry points are:

- `resources/styles/frontend.css` — front-end styles
- `resources/styles/editor.css` — Gutenberg editor styles

### NL Design System — Den Haag components

The theme uses CSS components from [`@gemeente-denhaag/side-navigation`](https://nl-design-system.github.io/denhaag/) (declared as a `devDependency` in the root `package.json` because it is consumed at build time only).

The following packages are direct runtime dependencies and must be declared in the root `package.json` `dependencies`:

| Package                              | Purpose                                                 |
| ------------------------------------ | ------------------------------------------------------- |
| `@yardinternet/a11y-cookie-yes`      | Accessibility integration for Cookie Yes consent banner |
| `@yardinternet/brave-frontend-kit`   | Shared frontend utilities from the Brave platform       |
| `@yardinternet/gutenberg-components` | Shared Gutenberg block components                       |
| `accordion-js`                       | Powers the `Accordion` frontend component               |
| `body-scroll-lock`                   | Locks body scroll when a `Dialog` is open               |
| `focus-trap`                         | Traps keyboard focus inside an open `Dialog`            |

These packages are consumed by `resources/scripts/frontend/components/`. Because pnpm only makes explicitly-declared dependencies available for imports, omitting them from the root `package.json` breaks the Vite build with an unresolvable-import error.

The following `devDependencies` must also be present in the root `package.json` for linting and the Vite build to work:

| Package                          | Purpose                        |
| -------------------------------- | ------------------------------ |
| `@yardinternet/eslint-config`    | Shared ESLint configuration    |
| `@yardinternet/prettier-config`  | Shared Prettier configuration  |
| `@yardinternet/stylelint-config` | Shared Stylelint configuration |
| `@yardinternet/vite-config`      | Shared Vite configuration      |
| `tailwindcss`                    | Tailwind CSS v4                |
| `vite`                           | Vite bundler                   |

The package's stylesheet is imported in `resources/styles/nlds/denhaag/side-navigation.css`:

```css
@import '@gemeente-denhaag/side-navigation/index.css';
```

Design tokens are overridden directly in that file via CSS custom properties to match the project's design. If you need to change the look of the side navigation, edit those `--denhaag-side-navigation-*` variables there rather than overriding the component output.

To upgrade the package, update the version in the root `package.json` and run `pnpm install` from the project root.

### ACF field groups

Field groups are stored as JSON in `acf-json/` and sync automatically with ACF Pro. Commit this directory to keep field definitions in version control.

## Linting & formatting

Run all linting and formatting from the Brave project root:

```bash
# JavaScript
pnpm lint:js
pnpm format:js

# CSS
pnpm lint:css
pnpm format:css

# Blade templates
pnpm format:blade
```

All lint and format commands delegate to `yard-toolkit` under the hood.

## License

MIT — see [LICENSE](https://opensource.org/licenses/MIT).
