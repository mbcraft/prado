<?php
/**
 * TStaticSql class file.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Data\SqlMap\Statements
 */

namespace Prado\Data\SqlMap\Statements;

/**
 * TStaticSql class.
 *
 * @author Wei Zhuo <weizho[at]gmail[dot]com>
 * @package Prado\Data\SqlMap\Statements
 * @since 3.1
 */
class TStaticSql extends \Prado\TComponent
{
	private $_preparedStatement;

	public function buildPreparedStatement($statement, $sqlString)
	{
		$factory = new TPreparedStatementFactory($statement, $sqlString);
		$this->_preparedStatement = $factory->prepare();
	}

	public function getPreparedStatement($parameter=null)
	{
		return $this->_preparedStatement;
	}
}

