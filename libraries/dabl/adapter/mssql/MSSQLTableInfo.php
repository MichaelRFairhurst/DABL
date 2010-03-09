<?php

/**
 * MSSQL implementation of TableInfo.
 * 
 * @author	Hans Lellelid <hans@xmpl.org>
 * @version   $Revision: 1.14 $
 * @package   creole.drivers.mssql.metadata
 */
class MSSQLTableInfo extends TableInfo {	
	
	/**
	 * Loads the columns for this table.
	 * @return void
	 */						  
	protected function initColumns() {
		$res = $this->getDatabase()->getConnection()->query("sp_columns ".$this->name);
		
		while ($row = $res->fetch()) {
			$name = $row['COLUMN_NAME'];
			$type = $row['TYPE_NAME'];
			$length = $row['LENGTH'];
			$is_nullable = $row['NULLABLE'];
			$default = $row['COLUMN_DEF'];
			$precision = $row['PRECISION'];
			$scale = $row['SCALE'];
			$identity = false;
			if (strtolower($type) == "int identity") {
				$identity = true;
			}
			$this->columns[$name] = new ColumnInfo($this, $name, MSSQLTypes::getType($type), $type, $length, $precision, $scale, $is_nullable, $default, $identity);
		}
				
		$this->colsLoaded = true;
	}

	/**
	 * Loads the indexes for this table.
	 * @return void
	 */	  
	protected function initIndexes(){
		// columns have to be loaded first
		if (!$this->colsLoaded) $this->initColumns();
		
		$res = $this->getDatabase()->getConnection()->query("sp_indexes_rowset ".$this->name);
		
		while ($row = $res->fetch()) {
			$name = $row['INDEX_NAME'];			
			// All primary keys are indexes (right...?)
			if (!isset($this->indexes[$name])) {
				$this->indexes[$name] = new IndexInfo($name);
			}
			$this->indexes[$name]->addColumn($this->columns[ $row['COLUMN_NAME'] ]);
		}
		
		$this->indexesLoaded = true;		
	}

	/**
	 * Loads the foreign keys for this table.
	 * @return void
	 */	  
	protected function initForeignKeys(){
		// columns have to be loaded first
		if (!$this->colsLoaded) $this->initColumns();
		
		$res = $this->getDatabase()->getConnection()->query("SELECT	 ccu1.TABLE_NAME, ccu1.COLUMN_NAME, ccu2.TABLE_NAME AS FK_TABLE_NAME, ccu2.COLUMN_NAME AS FK_COLUMN_NAME
							FROM		 INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE ccu1 INNER JOIN
									  INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc1 ON tc1.CONSTRAINT_NAME = ccu1.CONSTRAINT_NAME AND 
									  CONSTRAINT_TYPE = 'Foreign Key' INNER JOIN
									  INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc1 ON rc1.CONSTRAINT_NAME = tc1.CONSTRAINT_NAME INNER JOIN
									  INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE ccu2 ON ccu2.CONSTRAINT_NAME = rc1.UNIQUE_CONSTRAINT_NAME
							WHERE	 (ccu1.table_name = '".$this->name."')");
		
		while($row = $res->fetch()) {
			$name = $row['COLUMN_NAME'];
			$ftbl = $row['FK_TABLE_NAME'];
			$fcol = $row['FK_COLUMN_NAME'];

			if (!isset($this->foreignKeys[$name])) {
				$this->foreignKeys[$name] = new ForeignKeyInfo($name);

				if ($this->database->hasTable($ftbl)) {
					$foreignTable = $this->database->getTable($ftbl);
				} else {				
					$foreignTable = new TableInfo($ltbl);
					$this->database->addTable($foreignTable);
				}

				if ($foreignTable->hasColumn($fcol)) {
					$foreignCol = $foreignTable->getColumn($fcol);
				} else {				
					$foreignCol = new ColumnInfo($foreignTable, $fcol);
					$foreignTable->addColumn($foreignCol);
				}
								
				$this->foreignKeys[$name]->addReference($this->columns[$name], $foreignCol);
			}
		}
		
		$this->fksLoaded = true;
	}

	/**
	 * Loads the primary key info for this table.
	 * @return void
	 */	  
	protected function initPrimaryKey(){
		// columns have to be loaded first
		if (!$this->colsLoaded) $this->initColumns();
		
		$res = $this->getDatabase()->getConnection()->query("SELECT COLUMN_NAME
						FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
								INNER JOIN INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE ON 
					  INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_NAME = INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.constraint_name
						WHERE	 (INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_TYPE = 'PRIMARY KEY') AND 
					  (INFORMATION_SCHEMA.TABLE_CONSTRAINTS.TABLE_NAME = '".$this->name."')");
		
		// Loop through the returned results, grouping the same key_name together.
		// name of the primary key will be the first column name in the key.
		while($row = $res->fetch()) {
			$name = $row[0];			
			if (!isset($this->primaryKey)) {
				$this->primaryKey = new PrimaryKeyInfo($name);
			}
			$this->primaryKey->addColumn($this->columns[ $name ]);
		}		
		
		$this->pkLoaded = true;
	}	
	
}