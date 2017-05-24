# Griddle plugin for Craft CMS

Add grid overlays during development

## Installation

To install Griddle, follow these steps:

1. Download & unzip the file and place the `griddle` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/swixpop/griddle.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3. Install plugin in the Craft Control Panel under Settings > Plugins
4. Configure plugin settings in the control panel.

## Griddle Overview

Griddle allows you to add a grid overlay to directly to your templates using a template variable. This is great during development to check that things match the design spec. You can configure the grid settings at different breakpoints.

## Using Griddle

Every time you get hungry for a grid, you can bake a griddle cake by simply adding the griddle template variable to your template.

```
{{ craft.griddle.cake() | raw }}
```

If you want to only show it in your development environment you can add a griddle.php file to your Craft config folder and set the `showGrid` variable for your different environments.

```
{% if craft.config.get('showGrid', 'griddle') %}
  {{ craft.griddle.cake() | raw }}
{% endif %}
```

To toggle the grid on and off while viewing a page you can use `alt + g`.

**IMPORTANT: You should make sure to add some sort of environment config check so that Griddle doesn't show on your production sites**

### Grid Settings

You can configure your grid settings on the plugin settings page. Detailed documentation to follow.

By default, the grid shows on page load. Sometimes this can get annoying. You can add `defaultOn => false` to your griddle.php config file and then use `alt + g` to toggle it on when you need to check grid alignment.

## Griddle Roadmap

Todos:

* Complete documentation.

* Add show and default state settings to CP.

## Griddle Changelog

See [releases.json](https://raw.githubusercontent.com/swixpop/griddle/master/releases.json)

Brought to you by [Isaac Gray](http://isaacgray.me) and [Værsågod](https://www.vaersaagod.no/)
