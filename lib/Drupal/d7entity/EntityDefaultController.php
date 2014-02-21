<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityControllerInterface.
 */

namespace Drupal\d7entity;

/**
 * Ddefault Entity Controller for these objects.
 */
class EntityDefaultController extends \DrupalDefaultEntityController implements EntityControllerInterface {

  /**
   * Overrides DrupalEntityControllerInterface::load().
   */
  public function load($ids = array(), $conditions = array()) {
    $entities = parent::load($ids, $conditions);
    return EntityHelper::controllerLoad($this, $entities);
  }

  /**
   * Implements EntityControllerInterface::entityType()
   */
  public function entityType() {
    return $this->entityType;
  }

  /**
   * Implements EntityControllerInterface::entityInfo()
   */
  public function entityInfo() {
    return $this->entityInfo;
  }

  /**
   * Implements EntityControllerInterface::cacheReplace()
   */
  public function cacheReplace($entities) {
    $this->resetCache(array_keys($entities));
    $this->cacheSet($entities);
  }
}
