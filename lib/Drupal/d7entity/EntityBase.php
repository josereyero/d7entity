<?php

/**
 * @file
 * Contains \Drupal\d7entity\EntityControllerInterface.
 */

namespace Drupal\d7entity;

/**
 * Base class for all entities.
 *
 * This must extend stdClass as some modules expect this class for hooks.
 *
 * @see xmlsitemap_node.module
 *
 * Entities may implement hooks adding methods with the names:
 *
 * - hook_entity_load($type);
 * - hook_entity_presave($type);
 * - hook_entity_update($type);
 * - hook_entity_view($type, $view_mode, $langcode);
 */
abstract class EntityBase extends \stdClass implements EntityInterface {

  /**
   * Entity metadata wrapper if created will be statically cached.
   */
  protected $entityMetadataWrapper;
  /**
   * Properties from Entity
   * @var unknown_type
   */
  protected $entityType;
  protected $entityInfo;
  protected $idKey, $nameKey, $statusKey;
  protected $defaultLabel = FALSE;

  /**
   * Creates a new entity.
   *
   * @see entity_create()
   */
  public function __construct(array $values = array(), $entityType = NULL) {
    if (empty($entityType)) {
      throw new Exception('Cannot create an instance of Entity without a specified entity type.');
    }
    $this->entityType = $entityType;
    $this->setUp();
    // Set initial values.
    foreach ($values as $key => $value) {
      $this->$key = $value;
    }
  }

  /**
   * Set up the object instance on construction or unserializiation.
   */
  protected function setUp() {
    $this->entityInfo = entity_get_info($this->entityType);
    $this->idKey = $this->entityInfo['entity keys']['id'];
    $this->nameKey = isset($this->entityInfo['entity keys']['name']) ? $this->entityInfo['entity keys']['name'] : $this->idKey;
    $this->statusKey = empty($info['entity keys']['status']) ? 'status' : $info['entity keys']['status'];
  }

  /**
   * Returns the internal, numeric identifier.
   *
   * Returns the numeric identifier, even if the entity type has specified a
   * name key. In the latter case, the numeric identifier is supposed to be used
   * when dealing generically with entities or internally to refer to an entity,
   * i.e. in a relational database. If unsure, use Entity:identifier().
   */
  public function internalIdentifier() {
    return isset($this->{$this->idKey}) ? $this->{$this->idKey} : NULL;
  }

  /**
   * Returns the entity identifier, i.e. the entities name or numeric id.
   *
   * @return
   *   The identifier of the entity. If the entity type makes use of a name key,
   *   the name is returned, else the numeric id.
   *
   * @see entity_id()
   */
  public function identifier() {
    return isset($this->{$this->nameKey}) ? $this->{$this->nameKey} : NULL;
  }

  /**
   * Returns the info of the type of the entity.
   *
   * @see entity_get_info()
   */
  public function entityInfo() {
    return $this->entityInfo;
  }

  /**
   * Returns the type of the entity.
   */
  public function entityType() {
    return $this->entityType;
  }

  /**
   * Returns the bundle of the entity.
   *
   * @return
   *   The bundle of the entity. Defaults to the entity type if the entity type
   *   does not make use of different bundles.
   */
  public function bundle() {
    return !empty($this->entityInfo['entity keys']['bundle']) ? $this->{$this->entityInfo['entity keys']['bundle']} : $this->entityType;
  }

  /**
   * Returns the label of the entity.
   *
   * Modules may alter the label by specifying another 'label callback' using
   * hook_entity_info_alter().
   *
   * @see entity_label()
   */
  public function label() {
    // If the default label flag is enabled, this is being invoked recursively.
    // In this case we need to use our default label callback directly. This may
    // happen if a module provides a label callback implementation different
    // from ours, but then invokes Entity::label() or entity_class_label() from
    // there.
    if ($this->defaultLabel || (isset($this->entityInfo['label callback']) && $this->entityInfo['label callback'] == 'entity_class_label')) {
      return $this->defaultLabel();
    }
    $this->defaultLabel = TRUE;
    $label = entity_label($this->entityType, $this);
    $this->defaultLabel = FALSE;
    return $label;
  }

  /**
   * Defines the entity label if the 'entity_class_label' callback is used.
   *
   * Specify 'entity_class_label' as 'label callback' in hook_entity_info() to
   * let the entity label point to this method. Override this in order to
   * implement a custom default label.
   */
  protected function defaultLabel() {
    // Add in the translated specified label property.
    return $this->getTranslation($this->entityInfo['entity keys']['label']);
  }

  /**
   * Returns the uri of the entity just as entity_uri().
   *
   * Modules may alter the uri by specifying another 'uri callback' using
   * hook_entity_info_alter().
   *
   * @see entity_uri()
   */
  public function uri() {
    if (isset($this->entityInfo['uri callback']) && $this->entityInfo['uri callback'] == 'entity_class_uri') {
      return $this->defaultUri();
    }
    return entity_uri($this->entityType, $this);
  }

  /**
   * Override this in order to implement a custom default URI and specify
   * 'entity_class_uri' as 'uri callback' hook_entity_info().
   */
  protected function defaultUri() {
    return array('path' => 'default/' . $this->identifier());
  }

  /**
   * Checks if the entity has a certain exportable status.
   *
   * @param $status
   *   A status constant, i.e. one of ENTITY_CUSTOM, ENTITY_IN_CODE,
   *   ENTITY_OVERRIDDEN or ENTITY_FIXED.
   *
   * @return
   *   For exportable entities TRUE if the entity has the status, else FALSE.
   *   In case the entity is not exportable, NULL is returned.
   *
   * @see entity_has_status()
   */
  public function hasStatus($status) {
    if (!empty($this->entityInfo['exportable'])) {
      return isset($this->{$this->statusKey}) && ($this->{$this->statusKey} & $status) == $status;
    }
  }

  /**
   * Get field items.
   *
   * Wrapper for field_get_items()
   */
  protected function fieldGetItems($field_name, $langcode = NULL) {
    return field_get_items($this->entityType, $this, $field_name, $langcode);
  }

  /**
   * Get first field value
   *
   * @return value|FALSE
   */
  protected function fieldGetValue($field_name, $value_key = NULL, $langcode = NULL) {
    if ($items = $this->fieldGetItems($field_name, $langcode)) {
      $first = reset($items);
      return isset($value_key) ? $first[$value_key] : $first;
    }
    return FALSE;
  }

   /**
    * Get Entity wrapper.
    */
   public function getEntityWrapper() {
     if (!isset($this->entityMetadataWrapper)) {
       $this->entityMetadataWrapper = entity_metadata_wrapper($this->entityType, $this);
     }
     return $this->entityMetadataWrapper;
   }

   /**
    * Hook invokation.
    */
   public function invokeHook($name, $args) {
     $method = 'hook_' . $name;
     if (method_exists($this, $method)) {
       call_user_func_array(array($this, $method), $args);
     }
   }

   public function getRdfMapping() {
     return rdf_mapping_load($this->entityType(), $this->bundle());
   }
 }
