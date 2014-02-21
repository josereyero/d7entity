<?php

/**
 * @file
 * Contains \Drupal\d7taxonomy\NodeBase
 */

namespace Drupal\d7taxonomy;

use Drupal\d7entity\EntityBase;
use Drupal\d7entity\User;

/**
 * Base class for nodes.
 *
 * Node types must implement at least:
 * - NodeBase::bundleName();
 *
 * Node classes may implement hooks adding methods like:
 * - hook_node_load();
 * - hook_node_prepare();
 * - hook_node_presave();
 * - hook_entity_view($view_mode, $langcode);
 */
abstract class TaxonomyTermBase extends EntityBase {

  /**
   * Overrides Entity::_construct().
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'taxonomy_term');
  }

  /**
   * Get node title.
   */
  function getTitle() {
    return $this->name;
  }

}

