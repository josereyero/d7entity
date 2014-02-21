<?php

/**
 * @file
 * Contains \Drupal\d7node\NodeBase
 */

namespace Drupal\d7node;

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
abstract class NodeBase extends EntityBase {

  /**
   * Overrides Entity::_construct().
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'node');
  }

  /**
   * Get node title.
   */
  function getTitle() {
    return $this->title;
  }

  /**
   * Get author
   */
  function getAuthor() {
    //return new EntityUser(user_load($this->uid));
    return user_load($this->uid);
  }

}

