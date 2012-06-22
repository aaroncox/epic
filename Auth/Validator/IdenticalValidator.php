<?php
/**
 * Momentum Workshop
 *
 * @author Aaron Cox
 * @category MW
 * @package MW_Auth
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  MW_Auth_IdenticalValidator
 *
 * Validates that two passwords are identical
 *
 * @author Aaron Cox
 * @category MW
 * @package MW_Auth
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id:$
 */
class Epic_Auth_Validator_IdenticalValidator extends Zend_Validate_Identical {
  protected $_messageTemplates = array( 
    self::NOT_SAME => "The passwords don't match", 
    self::MISSING_TOKEN => 'No token was provided to match against', 
  );
}