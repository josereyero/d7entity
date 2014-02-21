<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityManager.
 */

namespace Drupal\d7node;

use Drupal\d7entity\EntityManager;
use Drupal\d7entity\EntityHelper;

/**
 * Implement Drupal 7 hooks
 */
class NodeManager extends EntityManager {

  /**
   * Implements hook_form_BASE_FORM_ID_alter() for node_form().
   */
  static function hook_form_node_form_alter(&$form, &$form_state, $form_id) {
    // @todo
    $node = $form_state['node'];
    if ($class = EntityHelper::getTypeClass('node', $node->type)) {
      // Use call_user_func_array() to pass by reference like this.
      static::callHook(array($class, __FUNCTION__), array(&$form, &$form_state, $form_id));
    }
  }

  /**
   * Implements hook_node_load().
   */
  static function hook_node_load($nodes, $types) {
    // @todo static invokation....
    static::hookMultiple(__FUNCTION__, $nodes, $types);
  }

  /**
   * Implements hook_node_prepare().
   */
  static function hook_node_prepare($node) {
    // This is a special hook since at this stage the node has no class yet.
    if ($class = EntityHelper::getTypeClass('node', $node->type)) {
      if (method_exists($class, __FUNCTION__)) {
        call_user_func(array($class, __FUNCTION__), $node);
      }
    }
  }

  /**
   * Implements hook_node_view_alter()
   */
  static function hook_node_view_alter(&$build) {
    static::hookAlter(__FUNCTION__, $build['#node'], $build);
  }

  /**
   * Implements hook_preprocess_HOOK
   */
  static function hook_preprocess_node(&$variables) {
    static::hookAlter(__FUNCTION__, $variables['node'], $variables);
  }

  /**
   * Check instance before invoking hook
   */
  static function checkInstance($entity, $function) {
    return $entity instanceof NodeBase && method_exists($entity, $function);
  }
}