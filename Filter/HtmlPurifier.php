<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Filter
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  MW_Filter_HtmlPurifier
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Filter
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: HtmlPurifier.php 492 2010-11-06 00:48:54Z jester $
 */

require_once dirname(__FILE__).'/../ext/library/HTMLPurifier.safe-includes.php';

class Epic_Filter_HtmlPurifier implements Zend_Filter_Interface {
	protected $_htmlPurifier = null;

	public function __construct($options = null)
	{
		// set up configuration
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.DefinitionID', 'MW Safe Filter');
		$config->set('HTML.DefinitionRev', 1); // increment when configuration changes
		$config->set('Cache.DefinitionImpl', null); // comment out after finalizing the config

		// Doctype
		$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$callback = false;
		if ( isset($options["configCallback"]) ) {
			$callback = $options["configCallback"];
			unset( $options["configCallback"] );
		}

		if (!is_null($options)) {
				//$config = HTMLPurifier_Config::createDefault();
				foreach ($options as $option) {
						call_user_func_array(array($config, "set"), $option);
						// $config->set($option[0], $option[1], $option[2]);
				}
		}
		if ( $callback ) {
			call_user_func( $callback, $config, &$options );
		}

		$this->_htmlPurifier = new HTMLPurifier($config);
	}

	public function filter($value)
	{
			return $this->_htmlPurifier->purify($value);
	}
}
