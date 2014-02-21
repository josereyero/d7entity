<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityControllerInterface.
 */

namespace Drupal\d7entity;

/**
 * Interface for new entity controllers.
 *
 * These are meant to replace entity controllers and return fully typed objects.
 */
interface EntityControllerInterface {
  /**
   * Entity type for this controller instance.
   */
  public function entityType();

  /**
   * Array of information about the entity.
   */
  public function entityInfo();

  /**
   * Replace objects in cache.
   */
  public function cacheReplace($entities);
}