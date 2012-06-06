<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Application
 * @subpackage Resource
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  MW_Application_Resource_Mongo
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Application
 * @subpackage Resource
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Mongo.php 532 2011-03-19 02:02:24Z jester $
 */
class Epic_Application_Resource_Mongo extends Zend_Application_Resource_ResourceAbstract {

	/**
	 * Contains the registered schemas by their "tag"
	 *
	 * @var array of arrays of MW_Mongo_Schema
	 **/
	protected $_schemas = array();
	protected $_options = array();

	/**
	 * Loads the Urchin ID into this resource
	 *
	 * @return void
	 * @author Corey Frang
	 **/
	public function init()
	{
		try {
			$bootstrap = $this->getBootstrap();
			$this->_options = $mongo = $this->getOptions();
			Zend_Registry::getInstance()->mongo = $this;

			// break Shanty Mongo Connections and install ours
			if ( count( $mongo ) ) {
				Shanty_Mongo::removeConnectionGroups();
				Shanty_Mongo::addConnections( $mongo );
			}

			foreach ( $mongo as $name => $opts )
			{
				$schemas = array();
				$addSchema = function( $className ) use ( &$schemas ) {
					$obj = $className::getInstance();
					$obj->check();
					array_push( $schemas, $obj );
				};
				if ( isset( $opts['schema'] ) ) {
					$schema = $opts['schema'];
					if (is_array($schema)) {
						foreach ($schema as $class) {
							$addSchema( $class );
						}
					} else {
						$addSchema( $schema );
					}
					unset($mongo[$name]['schema']);
				}
				$this->_schemas[ $name ] = $schemas;
			}
			Zend_Registry::getInstance()->mongoSchema = $this->_schemas;

		} catch (MongoConnectionException $e) {

			//mongodb://----:----@localhost:27017/r2db: Transport endpoint is not connected
			$message = $e->getMessage();
			$message = preg_replace("#mongodb://([^:]+:([^@]+)@)?([^:/]+)(:\d+)?/[^:]+#", "mongo", $message);
			throw new MongoConnectionException( $message );

		}
	}

	public function getSchemasForTag( $tag = null )
	{
		if ( $tag == null ) return $this->_schemas;
		if ( isset( $this->_schemas[ $tag ] ) ) return $this->_schemas[ $tag ];
		return array();
	}

	public function getDbName( $tag )
	{
		if ( $tag == null ) return @$this->_options['default']['database'] ?: false;
		return @$this->_options[$tag]['database'] ?: false;
	}
}
