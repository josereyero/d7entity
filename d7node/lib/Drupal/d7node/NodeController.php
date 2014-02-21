<?php

/**
 * @file
 * Contains \Drupal\d7node\NodeController.
 */

namespace Drupal\d7node;

use Drupal\d7entity\EntityControllerInterface;
use Drupal\d7entity\EntityHelper;

/**
 * Entity controller for nodes.
 */
class NodeController extends \NodeController {

  protected function attachLoad(&$nodes, $revision_id = FALSE) {
    EntityHelper::convertObjects('node', $nodes, $this->entityInfo);
    return parent::attachLoad($nodes, $revision_id);
  }
}