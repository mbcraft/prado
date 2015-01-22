<?php
/**
 * Core interfaces essential for TApplication class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI\ActiveControls
 */

namespace Prado\Web\UI\ActiveControls;

/**
 * IActiveControl interface.
 *
 * Active controls must implement IActiveControl interface.
 *
 * @author Wei Zhuo <weizhuo[at]gamil[dot]com>
 * @package Prado\Web\UI\ActiveControls
 * @since 3.1
 */
interface IActiveControl
{
	/**
	 * @return TBaseActiveControl Active control properties.
	 */
	public function getActiveControl();
}