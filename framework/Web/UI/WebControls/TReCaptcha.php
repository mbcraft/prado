<?php

/**
 * TReCaptcha class file
 *
 * @author Bérczi Gábor <gabor.berczi@devworx.hu>
 * @link http://www.devworx.hu/
 * @copyright Copyright &copy; 2011 DevWorx
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI\WebControls
 */

namespace Prado\Web\UI\WebControls;
use Prado\TPropertyValue;

/**
 * TReCaptcha class.
 *
 * TReCaptcha displays a reCAPTCHA (a token displayed as an image) that can be used
 * to determine if the input is entered by a real user instead of some program. It can
 * also prevent multiple submits of the same form either by accident, or on purpose (ie. spamming).
 *
 * The reCAPTCHA to solve (a string consisting of two separate words) displayed is automatically
 * generated by the reCAPTCHA system at recaptcha.net. However, in order to use the services
 * of the site you will need to register and get a public and a private API key pair, and 
 * supply those to the reCAPTCHA control through setting the {@link setPrivateKey PrivateKey} 
 * and {@link setPublicKey PublicKey} properties. 
 *
 * Currently the reCAPTCHA API supports only one reCAPTCHA field per page, so you MUST make sure that all 
 * your input is protected and validated by a single reCAPTCHA control. Placing more than one reCAPTCHA
 * control on the page will lead to unpredictable results, and the user will most likely unable to solve 
 * any of them successfully.
 *
 * Upon postback, user input can be validated by calling {@link validate()}.
 * The {@link TReCaptchaValidator} control can also be used to do validation, which provides
 * server-side validation. Calling (@link validate()) will invalidate the token supplied, so all consecutive
 * calls to the method - without solving a new captcha - will return false. Therefore if implementing a multi-stage
 * input process, you must make sure that you call validate() only once, either at the end of the input process, or 
 * you store the result till the end of the processing.
 *
 * The following template shows a typical use of TReCaptcha control:
 * <code>
 * <com:TReCaptcha ID="Captcha"
 *                 PublicKey="..."
 *                 PrivateKey="..."
 * />
 * <com:TReCaptchaValidator ControlToValidate="Captcha"
 *                          ErrorMessage="You are challenged!" />
 * </code>
 *
 * @author Bérczi Gábor <gabor.berczi@devworx.hu>
 * @package Prado\Web\UI\WebControls
 * @since 3.2
 */
class TReCaptcha extends \Prado\Web\UI\WebControls\TWebControl implements \Prado\Web\UI\IValidatable
{
	private $_isValid=true;

	const ChallengeFieldName = 'recaptcha_challenge_field';
	const ResponseFieldName = 'recaptcha_response_field';

	public function getTagName()
	{
		return 'span';
	}
	
	/**
	 * Returns true if this control validated successfully. 
	 * Defaults to true.
	 * @return bool wether this control validated successfully.
	 */
	public function getIsValid()
	{
		return $this->_isValid;
	}
	/**
	 * @param bool wether this control is valid.
	 */
	public function setIsValid($value)
	{
		$this->_isValid=TPropertyValue::ensureBoolean($value);
	}
	
	public function getValidationPropertyValue()
	{
		return $this->Request[$this->getChallengeFieldName()];
	}

	public function getPublicKey()
	{
		return $this->getViewState('PublicKey');
	}

	public function setPublicKey($value)
	{
		return $this->setViewState('PublicKey', TPropertyValue::ensureString($value));
	}

	public function getPrivateKey()
	{
		return $this->getViewState('PrivateKey');
	}

	public function setPrivateKey($value)
	{
		return $this->setViewState('PrivateKey', TPropertyValue::ensureString($value));
	}
	
	public function getThemeName()
	{
		return $this->getViewState('ThemeName');
	}

	public function setThemeName($value)
	{
		return $this->setViewState('ThemeName', TPropertyValue::ensureString($value));
	}

	public function getCustomTranslations()
	{
		return TPropertyValue::ensureArray($this->getViewState('CustomTranslations'));
	}

	public function setCustomTranslations($value)
	{
		return $this->setViewState('CustomTranslations', TPropertyValue::ensureArray($value));
	}

	public function getLanguage()
	{
		return $this->getViewState('Language');
	}

	public function setLanguage($value)
	{
		return $this->setViewState('Language', TPropertyValue::ensureString($value));
	}

	public function getCallbackScript()
	{
		return $this->getViewState('CallbackScript');
	}

