<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  Epic_Mongo_Schema
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Schema.php 547 2011-04-10 08:52:31Z corey $
 */
class Epic_Mongo_Schema extends Epic_Mongo_Document {
	protected static $_collectionName = 'schema';
	protected $_fromVersion = 0;
	protected $_version = 0;
	protected $_tag = 'epic';
	protected $_classMap = array(
		'user' => 'Epic_Mongo_Document_User',
		'post' => 'Epic_Mongo_Document_Post', 
		'record' => 'Epic_Mongo_Document_Record',
	);
	protected $_parent = null;

	/**
	* Class Instance - Singleton Pattern
	*
	* @var self
	**/
	static protected $_instance = NULL;

	/**
	* Returns (or creates) the Instance - Singleton Pattern
	*
	* @return self
	* @author Corey Frang
	**/
	static public function getInstance()
	{
	 if (static::$_instance === NULL) {
	   static::$_instance = new static();
	 }
	 return static::$_instance;
	}

	public function check($parent = null)
	{
		if($parent) {
			$this->_parent = $parent;
		}
		$lib = $this->fetchOne(array('tag'=>$this->_tag));
		if (!$lib) {
			$lib = new static();
			$lib->tag = $this->_tag;
			$lib->version = $this->_fromVersion;
		}
		$this->_fromVersion = $lib->version;

		if ($this->_fromVersion != $this->_version) {
			$time = ini_get('max_execution_time');
			set_time_limit(0);
			$this->updateFrom($this->_fromVersion);
			set_time_limit($time);
		}
		$lib->version = $this->_version;
		$lib->save();
	}

	protected $_collections = array();

	/**
	 * gets a table (or creates it)
	 *
	 * @return Zend_Db_Table_Abstract;
	 * @author Corey Frang
	 **/
	public function getCollectionForType($type)
	{
		if ($this->_parent) {
			return $this->_parent->getCollectionForType($type);
		}
		if (!static::$_instance) throw new Exception('Not Configured');
		if (isset($this->_collections[$type])) return $this->_collections[$type];

		return $this->_collections[$type] = $this->_createTable($type);
	}

	public function getClassForType($type)
	{
		if ($this->_parent) {
			return $this->_parent->getClassForType($type);
		}
		if (isset($this->_classMap[$type])) {
			return $this->_classMap[$type];
		}		
		// Lets load the Default Schema's classMap
    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
    $options = $bootstrap->getOptions();
		$schemaClass = $options['resources']['mongo']['default']['schema'];
		$schema = new $schemaClass;
		// Now add the classMap to ours
		foreach($schema->_classMap as $map) {
			$this->_classMap += $map;
		}
		// Query again for the type
		if (isset($this->_classMap[$type])) {
			return $this->_classMap[$type];
		}		
		throw new Epic_Mongo_Exception("Unknown Table ".$type);
	}
	
	public function getClassesForType($type)
	{
		// Lets load the Default Schema's classMap
    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		if($bootstrap) {
	    $options = $bootstrap->getOptions();
			$schemaClass = $options['resources']['mongo']['default']['schema'];
			$schema = new $schemaClass;
			// Now add the classMap to ours
			if($schema->_classMap[$type]) {
				return array($type => $this->_classMap[$type]) + $schema->_classMap[$type];
			}			
		}
		return array($type => $this->_classMap[$type]);
		throw new Epic_Mongo_Exception("Unknown Parent Datatype: ".$type);
	}

	/**
	 * Resolves a mongo ref
	 *
	 * @return Document
	 * @author Corey Frang
	 **/
	public function resolveReference($data)
	{
		if (empty($data['$ref']) || empty($data['$id'])) return null;
		if (is_string($data['$id'])) $data['$id'] = new MongoId($data['$id']);
		$config = array('collection'=>$data['$ref']);
		$data = MongoDBRef::get(static::getMongoDB(false), $data);
		if ($data['_type']) {
			$className = $this->getClassForType($data['_type']);
			return new $className($data, $config);
		} else {
			return new Shanty_Mongo_Document($data, $config);
		}
	}

	/**
	 * returns a table created from the mapping
	 *
	 * @return Zend_Db_Table_Abstract
	 * @author Corey Frang
	 **/
	protected function _createTable($type)
	{
		$tableClass = $this->getClassForType($type);
		Zend_Loader::loadClass($tableClass);
		return $this->_newTableFromClass($tableClass);
	}

	protected function _newTableFromClass($tableClass)
	{
		return new $tableClass();
	}
}