# Embed Iframe

Displays an iframe in CP with configurable label and embed

## Requirements

This plugin requires Craft CMS 5.8.0 or later, and PHP 8.2 or later.
---

## Requirements

- Craft CMS 5.8.0 or later.
- PHP 8.2 or later.

---

## Installation

Open `composer.json` in your CraftCMS project and add:

```json
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/diegocosta-dev/embed-iframe.git"
    }
]
```

Run the following commands in your terminal:

```bash
# Go to your project directory
cd /path/to/my-project

# Add the plugin via Composer
ddev composer require diego-costa/craft-embed-iframe:dev-main

# Install the plugin in Craft CMS
ddev craft plugin/install _embed-iframe
```

