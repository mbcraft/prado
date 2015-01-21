<?php
/**
 * IDataSource, TDataSourceControl, TReadOnlyDataSource class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Web\UI\WebControls
 */

namespace Prado\Web\UI\WebControls;

/**
 * IDataSource class
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package Prado\Web\UI\WebControls
 * @since 3.0
 */
interface IDataSource
{
	public function getView($viewName);
	public function getViewNames();
	public function onDataSourceChanged($param);
}