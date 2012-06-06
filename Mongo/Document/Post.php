<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Mongo_Document_Post extends Epic_Mongo_Document
{
	public $route = 'post';
	protected static $_baseType = 'post';
	protected static $_collectionName = 'posts';
	
	protected $_requirements = array(
		'_parent' => array('Document:Epic_Mongo_Document_Post', 'AsReference'),
		'tags' => array('DocumentSet:Epic_Mongo_DocumentSet_Tags'),
	);
	
} // END class Epic_Mongo_User extends Epic_Mongo_Document