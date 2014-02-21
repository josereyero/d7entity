<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityControllerInterface.
 */

namespace Drupal\d7entity;

/**
 * Interface for entity objects.
 */
interface EntityInterface {
   /**
    * Invoke hook on enity object.
    *
    * @param string $name
    *   Hook name, the method to invoke will be hook_$name()
    * @param array $args
    *   Array of arguments, excluding the first one that is usually the entity itself.
    */
   public function invokeHook($name, $args);

   /**
    * Get entity metadata wrapper.
    *
    * @return
    */
   public function getEntityWrapper();
}



