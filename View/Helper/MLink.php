<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_View_Helper_MLink extends Zend_View_Helper_Abstract
{
	public function mLink($doc, $params = array()) {
		if(!$doc instanceOf Epic_Mongo_Document) {
			return false;
		}
		$urlParams['action'] = 'view';		
		if(isset($params['action'])) {
			$urlParams['action'] = $params['action'];
		}
		$linkText = $doc->title ?: $doc->name;
		if(isset($params['text'])) {
			$linkText = $params['text'];
		}
		return $this->view->htmlTag("a", array(
			'href' => $this->view->url(array(
				$doc->route => $doc,
			)+$urlParams, $doc->route, true),
		), $linkText);
	}
} // END class Greymass_View_Helper_mLink extends Zend_View_Helper_Abstract