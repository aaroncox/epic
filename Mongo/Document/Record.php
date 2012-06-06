<?php
/**
 * EpicDb_Mongo_Record
 *
 * undocumented class
 *
 * @author Aaron Cox <aaronc@fmanet.org>
 * @param undocumented class
 * @package undocumented class
 **/
class Epic_Mongo_Document_Record extends Epic_Mongo_Document implements Epic_Interface_Cardable
{
	public $route = 'record';	
	protected static $_baseType = 'record';
	protected static $_collectionName = 'records';
	protected static $_documentSetClass = 'Epic_Mongo_DocumentSet_Records';
	
	protected $_requirements = array(
		'tags' => array('DocumentSet:Epic_Mongo_DocumentSet_Tags'),
	);
		
	public function getIcon() {
		
	}
	
	public function getName() {
		
	}

	public function cardProperties($view) {
		return array(
			'is a' => $this->_type
		);
	}

}