<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityHelper.
 */

namespace Drupal\d7entity;

/**
 * Helper class for entity objects.
 */
class EntityHelper {

 /**
   * Convert object to the right class.
   *
   * @param $object
   *
   */
  public static function checkObject($entityType, $entity, $entityInfo = NULL) {
    if ($entity instanceof EntityInterface) {
      return $entity;
    }
    elseif ($class = static::getClassName($entityType, $entity, $entityInfo)) {
      return new $class((array)$entity, $entityType);
    }
    else {
      // No special class, just return regular value.
      return $entity;
    }
  }

   /**
   * Convert objects to the right class.
   *
   * @param $entityType
   *
   */
  public static function convertObjects($entityType, &$entities, $entityInfo = NULL) {
    foreach ($entities as $id => $entity) {
      $entities[$id] = static::checkObject($entityType, $entity, $entityInfo);
    }
  }


  /**
   * Get class name for this entity object.
   */
  public static function getClassName($entityType, $entity, $entityInfo = NULL) {
    if ($bundle_name = static::getBundleName($entityType, $entity, $entityInfo)) {
      return static::getTypeClass($entityType, $bundle_name, $entityInfo);
    }
    else {
      return NULL;
    }
  }

  /**
   * Get bundle name from object.
   *
   * @param object $entity
   */
  public static function getBundleName($entityType, $entity, $entityInfo = NULL) {
    $entityInfo = $entityInfo ?: entity_get_info($entityType);
    if (!empty($entityInfo['entity keys']['bundle'])) {
      $field = $entityInfo['entity keys']['bundle'];
      return isset($entity->$field)  ? $entity->$field : NULL;
    }
    else {
      return $entityType;
    }
  }

  /**
   * Get class name for bundle of this entity type.
   */
  public static function getTypeClass($entityType, $bundle_name, $entityInfo = NULL) {
    $entityInfo = $entityInfo ?: entity_get_info($entityType);
    if ($bundle_name && isset($entityInfo['bundles']) && isset($entityInfo['bundles'][$bundle_name]['entity class'])) {
      return $entityInfo['bundles'][$bundle_name]['entity class'];
    }
    elseif (isset($entityInfo['entity class'])) {
      return $entityInfo['entity class'];
    }
  }

  /**
   * Helper function for loading controller.
   */
  public static function controllerLoad(EntityControllerInterface $controller, $entities) {
    foreach ($entities as $index => $object) {
      $entities[$index] = static::checkObject($controller->entityType(), $object, $controller->entityInfo());
    }
    $controller->cacheReplace($entities);
    return $entities;
  }
}
