<?php
/**
 * 
 *
 * @author Corey Frang
 * @package R2Db_View
 * @subpackage Helper
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 */

/**
 *  R2Db_View_Helper_HeadJs
 *
 * undocumented
 *
 * @author Corey Frang
 * @package R2Db_View
 * @subpackage Helper
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id:$
 */
class Epic_View_Helper_HeadJs extends Zend_View_Helper_Abstract {
	static protected $_headJs = false;
	public function headJs()
	{
		if ( self::$_headJs ) {
			return self::$_headJs;
		}

		if ( APPLICATION_ENV !== "production" ) {
			return self::$_headJs = $this->view->headScript();
		}

		return self::$_headJs = $this->view->bundleScript()
			->setCacheDir(APPLICATION_PATH . '/../cache/js')
			->setDocRoot(APPLICATION_PATH . '/../public_html')
			->setUseGzip(true)
			->setGzipLevel(9)
			->setUrlPrefix('j')
			->setUseMinify(true)
			->setMinifyCommand('java -jar /usr/bin/yuicompressor :filename --type js');
	}
}