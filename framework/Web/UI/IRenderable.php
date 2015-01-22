<?php
/**
 * Core interfaces essential for TApplication class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI
 */

namespace Prado\Web\UI;

/**
 * IRenderable interface.
 *
 * This interface must be implemented by classes that can be rendered
 * to end-users.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package Prado\Web\UI
 * @since 3.0
 */
interface IRenderable
{
	/**
	 * Renders the component to end-users.
	 * @param ITextWriter writer for the rendering purpose
	 */
	public function render($writer);
}