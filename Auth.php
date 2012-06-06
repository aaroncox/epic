<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth extends Zend_Auth
{
	protected $_profile = array();
	
	public function getProfile() {
		if($this->_profile) {
			return $this->_profile;			
		}
		$identity = $this->getIdentity();
		return $this->_profile = Epic_Mongo::db('user')->find($identity['id']);
	}
	
	/**
	 * private constructor - singleton pattern
	 *
	 * @return void
	 * @author Corey Frang
	 **/
	protected function __construct()
	{
	}

	/**
	 * Returns (or creates) the Instance - Singleton Pattern
	 *
	 * @return self
	 * @author Corey Frang
	 **/
	static public function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
} // END class Epic_Auth extends Zend_Auth