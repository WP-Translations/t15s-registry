# T15S Registry

Allows loading translation files from TranslationsPress.

## Installation

If you're using [Composer](https://getcomposer.org/) to manage dependencies, you can use the following command to add the plugin to your site:

```bash
composer require wp-transaltions/t15s-registry
```

After that, you can use `\WP_Translations\T15S_Registry\add_project( $type, $slug, $api_url )` in your theme or plugin.

*Parameters:*

* `$type`: either `plugin` or `theme`.
* `$slug`: must match the theme/plugin directory slug.
* `$api_url`: TanslationsPress translation API URL.

*Note:* On a multisite install it's recommended to use it in a must-use plugin.

## Usage

Here's an example of how you can use that function:

```php
\WP_Translations\T15S_Registry\add_project(
	'plugin',
	'example-plugin',
	'https://translationspress.com/translate/api/translations/acme/acme-plugin/'
);

\WP_Translations\T15S_Registry\add_project(
	'theme',
	'example-theme',
	'https://translationspress.com/translate/api/translations/acme/acme-theme/'
);
```
