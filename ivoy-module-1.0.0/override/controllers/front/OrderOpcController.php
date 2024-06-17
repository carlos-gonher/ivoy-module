<?php
/*
*
*  @author: VidaFull DevTeam <devops@vidafull.mx>
*  @copyright  VidaFull S.A. Nov 2017 
*  
*/

class OrderOpcController extends OrderOpcControllerCore
{
    
    public function setMedia(){
		parent::setMedia();

		if (!$this->useMobileTheme())
		{
			// Adding CSS style sheet
			$this->addCSS(_THEME_CSS_DIR_.'order-opc.css');
			// Adding JS files
			$this->addJS(_THEME_JS_DIR_.'order-opc.js');
                        $this->addJS(_THEME_JS_DIR_.'ivoy-delivery.js');
			$this->addJqueryPlugin('scrollTo');
		}
		else
			$this->addJS(_THEME_MOBILE_JS_DIR_.'opc.js');

		$this->addJS(array(
			_THEME_JS_DIR_.'tools/vatManagement.js',
			_THEME_JS_DIR_.'tools/statesManagement.js',
			_THEME_JS_DIR_.'order-carrier.js',
			_PS_JS_DIR_.'validate.js'
		));
    }

}