 <?php

 /**
 * @file
 * Contains \Drupal\d7entity\EntityControllerInterface.
 */

namespace Drupal\d7entity;

 /**
  * Base class for users.
  */
class EntityUser extends EntityBase {

  /**
   * Overrides Entity::_construct().
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'user');
  }
}
