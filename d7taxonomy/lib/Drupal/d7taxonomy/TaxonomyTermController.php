<?php

/**
 * @file
 * Contains \Drupal\d7taxonomy\NodeController.
 */

namespace Drupal\d7taxonomy;

use Drupal\d7entity\EntityControllerInterface;
use Drupal\d7entity\EntityHelper;

/**
 * Entity controller for nodes.
 */
class TaxonomyTermController extends \TaxonomyTermController {

  protected function attachLoad(&$entities, $revision_id = FALSE) {
    EntityHelper::convertObjects('taxonomy_term', $entities, $this->entityInfo);
    return parent::attachLoad($entities, $revision_id);
  }
}