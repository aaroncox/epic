<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Controller_Action extends Zend_Controller_Action
{
	
	public function preDispatch() {
	}
	
	public function postDispatch() {
	}
	
	protected $_forceProcess = false;
  
	public function _handleForm(Epic_Form $form, $key = '')
  {
    $ajax = false;
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $ajax = true;
      $this->_helper->layout->disableLayout();
    }
    $form->trackReferrer($this->getRequest());
    if (($this->_request->isPost() || $this->_forceProcess) && $form->process($this->_forceProcess ? $this->_request->getParams() : $this->_request->getPost()))
    {
      return $this->_formSuccess($form, $key, $ajax);
    }
    else if ($ajax && $this->_request->getParam('format') == 'json-form') {
      $this->_helper->viewRenderer->setNoRender(true);     
      echo $this->view->json($form->getJson());
      return;
    }
  }

  protected function _formRedirect($form, $key, $ajax) {
		
    return $form->getReferrer($this->view->url());
  }

  public function _formSuccess(Epic_Form $form, $key, $ajax)
  {
    if ($ajax)
    {
      $this->_helper->viewRenderer->setNoRender(true);     
      $this->getResponse()->setHeader('Content-Type', 'text/javascript'); 
      
      echo $this->view->json($form->getAjaxResponse());
      return;
    }
    else
    {
      $url = $this->_formRedirect($form, $key, $ajax);
      return $this->_redirect($url);                    
    }
  }

	public function requireAdmin() {
		$profile = Epic_Auth::getInstance()->getProfile();
		if(!$profile) {
			$this->_redirect("/admin/login");			
		}
		if($profile->_access != "admin") {
			throw new Exception("Access Denied");
		}		
	}
} // END class Epic_Controller_Action