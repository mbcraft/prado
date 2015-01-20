<?php
/**
 * TDataGatewayCommand, TDataGatewayEventParameter and TDataGatewayResultEventParameter class file.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Id$
 * @package System.Data.DataGateway
 */)");
		else
			$criteria->setCondition($index);
		$command = $this->getFindCommand($criteria);
		$this->onCreateCommand($command, $criteria);
		return $this->onExecuteCommand($command,$command->query());
	}

	/**
	 * @param array multiple primary key values or composite value arrays
	 * @return integer number of rows affected.
	 */
	public function deleteByPk($keys)
	{
		$where = $this->getCompositeKeyCondition((array)$keys);
		$command = $this->getBuilder()->createDeleteCommand($where);
		$this->onCreateCommand($command, new TSqlCriteria($where,$keys));
		$command->prepare();
		return $this->onExecuteCommand($command,$command->execute());
	}

	public function getIndexKeyCondition($table,$fields,$values)
	{
		if (!count($values))
			return 'FALSE';
		$columns = array();
		$tableName = $table->getTableFullName();
		foreach($fields as $field)
			$columns[] = $tableName.'.'.$table->getColumn($field)->getColumnName();
		return '('.implode(', ',$columns).') IN '.$this->quoteTuple($values);
	}

	/**
	 * Construct a "pk IN ('key1', 'key2', ...)" criteria.
	 * @param array values for IN predicate
	 * @param string SQL string for primary keys IN a list.
	 */
	protected function getCompositeKeyCondition($values)
	{
		$primary = $this->getTableInfo()->getPrimaryKeys();
		$count = count($primary);
		if($count===0)
		{
			throw new TDbException('dbtablegateway_no_primary_key_found',
				$this->getTableInfo()->getTableFullName());
		}
		if(!is_array($values) || count($values) === 0)
		{
			throw new TDbException('dbtablegateway_missing_pk_values',
				$this->getTableInfo()->getTableFullName());
		}
		if($count>1 && (!isset($values[0]) || !is_array($values[0])))
			$values = array($values);
		if($count > 1 && count($values[0]) !== $count)
		{
			throw new TDbException('dbtablegateway_pk_value_count_mismatch',
				$this->getTableInfo()->getTableFullName());
		}
		return $this->getIndexKeyCondition($this->getTableInfo(),$primary, $values);
	}

	/**
	 * @param TDbConnection database connection.
	 * @param array values
	 * @return string quoted recursive tuple values, e.g. "('val1', 'val2')".
	 */
	protected function quoteTuple($array)
	{
		$conn = $this->getDbConnection();
		$data = array();
		foreach($array as $k=>$v)
			$data[] = is_array($v) ? $this->quoteTuple($v) : $conn->quoteString($v);
		return '('.implode(', ', $data).')';
	}

	/**
	 * Create the condition and parameters for find by primary.
	 * @param array primary key values
	 * @return array tuple($where, $parameters)
	 */
	protected function getPrimaryKeyCondition($values)
	{
		$primary = $this->getTableInfo()->getPrimaryKeys();
		if(count($primary)===0)
		{
			throw new TDbException('dbtablegateway_no_primary_key_found',
				$this->getTableInfo()->getTableFullName());
		}
		$criteria=array();
		$bindings=array();
		$i = 0;
		foreach($primary as $key)
		{
			$column = $this->getTableInfo()->getColumn($key)->getColumnName();
			$criteria[] = $column.' = :'.$key;
			$bindings[$key] = isset($values[$key])?$values[$key]:$values[$i++];
		}
		return array(implode(' AND ', $criteria), $bindings);
	}

	/**
	 * Find one matching records for arbituary SQL.
	 * @param TSqlCriteria $criteria
	 * @return TDbDataReader record reader.
	 */
	public function findBySql($criteria)
	{
		$command = $this->getSqlCommand($criteria);
		return $this->onExecuteCommand($command, $command->queryRow());
	}

	/**
	 * Find zero or more matching records for arbituary SQL.
	 * @param TSqlCriteria $criteria
	 * @return TDbDataReader record reader.
	 */
	public function findAllBySql($criteria)
	{
		$command = $this->getSqlCommand($criteria);
		return $this->onExecuteCommand($command, $command->query());
	}

	/**
	 * Build sql command from the criteria. Limit, Offset and Ordering are applied if applicable.
	 * @param TSqlCriteria $criteria
	 * @return TDbCommand command corresponding to the criteria.
	 */
	protected function getSqlCommand($criteria)
	{
		$sql = $criteria->getCondition();
		$ordering = $criteria->getOrdersBy();
		$limit = $criteria->getLimit();
		$offset = $criteria->getOffset();
		if(count($ordering) > 0)
			$sql = $this->getBuilder()->applyOrdering($sql, $ordering);
		if($limit>=0 || $offset>=0)
			$sql = $this->getBuilder()->applyLimitOffset($sql, $limit, $offset);
		$command = $this->getBuilder()->createCommand($sql);
		$this->getBuilder()->bindArrayValues($command, $criteria->getParameters()->toArray());
		$this->onCreateCommand($command, $criteria);
		return $command;
	}

	/**
	 * @param TSqlCriteria $criteria
	 * @return integer number of records.
	 */
	public function count($criteria)
	{
		if($criteria===null)
			return (int)$this->getBuilder()->createCountCommand()->queryScalar();
		$where = $criteria->getCondition();
		$parameters = $criteria->getParameters()->toArray();
		$ordering = $criteria->getOrdersBy();
		$limit = $criteria->getLimit();
		$offset = $criteria->getOffset();
		$command = $this->getBuilder()->createCountCommand($where,$parameters,$ordering,$limit,$offset);
		$this->onCreateCommand($command, $criteria);
		return $this->onExecuteCommand($command, (int)$command->queryScalar());
	}

	/**
	 * Inserts a new record into the table. Each array key must
	 * correspond to a column name in the table unless a null value is permitted.
	 * @param array new record data.
	 * @return mixed last insert id if one column contains a serial or sequence,
	 * otherwise true if command executes successfully and affected 1 or more rows.
	 */
	public function insert($data)
	{
		$command=$this->getBuilder()->createInsertCommand($data);
		$this->onCreateCommand($command, new TSqlCriteria(null,$data));
		$command->prepare();
		if($this->onExecuteCommand($command, $command->execute()) > 0)
		{
			$value = $this->getLastInsertId();
			return $value !== null ? $value : true;
		}
		return false;
	}

	/**
	 * Iterate through all the columns and returns the last insert id of the
	 * first column that has a sequence or serial.
	 * @return mixed last insert id, null if none is found.
	 */
	public function getLastInsertID()
	{
		return $this->getBuilder()->getLastInsertID();
	}

	/**
	 * @param string __call method name
	 * @param string criteria conditions
	 * @param array method arguments
	 * @return TActiveRecordCriteria criteria created from the method name and its arguments.
	 */
	public function createCriteriaFromString($method, $condition, $args)
	{
		$fields = $this->extractMatchingConditions($method, $condition);
		$args=count($args) === 1 && is_array($args[0]) ? $args[0] : $args;
		if(count($fields)>count($args))
		{
			throw new TDbException('dbtablegateway_mismatch_args_exception',
				$method,count($fields),count($args));
		}
		return new TSqlCriteria(implode(' ',$fields), $args);
	}

	/**
	 * Calculates the AND/OR condition from dynamic method substrings using
	 * table meta data, allows for any AND-OR combinations.
	 * @param string dynamic method name
	 * @param string dynamic method search criteria
	 * @return array search condition substrings
	 */
	protected function extractMatchingConditions($method, $condition)
	{
		$table = $this->getTableInfo();
		$columns = $table->getLowerCaseColumnNames();
		$regexp = '/('.implode('|', array_keys($columns)).')(and|_and_|or|_or_)?/i';
		$matches = array();
		if(!preg_match_all($regexp, strtolower($condition), $matches,PREG_SET_ORDER))
		{
			throw new TDbException('dbtablegateway_mismatch_column_name',
				$method, implode(', ', $columns), $table->getTableFullName());
		}

		$fields = array();
		foreach($matches as $match)
		{
			$key = $columns[$match[1]];
			$column = $table->getColumn($key)->getColumnName();
			$sql = $column . ' = ? ';
			if(count($match) > 2)
				$sql .= strtoupper(str_replace('_', '', $match[2]));
			$fields[] = $sql;
		}
		return $fields;
	}

	/**
	 * Raised when a command is prepared and parameter binding is completed.
	 * The parameter object is TDataGatewayEventParameter of which the
	 * {@link TDataGatewayEventParameter::getCommand Command} property can be
	 * inspected to obtain the sql query to be executed.
	 * @param TDataGatewayCommand originator $sender
	 * @param TDataGatewayEventParameter
	 */
	public function onCreateCommand($command, $criteria)
	{
		$this->raiseEvent('OnCreateCommand', $this, new TDataGatewayEventParameter($command,$criteria));
	}

	/**
	 * Raised when a command is executed and the result from the database was returned.
	 * The parameter object is TDataGatewayResultEventParameter of which the
	 * {@link TDataGatewayEventParameter::getResult Result} property contains
	 * the data return from the database. The data returned can be changed
	 * by setting the {@link TDataGatewayEventParameter::setResult Result} property.
	 * @param TDataGatewayCommand originator $sender
	 * @param TDataGatewayResultEventParameter
	 */
	public function onExecuteCommand($command, $result)
	{
		$parameter = new TDataGatewayResultEventParameter($command, $result);
		$this->raiseEvent('OnExecuteCommand', $this, $parameter);
		return $parameter->getResult();
	}
}

/**
 * TDataGatewayEventParameter class contains the TDbCommand to be executed as
 * well as the criteria object.
 *
 * @author Wei Zhuo <weizho[at]gmail[dot]com>
 * @version $Id$
 * @package System.Data.DataGateway
 * @since 3.1
 */
class TDataGatewayEventParameter extends TEventParameter
{
	private $_command;
	private $_criteria;

	public function __construct($command,$criteria)
	{
		$this->_command=$command;
		$this->_criteria=$criteria;
	}

	/**
	 * The database command to be executed. Do not rebind the parameters or change
	 * the sql query string.
	 * @return TDbCommand command to be executed.
	 */
	public function getCommand()
	{
		return $this->_command;
	}

	/**
	 * @return TSqlCriteria criteria used to bind the sql query parameters.
	 */
	public function getCriteria()
	{
		return $this->_criteria;
	}
}