<?php
/**
 *
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  EpicDb_Route_record
 *
 * undocumented
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: record.php 663 2011-03-08 22:41:56Z root $
 */
class Epic_Route_Record extends Zend_Controller_Router_Route {

	static public $types = array('record');
	
	public static function getInstance(Zend_Config $config)
	{
		$defaults = array(
			'controller' => 'record',
			'action' => 'view',
			'module' => 'default',
		);
		$reqs = array(
			'type' => implode('|',self::$types),
			'id' => '\d+|[a-f0-9]{24}',
		);
		$route = $config->route;
		if($config->reqs instanceof Zend_Config) {
			$cfg = $config->reqs->toArray();
			if(isset($cfg['type'])) {
				static::$types = array_merge(explode("|", $reqs['type']),explode("|", $cfg['type']));
				$reqs['type'] = implode("|", static::$types);
			}
		}
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() + $defaults : $defaults;
		return new static($route, $defs, $reqs);
	}

	public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
	{
		$record = false;
		$recordHash = '';
		if(isset($data['record'])) {
			$record = $data['record'];
		} elseif(isset($this->_values['record'])) {
			$record = $this->_values['record'];
		}
		if ($record instanceOf Epic_Mongo_Document) {
			$filter = new Epic_Filter_Slug();
			$data['type'] = $record->_type;
			$data['id'] = $record->id;
			$data['slug'] = $filter->filter($record->name);
			unset($data['record']);
		} else {
			throw new Exception("Expected EpicDb_Mongo_Document, got ".get_class($data['record']));
		}
		foreach ($data as $key => $value) {
			if ( isset($this->_defaults[$key]) && $this->_defaults[$key] == $value ) {
				unset($data[$key]);
			}
		}
		$result = parent::assemble($data, $reset, $encode, $partial);
		return $result.$recordHash;
	}

	public function getRecord($params)
	{
		if(!in_array($params['type'], static::$types)) {
			return null;
		}
		// var_dump(Epic_Mongo::db($params['type'])->fetchOne(array('id'=>(int)$params['id'])), $params['id']);
		return Epic_Mongo::db($params['type'])->fetchOne(array('id'=>(int)$params['id']));
	}

	public function match($path, $partial = false)
	{
		$match = parent::match($path, $partial);
		// var_dump($match, $path, $partial); exit;
		if ($match) {
			$record = $this->getrecord($match);
			if (!$record) {
				$this->_values = array(); return false;
			}
			$match['record'] = $record;
			$this->_values['record'] = $record;
		}
		return $match;
	}
}