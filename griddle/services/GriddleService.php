<?php
/**
 * Griddle plugin for Craft CMS
 *
 * Griddle Service
 *
 *
 * @author    Isaac Gray
 * @copyright Copyright (c) 2016 Isaac Gray
 * @link      http://isaacgray.me
 * @package   Griddle
 * @since     1.0.0
 */

namespace Craft;

class GriddleService extends BaseApplicationComponent
{
    /**
     * This function can literally be anything you want, and you can have as many service functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     craft()->griddle->exampleService()
     */
    var $settings = null;
    var $styles = '<style>';
    var $script = '<script>';
    var $globalGutter = 0;
    var $globalColumns = 12;
    var $output = '';
    var $maxPadding = null;
    var $maxGutter = null;

    public function fry()
    {
        $this->styles .= '.griddle-grid{top:0;position:fixed;z-index:9999999999;pointer-events:none;height:100%;}';
        $this->styles .= '.griddle-grid-hidden{display:none;}';
        $this->styles .= '.griddle-inner{position:relative;pointer-events:none;height:100%;width:100%;' . (($this->getSetting('maxWidth') !== null) ? ('max-width:' . ($this->getSetting('maxWidth') + ($this->getSetting('gutter'))) . 'px;'):'') .  'margin:0 auto;}';
        $this->styles .= '.griddle-column{float:left;opacity:'.($this->getSetting('opacity')/100).';height:100vh;}';


        $this->colStyle(null);
        $this->gridStyle(null);

        if ($this->getSetting('breakpoints') !== null) {
          foreach ($this->getSetting('breakpoints') as $breakpoint) {
              $this->generateStyle($breakpoint);
          }
        }

        $this->styles .= '</style>';

        $this->script .= "
            var ctrlDown = false;
            window.onkeydown = function(e) {
               var key = e.keyCode ? e.keyCode : e.which;
               if (key == 18) ctrlDown = true;
               if (ctrlDown && key == 71) {
                   var grid = document.querySelector('.griddle-grid');
                   grid.classList.toggle('griddle-grid-hidden');
               }
            };
            window.onkeyup = function(e) {
               var key = e.keyCode ? e.keyCode : e.which;
               if (key == 18) ctrlDown = false;
            };
        ";
        $this->script .= '</script>';

        $this->output .= $this->styles;
        $this->output .= $this->script;

        $this->output .= '<div class="griddle-grid'.(craft()->config->get('defaultOn', 'griddle') ? '' : ' griddle-grid-hidden').'"><div class="griddle-inner">';

        for ($i = 0; $i < $this->getSetting('columns'); $i++) {
            $this->output .= '<div class="griddle-column"><div style="background:'.$this->getSetting('color').';height:100vh;"></div></div>';
        }

        $this->output .= '</div></div>';

        return $this->output;

    }

    private function generateStyle($breakpoint)
    {

        $this->styles .= '@media(min-width:' . $breakpoint[0] . 'px){';
        $this->colStyle(array('gutter' => $breakpoint[1], 'containerPadding' => $breakpoint[2], 'columns' => $breakpoint[3]));
        $this->gridStyle(array('gutter' => $breakpoint[1], 'containerPadding' => $breakpoint[2], 'columns' => $breakpoint[3]));
        $this->styles .= '}';
    }

    private function colStyle($setting)
    {
        $currentColumns = $setting['columns'] ?? $this->getSetting('columns');
        $currentGutter = $setting['gutter'] ?? $this->getSetting('gutter');

        $this->styles .= '.griddle-column{';
        $this->styles .= 'width:calc(100% / ' . $currentColumns . ');';
        $this->styles .= 'padding:0 calc(' . $currentGutter . 'px / 2);';
        $this->styles .= '}';
    }

    private function gridStyle($setting)
    {
        $currentPadding = isset($setting['containerPadding']) ? $setting['containerPadding'] : $this->getSetting('containerPadding');

        $currentGutter = isset($setting['gutter']) ? $setting['gutter'] : $this->getSetting('gutter');

        $this->styles .= '.griddle-grid{left:calc(' . $currentPadding . 'px - (' . $currentGutter . 'px / 2));right:calc(' . $currentPadding . 'px - (' . $currentGutter . 'px / 2));}';

        $this->styles .= '.griddle-inner{max-width:' . ($this->getSetting('maxWidth') + ($currentGutter)) . 'px;}';
    }

    public function getSetting($name)
    {
        if ($this->settings == null) {
            $this->settings = $this->_init_settings();
        }

        return $this->settings[$name];
    }

    private function _init_settings()
    {
        $plugin = craft()->plugins->getPlugin('griddle');
        $plugin_settings = $plugin->getSettings();

        $settings = array();
        $settings['columns'] = craft()->config->get('columns', 'griddle') !== null ? craft()->config->get('columns', 'griddle') : $plugin_settings['columns'];

        $settings['gutter'] = craft()->config->get('gutter', 'griddle') !== null ? craft()->config->get('gutter', 'griddle') : $plugin_settings['gutter'];

        $settings['color'] = craft()->config->get('color', 'griddle') !== null ? craft()->config->get('color', 'griddle') : $plugin_settings['color'];

        $settings['opacity'] = craft()->config->get('opacity', 'griddle') !== null ? craft()->config->get('opacity', 'griddle') : $plugin_settings['opacity'];

        $settings['maxWidth'] = craft()->config->get('maxWidth', 'griddle') !== null ? craft()->config->get('maxWidth', 'griddle') : $plugin_settings['maxWidth'];

        $settings['containerPadding'] = craft()->config->get('containerPadding', 'griddle') !== null ? craft()->config->get('containerPadding', 'griddle') : $plugin_settings['containerPadding'];

        $settings['breakpoints'] = craft()->config->get('breakpoints', 'griddle') !== null ? craft()->config->get('breakpoints', 'griddle') : $plugin_settings['breakpoints'];

        return $settings;
    }

}
