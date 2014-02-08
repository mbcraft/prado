<?php
/**
 * TJuiResizable class file.
 *
 * @author Fabio Bas <ctrlaltca[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2013-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package System.Web.UI.JuiControls
 */

Prado::using('System.Web.UI.JuiControls.TJuiControlAdapter');
Prado::using('System.Web.UI.ActiveControls.TActivePanel');

/**
 * TJuiResizable class.
 *
 * TJuiResizable is an extension to {@link TActivePanel} based on jQuery-UI's
 * {@link http://jqueryui.com/resizable/ Resizable} interaction.
 * A small handle is shown on the bottom right corner of the panel, that permits
 * the panel to be resized using the mouse.
 *
 * <code>
 * <com:TJuiResizable
 *     ID="resize1"
 *     Style="border: 1px solid green; width:100px;height:100px;background-color: #00dd00"
 *     Options.maxHeight="250"
 *     Options.maxWidth="350"
 *     Options.minHeight="150"
 *     Options.minWidth="200"
 * >
 * resize me
 * </com:TJuiResizable>
 * </code>
 *
 * @author Fabio Bas <ctrlaltca[at]gmail[dot]com>
 * @package System.Web.UI.JuiControls
 * @since 3.3
 */
class TJuiResizable extends TActivePanel implements IJuiOptions, ICallbackEventHandler
{
	protected $_options;

	/**
	 * Creates a new callback control, sets the adapter to
	 * TActiveControlAdapter. If you override this class, be sure to set the
	 * adapter appropriately by, for example, by calling this constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setAdapter(new TJuiControlAdapter($this));
	}

	/**
	 * Object containing defined javascript options
	 * @return TJuiControlOptions
	 */
	public function getOptions()
	{
		if($this->_options===null)
			$this->_options=new TJuiControlOptions($this);
		return $this->_options;
	}

	/**
	 * Array containing valid javascript options
	 * @return array()
	 */
	public function getValidOptions()
	{
		return array('alsoResize', 'animate', 'animateDuration', 'animateEasing', 'aspectRatio', 'autoHide', 'cancel', 'containment', 'delay', 'disabled', 'distance', 'ghost', 'grid', 'handles', 'helper', 'maxHeight', 'maxWidth', 'minHeight', 'minWidth');
	}

	/**
	 * Array containing valid javascript events
	 * @return array()
	 */
	public function getValidEvents()
	{
		return array('create', 'resize', 'start', 'stop');
	}

	/**
	 * @return array list of callback options.
	 */
	protected function getPostBackOptions()
	{
		$options = $this->getOptions()->toArray();
		return $options;
	}

	/**
	 * Ensure that the ID attribute is rendered and registers the javascript code
	 * for initializing the active control.
	 */
	protected function addAttributesToRender($writer)
	{
		parent::addAttributesToRender($writer);

		$writer->addAttribute('id',$this->getClientID());
		$options=TJavascript::encode($this->getPostBackOptions());
		$cs=$this->getPage()->getClientScript();
		$code="jQuery('#".$this->getClientId()."').resizable(".$options.");";
		$cs->registerEndScript(sprintf('%08X', crc32($code)), $code);
	}

	/**
	 * Raises callback event. This method is required by the {@link ICallbackEventHandler}
	 * interface.
	 * @param TCallbackEventParameter the parameter associated with the callback event
	 */
	public function raiseCallbackEvent($param)
	{
		$this->getOptions()->raiseCallbackEvent($param);
	}

	/**
	 * Raises the OnCreate event
	 * @param object $params event parameters
	 */
	public function onCreate ($params)
	{
		$this->raiseEvent('OnCreate', $this, $params);
	}

	/**
	 * Raises the OnResize event
	 * @param object $params event parameters
	 */
	public function onResize ($params)
	{
		$this->raiseEvent('OnResize', $this, $params);
	}

	/**
	 * Raises the OnStart event
	 * @param object $params event parameters
	 */
	public function onStart ($params)
	{
		$this->raiseEvent('OnStart', $this, $params);
	}

	/**
	 * Raises the OnStop event
	 * @param object $params event parameters
	 */
	public function onStop ($params)
	{
		$this->raiseEvent('OnStop', $this, $params);
	}
}
