<?php
/**
 * TDbConnection class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Data
 */

namespace Prado\Data;

/**
 * TDbColumnCaseMode
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package Prado\Data
 * @since 3.0
 */
class TDbColumnCaseMode extends \Prado\TEnumerable
{
	/**
	 * Column name cases are kept as is from the database
	 */
	const Preserved='Preserved';
	/**
	 * Column names are converted to lower case
	 */
	const LowerCase='LowerCase';
	/**
	 * Column names are converted to upper case
	 */
	const UpperCase='UpperCase';
}