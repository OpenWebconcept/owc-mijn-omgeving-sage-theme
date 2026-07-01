# OWC Mijn Omgeving — Sage Theme

Starter theme for Dutch government "Mijn Omgeving" (citizen portal) projects. Built with [Sage](https://roots.io/sage/) on top of [Yard Brave](https://github.com/yardinternet/brave/), a combination of [Bedrock](https://roots.io/bedrock/), [Acorn](https://roots.io/acorn/) and [Sage](https://roots.io/sage/) with a few modifications and additional features.

> **This theme requires Brave as its project foundation.** Brave provides the build tooling, frontend dependencies, and PHP packages this theme depends on. Do not use this theme outside of a Brave project.

## Requirements

| Requirement | Version    |
| ----------- | ---------- |
| PHP         | >= 8.2     |
| WordPress   | 6.9.\*     |
| Node        | >= 22.12.0 |
| pnpm        | >= 10.34.4 |
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

The `@yardinternet/*` packages are published to GitHub Packages. The committed `.npmrc` maps the scope to that registry, but GitHub Packages requires a token even to read. Provide your own:

1. Create a GitHub [token](https://github.com/settings/tokens) with the `read:packages` scope.
2. Add it in the place of `${GITHUB_TOKEN}`, or export it (add to your shell profile to persist):

   ```bash
   export GITHUB_TOKEN=ghp_your_token_here
   ```

3. Install:

   ```bash
   nvm use
   pnpm install
   ```

`.npmrc` references `${GITHUB_TOKEN}` from the environment — never commit a token into it.

### 6. Configure the dev server

Copy the env template and set `WP_HOME` to your local site URL.

```bash
cp .env.example .env
```

### 7. Build assets

Build once, after that watch during development (themes and blocks in parallel):

```bash
pnpm run build
pnpm run watch
```

See [Asset build](#asset-build) for running from a Brave project root.

### 8. Activate the theme

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
│   └── build/         # Compiled assets
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

### Asset build

The theme carries its own `package.json`, so you run the asset build from the theme directory:

```bash
pnpm run build   # or: pnpm run watch
```

In a Brave context you can instead run the same scripts from the project root, which builds every theme at once. The tooling auto-detects where it runs — no configuration needed.

### Tailwind CSS

Styles use [Tailwind CSS v4](https://tailwindcss.com/). The main entry points are:

- `resources/styles/frontend.css` — front-end styles
- `resources/styles/editor.css` — Gutenberg editor styles

### NL Design System (NLDS)

This theme uses NLDS only **partially** — in both which components are used and how they are integrated.

Where things live:

- **Components** — in the [`owc-mijn-services`](https://github.com/OpenWebconcept/plugin-owc-mijn-services) plugin at `src/Views/partials/nlds/denhaag/` (`status`, `description-list`, `card`, `file`). The plugin loads the upstream denhaag React components and their CSS stylesheets in its block JS (e.g. `resources/blocks/zaak/components/`); the Blade partials emit placeholder elements that the JS mounts onto. New NLDS components go there too.
- **Design-token variables** — in this theme, under `resources/styles/nlds/` (`denhaag/`, `nl/`), imported from `resources/styles/frontend.css`. The theme loads only the token overrides, not the component CSS (except side-navigation — see below).

> **Side-navigation is the exception.** It renders server-side from a WordPress nav menu with ACF icons and an auth-aware logout item, so its markup is hand-rolled Blade in this theme at `resources/views/components/nlds/denhaag/side-navigation/` and only its upstream CSS is imported (here, not the plugin).

### ACF field groups

Field groups are stored as JSON in `acf-json/` and sync automatically with ACF Pro. Commit this directory to keep field definitions in version control.

## Linting & formatting

Run all linting and formatting from the theme directory (or from a Brave project root to cover every theme):

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
