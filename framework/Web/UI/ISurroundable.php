<?php
/**
 * TControl, TControlCollection, TEventParameter and INamingContainer class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI
 */

namespace Prado\Web\UI;

/**
 * ISurroundable interface
 *
 * Identifies controls that may create an additional surrounding tag. The id of the
 * tag can be obtained with {@link getSurroundingTagID}.
 *
 * @package Prado\Web\UI
 * @since 3.1.2
 */
interface ISurroundable
{
	/**
	 * @return string the id of the embedding tag of the control or the control's clientID if not surrounded
	 */
	public function getSurroundingTagID();
}