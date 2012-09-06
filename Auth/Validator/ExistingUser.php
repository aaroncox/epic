<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Validator_ExistingUser extends Zend_Validate_Abstract
{
	
    const DOESNT_EXISTS = 'doesntExist';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::DOESNT_EXISTS => "The supplied login information is incorrect."
    );

	/**
	 * isValid - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function isValid($value)
	{
		$this->_setValue($value);
		$test1 = Epic_Mongo::db('user')->fetchOne(array('username' => strtolower($value)));
		$test2 = Epic_Mongo::db('user')->fetchOne(array('email' => strtolower($value)));
		if($test1 || $test2) {
			return true;
		}
		$this->_error(self::DOESNT_EXISTS);
  	return false;
	}
} // END class 