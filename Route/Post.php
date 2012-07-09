<?php
/**
 *
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  EpicDb_Route_Post
 *
 * undocumented
 *
 * @author Corey Frang
 * @package EpicDb_Route
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Post.php 663 2011-03-08 22:41:56Z root $
 */
class Epic_Route_Post extends Zend_Controller_Router_Route {

	static public $types = array('post');
	
	public static function getInstance(Zend_Config $config)
	{
		$defaults = array(
			'controller' => 'post',
			'action' => 'view',
			'module' => 'default',
		);
		$reqs = array(
			'type' => implode('|',self::$types),
			'id' => '\d+|[a-f0-9]{24}',
		);
		if($config->reqs instanceof Zend_Config) {
			$cfg = $config->reqs->toArray();
			if(isset($cfg['type'])) {
				static::$types = array_merge(explode("|", $reqs['type']),explode("|", $cfg['type']));
				$reqs['type'] = implode("|", static::$types);
			}
		}	
		$route = $config->route;
		if($config->reqs instanceof Zend_Config) {
			$cfg = $config->reqs->toArray();
			if(isset($cfg['type'])) {
				static::$types = array_merge(explode("|", $reqs['type']),explode("|", $cfg['type']));
				$reqs['type'] = implode("|", static::$types);
			}
		}
		$reqs = ($config->reqs instanceof Zend_Config) ? array_merge($config->reqs->toArray(),$reqs) : $reqs;
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() + $defaults : $defaults;
		return new static($route, $defs, $reqs);
	}

	public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
	{
		$record = false;
		$postHash = '';
		if(isset($data['post'])) {
			$post = $data['post'];
		} elseif(isset($this->_values['post'])) {
			$post = $this->_values['post'];
		}
		if ($post instanceOf Epic_Mongo_Document) {
			$filter = new Epic_Filter_Slug();
			$data['type'] = $post->_type;
			$data['id'] = $post->id;
			$data['slug'] = $filter->filter($post->title);
			unset($data['post']);
		} else {
			throw new Exception("Expected EpicDb_Mongo_Post, got ".get_class($data['post']));
		}
		foreach ($data as $key => $value) {
			if ( isset($this->_defaults[$key]) && $this->_defaults[$key] == $value ) {
				unset($data[$key]);
			}
		}
		$result = parent::assemble($data, $reset, $encode, $partial);
		return $result.$postHash;
	}

	public function getPost($params)
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
			$post = $this->getPost($match);
			if (!$post) {
				$this->_values = array(); return false;
			}
			$match['post'] = $post;
			$this->_values['post'] = $post;
		}
		return $match;
	}
}