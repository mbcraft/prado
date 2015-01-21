<?php
/**
 * TJuiControlAdapter class file.
 *
 * @author Fabio Bas <ctrlaltca@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2013-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI\JuiControls
 */

namespace Prado\Web\UI\JuiControls;

/**
 * IJuiOptions interface
 *
 * IJuiOptions is the interface that must be implemented by controls using
 * {@link TJuiControlOptions}.
 *
 * @author Fabio Bas <ctrlaltca@gmail.com>
 * @package Prado\Web\UI\JuiControls
 * @since 3.3
 */
interface IJuiOptions
{
	public function getOptions();
	public function getValidOptions();
	public function getValidEvents();
}