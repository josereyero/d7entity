<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityManager.
 */

namespace Drupal\d7entity;

/**
 * Implement Drupal 7 hooks
 */
class EntityManager {

  /**
   * Implements hook_entity_load().
   */
  static function hook_entity_load($entities, $type) {
    // Use only second parameter as the first one will be handled
    // per each bunlde/class.
    static::hookMultiple(__FUNCTION__, $entities, $type);
  }

  /**
   * Implements hook_entity_view_alter()
   */
  static function hook_entity_view_alter(&$build, $type) {
    if (isset($build['#entity'])) {
      static::hookAlter(__FUNCTION__, $build['#entity'], $build, $type);
    }
    elseif (isset($build['#node'])) {
      static::hookAlter(__FUNCTION__, $build['#node'], $build, $type);
    }
  }

  /**
   * Pass entity object hook invokation.
   *
   * @param $function
   *   Caller function name.
   * @param $args
   *   Hook arguments
   */
  static function hookInvoke($function, $args) {
    $entity = reset($args);
    if (static::checkInstance($entity, $function)) {
      return call_user_func_array(array($entity, $function), $args);
    }
  }

  /**
   * Pass entity object alter hook invokation.
   *
   * @param $function
   *   Caller function name.
   * @param $args
   *   Hook arguments
   */
  static function hookAlter($function, $entity, &$arg1, $arg2 = NULL) {
    if (static::checkInstance($entity, $function)) {
      $entity->$function($arg1, $arg2);
    }
  }

  /**
   * Invoke hook on multiple objects.
   *
   * This one will be invoked as a static function.
   */
  static function hookMultiple($function, $entities, $arg1 = NULL, $arg2 = NULL) {
    // Group entities by classes
    $classes = array();
    foreach ($entities as $index => $entity) {
      $classes[get_class($entity)][$index] = $entity;
    }
    foreach ($classes as $class => $class_entities) {
      if (method_exists($class, $function)) {
        call_user_func(array($class, $function), $class_entities, $arg1, $arg2);
      }
    }
  }

  /**
   * Call hook previously checking whether it is callable.
   *
   * Arguments may be passed by reference.
   */
  static function callHook($callback, $args) {
    if (is_callable($callback)) {
      return call_user_func_array($callback, $args);
    }
  }

  /**
   * Check instance before invoking hook
   */
  static function checkInstance($entity, $function) {
    return $entity instanceof EntityInterface && method_exists($entity, $function);
  }

  /**
   * Handle generic hook invokation.
   *
   * @param string $name
   *   Hook name (hook_xxx).
   * @param array $arguments
   *   Array of hook arguments. The first one should be the invoked entity.
   */
  public static function __callStatic($name , array $arguments) {
    // Only for hook_*() static functioms.
    if (strpos($name, 'hook_') === 0) {
      return static::hookInvoke($name, $arguments);
    }
    else {
      throw new \Exception('EntityManager static method not found:' . $name);
    }
  }

}