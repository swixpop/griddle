<?php
/**
 * Griddle plugin for Craft CMS
 *
 * Add grid overlays during development
 *
 *
 * @author    Isaac Gray
 * @copyright Copyright (c) 2016 Isaac Gray
 * @link      http://isaacgray.me
 * @package   Griddle
 * @since     1.0.0
 */

namespace Craft;

class GriddlePlugin extends BasePlugin
{


    /**
     * @return mixed
     */
    public function getName()
    {
         return Craft::t('Griddle');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Add grid overlays during development');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/swixpop/griddle/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/swixpop/griddle/master/releases.json';
    }

    /**
     * Returns the version number.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.1';
    }

    /**
     * As of Craft 2.5, Craft no longer takes the whole site down every time a plugin’s version number changes, in
     * case there are any new migrations that need to be run. Instead plugins must explicitly tell Craft that they
     * have new migrations by returning a new (higher) schema version number with a getSchemaVersion() method on
     * their primary plugin class:
     *
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Isaac Gray';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://isaacgray.me';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    protected function defineSettings()
    {
        return array(
            'columns' => array(AttributeType::Number, 'default' => 12),
            'color' => array(AttributeType::String, 'default' => '#ff0000'),
            'opacity' => array(AttributeType::Number, 'default' => 10),
            'gutter' => array(AttributeType::Number, 'default' => 16),
            'containerPadding' => array(AttributeType::Number, 'default' => 20),
            'maxWidth' => array(AttributeType::Number, 'default' => 1800),
            'breakpoints' => array(AttributeType::Mixed, 'default' => null),
        );
    }

    public function getSettingsHtml()
    {
        $config_settings = array();
        $config_settings['columns'] = craft()->config->get('columns', 'griddle');
        $config_settings['gutter'] = craft()->config->get('gutter', 'griddle');
        $config_settings['color'] = craft()->config->get('color', 'griddle');
        $config_settings['opacity'] = craft()->config->get('opacity', 'griddle');
        $config_settings['containerPadding'] = craft()->config->get('containerPadding', 'griddle');
        $config_settings['maxWidth'] = craft()->config->get('maxWidth', 'griddle');

        return craft()->templates->render('griddle/settings', array(
          'settings' => $this->getSettings(),
          'config_settings' => $config_settings
        ));
    }

    public function prepSettings($settings)
    {
        $hadEmptyRow = false;
        foreach ($settings as $key => $value) {
          if ($key == 'breakpoints') {
            foreach ($value as $bpkeys => $bpvalues) {
              $isEmpty = true;
              foreach ($bpvalues as $bpkey => $bpvalue) {
                $settings[$key][$bpkeys][$bpkey] = (int)$bpvalue == 0 ? null : (int)$bpvalue;
                if ($settings[$key][$bpkeys][$bpkey] !== null && $bpkey !== 0) {
                  $isEmpty = false;
                }

                if ($settings[$key][$bpkeys][0] == null) {
                  $isEmpty = true;
                }
              }

              if ($isEmpty) {
                unset($settings[$key][$bpkeys]);
                $hadEmptyRow = true;
              }
            }
          } else {
            if ($key !== 'color') {
              $settings[$key] = (int)$settings[$key];
            }

            if ($key == 'color' && $value == '') {
              $settings[$key] = 'red';
            }

            if ($key == 'maxWidth' && (int)$value == 0) {
              $settings[$key] = null;
            }
          }
        }

        if ($hadEmptyRow) {
          $settings['breakpoints'] = array_values($settings['breakpoints']);
        }

        if (!isset($settings['breakpoints']) || empty($settings['breakpoints'])) {
          $settings['breakpoints'] = null;
        }

        return $settings;
    }

}
