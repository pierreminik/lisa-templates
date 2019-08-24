# Lisa Templates

Easily write templates filled with custom data that loads across your site.

## Description

Allow users to write Twig-templates that can easily be filled with custom meta data.

Manage your custom meta data with a plugin like **ACF Pro** and render it with **Lisa Templates**.

Tested and working with the following plugins.

- [ACF and ACF Pro](https://www.advancedcustomfields.com)
- [Tailor](https://www.tailorwp.com)
- [Polylang](https://wordpress.org/plugins/polylang/)

Example of custom query to load data:

`{ "post_type": [ "post" ], "post_status": [ "publish" ], "posts_per_page": 10, "order": "DESC", "orderby": "date" }`

### Dependencies:

- [Timber](https://wordpress.org/plugins/timber-library/)

## Where can I learn more about Twig-templates?

Lisa Templates is running Twig through a plugin called Timber. Learn more about Timber at their [excellent documentation site](https://timber.github.io/docs/).

## Installation

1. Install the [GitHub Updater](https://github.com/afragen/github-updater)-plugin.
2. In `Settings` > `GitHub Updater` select the `Install Plugin` tab.
3. Copy and past the GitHub repository uri (which is[http://github.com/pierreminik/lisa-templates](http://github.com/pierreminik/lisa-templates)).
4. Install and activate.
5. Enjoy!

## Changelog

**1.5.0**

- Enabled shortcodes within templates.

**1.4.4**

- Bumping version to check if GitHub Updater can update the plugin.

**1.4.3**

- Open sourced the premium version and put it on Github.

**1.2.0**

- It's now possible to load the template with the woocommerce_short_description-filter.

**1.1.2**

- Fixed autoupdate feature.

**1.1.1**

- Testing autoupdate feature.

**1.1.0**

- Added widget feature.

**1.0.1**

- Bug fixes.

**1.0.0**

- Initial release.

## Plugin

Contributors: [pierreminik](https://github.com/pierreminik)
Tags: template, twig, timber, tailor, acf, acf pro, polylang
Requires at least: 4.8
Tested up to: 5.1
Stable tag: 1.4.3
License: GPLv2 or later
License URI: [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)
