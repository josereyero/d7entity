-- SUMMARY --

Drupal Module: D7 Entity

Warning: This is experimental code under heavy development. Use at your own risk.

The D7 Entity module provides basic classes to use for some types of entities
like node types. It allows per-bundle classes.

Classes use PSR-0 namespaces (Just like Drupal 8) and can implement entity/node hooks
so no need to define them in modules anymore.

Quick Example:

File: mymodule/lib/Drupal/mymodule/MyNodeType.php

  use Drupal\d7node\NodeBase;
  
  class MyNodeType extends NodeBase {
  
    /**
     * Gets clean node title to be used for class names.
     */
    public function getTitleName() {
      $name = str_replace(' ', '_', $this->getTitle());
      return strtolower($name);
    }
    
    /**
     * Implements hook_preprocess_node().
     *
     * (Yay, classes can implement hooks that will be invoked only for this node type.
     */
    static function hook_preprocess_node(&$variables) {
      // Nodes of this type will be instances of MyNodeType.
      $node = $variables['node'];
      $variables['classes_array'][] = 'node-title-' . $node->getTitleName();
    }
  }
  
File: mymodule/mymodule.module

  /**
   * Implements hook_entity_info_alter().
   *
   * By definining the bundle class here, nodes will 'magically' get that class when loaded.
   *
   * Also hooks will be invoked on that class (as static methods) for that node type.
   */
  function mymodule_entity_info_alter(&$entity_info) {
    $entity_info['node']['bundles']['mynodetype']['entity class'] = '\Drupal\mymodule\MyNodeClass';
  }


This is an API module only for developers. See also https://github.com/josereyero/d7field

Developed by http://reyero.net

