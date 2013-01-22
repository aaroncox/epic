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
 *  Epic_Mongo_Document
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Document.php 547 2011-04-10 08:52:31Z corey $
 */
class Epic_Mongo_Document extends Shanty_Mongo_Document {

	protected static $_documentType = null;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Corey Frang
	 **/
	static public function getDbName()
	{
		$name = static::$_dbName;
		if ($name) return $name;

		$mongo = Zend_Registry::getInstance()->mongo;

		if ($name = static::$_connectionGroup ) {
			if ( $name = $mongo->getDbName( $name ) ) {
				return static::$_dbName = $name;
			}
		}

		if ( $name = $mongo->getDbName( "default" ) ) {
			return static::$_dbName = $name;
		}
	}

	public function init() {

	}

	public function __construct($data = array(), $config = array())
	{
		$this->init();
		return parent::__construct($data, $config);
	}

	public function __get($property)
	{
		$value = parent::getProperty($property);
		if ($value instanceOf MongoDate)
		{
			$value = new Zend_Date($value->sec.'.'.$value->usec);
		}
		return $value;
	}

	public function setProperty($property,$value)
	{
		if ($value instanceOf Zend_Date) {
			$value = new MongoDate((int)$value->getTimestamp());
		}
		return parent::setProperty($property, $value);
	}

	public function setFromArray($array)
	{
		foreach ($array as $k=>$v) {
			if($this->hasRequirement($k, 'DocumentSet') && is_array($v)) {
				$this->$k->setFromArray($v);
			} else {
				$this->setProperty($k, $v);				
			}
		}
		return $this;
	}

	public function toArray()
	{
		$return = array();
		foreach ($this as $k=>$v) {
			$return[$k] = $v;
		}
		return $return;
	}
	
	public function postSave() {}
	public function preSave() {}

	public function save($entierDocument = false) {
		if(isset(static::$_documentType) || isset(static::$_baseType)) {
			$this->_type = static::$_documentType ?: static::$_baseType;
			if(!$this->id) {
				$this->id = Epic_Mongo_Sequence::getNextSequence($this->_type);
			}
		}
		$this->preSave();
		$return = parent::save();
		$this->postSave();
		return $return;
	}

	public static function remove(array $criteria, $justone = false) {
		if(static::$_documentType) {
			$criteria["_type"] = static::$_documentType;
		}
		parent::remove($criteria, $justone);
	}

	public static function fetchOne($query = array()) {
		if ($query instanceOf Epic_Mongo_Query) {
			$query = $query->query;
		}
		if(static::$_documentType) {
			$query["_type"] = static::$_documentType;				
		}
		return parent::fetchOne($query);
	}

	/**
	 * Find many documents
	 *
	 * @param array $query
	 */
	public static function fetchAll($query = array(), $sort = array(), $limit = null, $skip = null)
	{

		if ($query instanceOf Epic_Mongo_Query) {
			if (!empty($query->sort)) {
				$sort = $query->sort;
			}
			if (!empty($query->limit)) {
				$limit = $query->limit;
			}

			$query = $query->query;
		}
		// If we have a documentType, lets load our type and all children.
		if(static::$_documentType) {
			// Lets load the Default Schema's classMap
			// 	    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
			// 	    $options = $bootstrap->getOptions();
			// $schemaClass = $options['resources']['mongo']['default']['schema'];
			// $schema = new $schemaClass;
			// // Now add the classMap to ours
			// if(isset($schema->_classMap[static::$_documentType])) {
			// 	$subTypes = array_keys($schema->_classMap[static::$_documentType]);
			// 	$subTypes[] = static::$_documentType;
			// 	$query['_type'] = array(
			// 		'$in' => $subTypes
			// 	);
			// } else {
				$query["_type"] = static::$_documentType;				
			// }
		}
		// if($user = D3Up_Auth::getInstance()->getProfile()) {
		// 	if($user->id == 2) {
		// 		var_dump(static::$_collectionName, json_encode($query), json_encode($sort));
		// 	}
		// }
		return parent::fetchAll($query, $sort, $limit, $skip);
	}

	public static function getMongoCollection($writable = true) {
		return parent::getMongoCollection($writable = true);
	}

	/**
	 * Method Name / Arguments
	 *
	 * @return void
	 * @author Corey Frang
	 **/
	public static function __callStatic($methodName, $methodArgs)
	{
		$class = get_called_class();
		if (preg_match("/^fetch([A-Z].*)$/", $methodName, $matches))
		{
			$query = "query"+$matches[1];
			if (method_exists($class, $query)) {
				return $class::fetchAll($class::$query());
			}
		}
		throw new Exception('Unknown Method '.$class.'::'.$methodName);
	}

	/**
	 * Add a requirements to a properties
	 *
	 * @param array $requirements
	 *    "property" => "requirements"
	 */
	public function addRequirements($requirements)
	{
		foreach ($requirements as $key => $reqs) {
			if(!is_array($reqs)) continue;
			foreach ($reqs as $key2 => $req) {
				$options = null;
				if (!is_int($key2)) {
					$options = $req;
					$req = $key2;
				}
				$this->addRequirement($key, $req, $options);
			}
		}
	}
	
	public function getType() {
		if(isset(static::$_baseType)) {
			return static::$_baseType;
		}
		return static::$_documentType;
	}
	

}