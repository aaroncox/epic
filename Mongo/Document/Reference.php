<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @subpackage Document
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 */

/**
 *  Epic_Mongo_Document_Ref
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @subpackage Document
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id:$
 */
class Epic_Mongo_Document_Reference extends Epic_Mongo_Document {
	protected $_requirements = array(
			'ref' => array('Document:Epic_Mongo_Document', 'AsReference', 'Required'),
		);

	public function set(Epic_Mongo_Document $ref) {
		$this->ref = $ref;
	}

	public function getPropertyClass($property, $data)
	{
		if ($property == 'ref') {
			if( !isset( $data['_type'] ) ) {
				return null;
				var_dump("Bad Record for tagging... dumping data...", $property, $data);
				exit;
			}
			$mongo = Zend_Registry::getInstance()->mongo;
			$schemas = $mongo->getSchemasForTag();
			foreach ($schemas as $group) {
				foreach ($group as $schema) {
					if ( $class = $schema->getClassForType( $data['_type'] ) ) {
						return $class;
					}
				}
			}
			return null;
		}
	}
}