<?php
/**
 * TTextBox class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package System.Web.UI.WebControls
 */

/**
 * TTextBoxMode class.
 * TTextBoxMode defines the enumerable type for the possible mode
 * that a {@link TTextBox} control could be at.
 *
 * The following enumerable values are defined:
 * - SingleLine: the textbox will be a regular single line input
 * - MultiLine: the textbox will be a textarea allowing multiple line input
 * - Password: the textbox will hide user input like a password input box
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package System.Web.UI.WebControls
 * @since 3.0.4
 */
class TTextBoxMode extends TEnumerable
{
	const SingleLine='SingleLine';
	const MultiLine='MultiLine';
	const Password='Password';
}