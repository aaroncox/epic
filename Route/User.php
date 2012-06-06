<?php
/**
 *
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  EpicDb_Route_User
 *
 * undocumented
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: User.php 663 2011-03-08 22:41:56Z root $
 */
class Epic_Route_User extends Zend_Controller_Router_Route {

	static public $types = array('user');
	
	public static function getInstance(Zend_Config $config)
	{
		$defaults = array(
			'controller' => 'user',
			'action' => 'view',
			'module' => 'default',
		);
		$reqs = array(
			'type' => implode('|',self::$types),
			'id' => '\d+|[a-f0-9]{24}',
		);

		$route = $config->route;
		$reqs = ($config->reqs instanceof Zend_Config) ? array_merge($config->reqs->toArray(),$reqs) : $reqs;
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() + $defaults : $defaults;
		return new static($route, $defs, $reqs);
	}

	public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
	{
		$user = false;
		if(isset($data['user'])) {
			$user = $data['user'];
		} elseif(isset($this->_values['user'])) {
			$user = $this->_values['user'];
		}
		if ($user instanceOf Epic_Mongo_Document) {
			$filter = new Epic_Filter_Slug();
			$data['type'] = $user->_type;
			$data['id'] = $user->id;
			$data['slug'] = $filter->filter($user->name);
			unset($data['user']);
		} else {
			throw new Exception("Expected EpicDb_Mongo_User, got ".get_class($data['user']));
		}
		// foreach ($data as $key => $value) {
		// 	if ( isset($this->_defaults[$key]) && $this->_defaults[$key] == $value ) {
		// 		unset($data[$key]);
		// 	}
		// }
		$result = parent::assemble($data, $reset, $encode, $partial);
		return $result;
	}

	public function getUser($params)
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
			$user = $this->getUser($match);
			if (!$user) {
				$this->_values = array(); return false;
			}
			$match['user'] = $user;
			$this->_values['user'] = $user;
		}
		return $match;
	}
}