	public function setCallbackScript($value)
	{
		return $this->setViewState('CallbackScript', TPropertyValue::ensureString($value));
	}

	protected function getChallengeFieldName()
	{
		return /*$this->ClientID.'_'.*/self::ChallengeFieldName;
	}
	
	public function getResponseFieldName()
	{
		return /*$this->ClientID.'_'.*/self::ResponseFieldName;
	}
	
	public function getClientSideOptions()
	{
		$options = array();
		if ($theme = $this->getThemeName())
			$options['theme'] = $theme;
		if ($lang = $this->getLanguage())
			$options['lang'] = $lang;
		if ($trans = $this->getCustomTranslations())
			$options['custom_translations'] = $trans;
		return $options;
	}

	public function validate()
	{
		if (!
		      (
			($challenge = @$_POST[$this->getChallengeFieldName()])
			and
			($response = @$_POST[$this->getResponseFieldName()])
		      )
                   )
		   return false;

		$resp = recaptcha_check_answer(
			$this->getPrivateKey(),
			$_SERVER["REMOTE_ADDR"],
			$challenge,
			$response
		); 
		return ($resp->is_valid==1);
	}

	/**
	 * Checks for API keys
	 * @param mixed event parameter
	 */
	public function onPreRender($param)
	{
		parent::onPreRender($param);

		if("" == $this->getPublicKey())
			throw new TConfigurationException('recaptcha_publickey_unknown');
		if("" == $this->getPrivateKey())
			throw new TConfigurationException('recaptcha_privatekey_unknown');

		// need to register captcha fields so they will be sent back also in callbacks 
		$page = $this->getPage();
		$page->registerRequiresPostData($this->getChallengeFieldName());
		$page->registerRequiresPostData($this->getResponseFieldName());
	}

	protected function addAttributesToRender($writer)
	{
		parent::addAttributesToRender($writer);
		$writer->addAttribute('id',$this->getClientID());
	}

	public function regenerateToken()
	{
		// if we're in a callback, then schedule re-rendering of the control 
		// if not, don't do anything, because a new challenge will be rendered anyway
		if ($this->Page->IsCallback)
			$this->Page->ClientScript->registerEndScript($this->getClientID().'::refresh', implode(' ', array(
				// work-around for "ReCaptchaState is undefined" bug 
				// (if there's no previous instance yet, regenerating the token is not needed anyway)
				'if (typeof ReCaptchaState != "undefined") '.
				'  Recaptcha.reload();',
			)));
	}

	public function renderContents($writer)
	{
		$readyscript = 'jQuery(document).trigger('.TJavaScript::quoteString('captchaready:'.$this->getClientID()).')';
		$cs = $this->Page->ClientScript;
		$id = $this->getClientID();
		$divid = $id.'_1_recaptchadiv';
		$writer->write('<div id="'.htmlspecialchars($divid).'">');
	
		if (!$this->Page->IsCallback)
			{
				$writer->write(TJavaScript::renderScriptBlock(
					'var RecaptchaOptions = '.TJavaScript::jsonEncode($this->getClientSideOptions()).';'
				));
	
				$html = recaptcha_get_html($this->getPublicKey());
				/*
				reCAPTCHA currently does not support multiple validations per page
				$html = str_replace(
					array(self::ChallengeFieldName,self::ResponseFieldName),
					array($this->getChallengeFieldName(),$this->getResponseFieldName()),
					$html
				);
				*/
				$writer->write($html);

				$cs->registerEndScript('ReCaptcha::EventScript', 'jQuery(document).ready(function() { '.$readyscript.'; } );');
			}
		else
			{
				$options = $this->getClientSideOptions();
				$options['callback'] = new TJavaScriptLiteral('function() { '.$readyscript.'; '.$this->getCallbackScript().'; }');
				$cs->registerScriptFile('ReCaptcha::AjaxScript', 'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
				$cs->registerEndScript('ReCaptcha::CreateScript::'.$id, implode(' ', array(
					'if (!jQuery('.TJavaScript::quoteString('#'.$this->getResponseFieldName()).'))',
					'{',
					'Recaptcha.destroy();',
					'Recaptcha.create(',
						TJavaScript::quoteString($this->getPublicKey()).', ',
						TJavaScript::quoteString($divid).', ',
						TJavaScript::encode($options),
					');',
					'}',
				)));
			}
			
		$writer->write('</div>');
	}

}
