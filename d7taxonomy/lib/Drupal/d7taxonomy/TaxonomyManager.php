<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityManager.
 */

namespace Drupal\d7taxonomy;

use Drupal\d7entity\EntityManager;

/**
 * Implement Drupal 7 hooks
 */
class TaxonomyManager extends EntityManager {

  /**
   * Implements hook_taxonomy_term_load().
   */
  static function hook_taxonomy_term_load($taxonomy_terms) {
    // @todo static invokation....
    static::hookMultiple(__FUNCTION__, $taxonomy_terms);
  }

  /**
   * Implements hook_taxonomy_term_view_alter()
   */
  static function hook_taxonomy_term_view_alter(&$build) {
    static::hookAlter(__FUNCTION__, $build['#term'], $build);
  }

  /**
   * Implements hook_taxonomy_term_presave().
   */
  static function hook_taxonomy_term_presave($taxonomy_term) {
    static::hookInvoke(__FUNCTION__, func_get_args());
  }

  /**
   * Implements hook_preprocess_HOOK
   */
  static function hook_preprocess_taxonomy_term(&$variables) {
    static::hookAlter(__FUNCTION__, $variables['term'], $variables);
  }

  /**
   * Check instance before invoking hook
   */
  static function checkInstance($entity, $function) {
    return $entity instanceof TaxonomyTermBase && method_exists($entity, $function);
  }
}