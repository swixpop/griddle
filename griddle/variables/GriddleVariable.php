<?php
/**
 * Griddle plugin for Craft CMS
 *
 * Griddle Variable
 *
 *
 * @author    Isaac Gray
 * @copyright Copyright (c) 2016 Isaac Gray
 * @link      http://isaacgray.me
 * @package   Griddle
 * @since     1.0.0
 */

namespace Craft;

class GriddleVariable
{

  public function cake()
  {
      $grid = craft()->griddle->fry();
      return $grid;
  }
}
