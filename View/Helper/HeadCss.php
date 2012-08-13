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
 *  R2Db_View_Helper_HeadCss
 *
 * undocumented
 *
 * @author Corey Frang
 * @package R2Db_View
 * @subpackage Helper
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id:$
 */
class Epic_View_Helper_HeadCss extends Zend_View_Helper_Abstract {
	
	static protected $_headCss = false;
	
	public function headCss()
	{
		if ( self::$_headCss ) {
			return self::$_headCss;
		}
		
		if ( APPLICATION_ENV != "production" ) {
			return self::$_headCss = $this->view->headLink();
		}
		
		return self::$_headCss = $this->view->bundleLink()
			->setCacheDir(APPLICATION_PATH . '/../cache/css')
			->setDocRoot(APPLICATION_PATH . '/../public_html')
			->setUseGzip(true)
			->setGzipLevel(9)
			->setUseMinify(true)
			->setMinifyCommand('java -jar /usr/bin/yuicompressor :filename --type css')
			->setUrlPrefix('c');
	}
}