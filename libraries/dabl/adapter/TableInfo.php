<?php

/**
 * Represents a table.
 *
 * @author	Hans Lellelid <hans@xmpl.org>
 * @version   $Revision: 1.16 $
 * @package   creole.metadata
 */
abstract class TableInfo {
	
	protected $name;
	protected $columns = array();
	protected $foreignKeys = array();
	protected $indexes = array();
	protected $primaryKey;

	protected $pkLoaded = false;
	protected $fksLoaded = false;
	protected $indexesLoaded = false;
	protected $colsLoaded = false;
	protected $vendorLoaded = false;

	/**
	 * Additional and optional vendor specific information.
	 * @var vendorSpecificInfo
	 */
	protected $vendorSpecificInfo = array();

	/**
	 * Database Connection.
	 * @var DBAdapter
	 */
	protected $conn;

	/**
	 * The parent DatabaseInfo object.
	 * @var DatabaseInfo
	 */
	protected $database;

	/** Shortcut to db resource link id (needed by drivers for queries). */
	protected $dblink;

	/** Shortcut to db name (needed by many drivers for queries). */
	protected $dbname;

	/**
	 * @param string $table The table name.
	 * @param string $database The database name.
	 * @param resource $dblink The db connection resource.
	 */
	function __construct(DatabaseInfo $database, $name) {
		$this->database = $database;
		$this->name = $name;
		$this->conn = $database->getConnection(); // shortcut because all drivers need this for the queries
		$this->dbname = $database->getName();
	}

	/**
	 * This "magic" method is invoked upon serialize().
	 * Because the Info class hierarchy is recursive, we must handle
	 * the serialization and unserialization of this object.
	 * @return array The class variables that should be serialized (all must be public!).
	 */
	function __sleep() {
		return array('name', 'columns', 'foreignKeys', 'indexes', 'primaryKey');
	}

	/**
	 * This "magic" method is invoked upon unserialize().
	 * This method re-hydrates the object and restores the recursive hierarchy.
	 */
	function __wakeup() {
		// restore chaining
		foreach($this->columns as $col) {
			$col->table = $this;
		}
	}

	/**
	 * Loads the columns.
	 * @return void
	 */
	abstract protected function initColumns();

	/**
	 * Loads the primary key information for this table.
	 * @return void
	 */
	abstract protected function initPrimaryKey();

	/**
	 * Loads the foreign keys for this table.
	 * @return void
	 */
	abstract protected function initForeignKeys();

	/**
	 * Loads the indexes information for this table.
	 * @return void
	 */
	abstract protected function initIndexes();

	/**
	 * Loads the vendor specific information for this table.
	 * @return void
	 */
	//it must be asbtract and be implemented in every vendor specific driver,
	//however since it's an experimental stuff it has an empty body in order
	//not to break BC
	/*abstract*/
	protected function initVendorSpecificInfo() {
	}

	/**
	 * Get parimary key in this table.
	 * @throws Exception - if foreign keys are unsupported by DB.
	 * @return array ForeignKeyInfo[]
	 */
	function getPrimaryKey() {
		if(!$this->pkLoaded) $this->initPrimaryKey();
		return $this->primaryKey;
	}

	/**
	 * Get the ColumnInfo object for specified column.
	 * @param string $name The column name.
	 * @return ColumnInfo
	 * @throws SQLException - if column does not exist for this table.
	 */
	function getColumn($name) {
		if(!$this->colsLoaded) $this->initColumns();
		if (!isset($this->columns[$name])) {
			throw new SQLException("Table `".$this->name."` has no column `".$name."`");
		}
		return $this->columns[$name];
	}

	/**
	 * Return whether table contains specified column.
	 * @param string $name The column name.
	 * @return boolean
	 */
	function hasColumn($name) {
		if(!$this->colsLoaded) $this->initColumns();
		return isset($this->columns[$name]);
	}

	/**
	 * Get array of columns for this table.
	 * @return array ColumnInfo[]
	 */
	function getColumns() {
		if(!$this->colsLoaded) $this->initColumns();
		return array_values($this->columns); // re-key numerically
	}

	/**
	 * Get specified fk for this table.
	 * @param string $name The foreign key name to retrieve.
	 * @return ForeignKeyInfo
	 * @throws SQLException - if fkey does not exist for this table.
	 */
	function getForeignKey($name) {
		if(!$this->fksLoaded) $this->initForeignKeys();
		if (!isset($this->foreignKeys[$name])) {
			throw new SQLException("Table `".$this->name."` has no foreign key `".$name."`");
		}
		return $this->foreignKeys[$name];
	}

	/**
	 * Get all foreign keys.
	 * @return array ForeignKeyInfo[]
	 */
	function getForeignKeys() {
		if(!$this->fksLoaded) $this->initForeignKeys();
		return array_values($this->foreignKeys);
	}

	/**
	 * Gets the IndexInfo object for a specified index.
	 * @param string $name The index name to retrieve.
	 * @return IndexInfo
	 * @throws SQLException - if index does not exist for this table.
	 */
	function getIndex($name) {
		if(!$this->indexesLoaded) $this->initIndexes();
		if (!isset($this->indexes[$name])) {
			throw new SQLException("Table `".$this->name."` has no index `".$name."`");
		}
		return $this->indexes[$name];
	}

	/**
	 * Get array of IndexInfo objects for this table.
	 * @return array IndexInfo[]
	 */
	function getIndexes() {
		if(!$this->indexesLoaded) $this->initIndexes();
		return array_values($this->indexes);
	}

	/**
	 * Alias for getIndexes() method.
	 * @return array
	 */
	function getIndices() {
		return $this->getIndexes();
	}

	/**
	 * Get table name.
	 * @return string
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	function toString() {
		return $this->name;
	}

	/** Have foreign keys been loaded? */
	function foreignKeysLoaded() {
		return $this->fksLoaded;
	}

	/** Has primary key info been loaded? */
	function primaryKeyLoaded() {
		return $this->pkLoaded;
	}

	/** Have columns been loaded? */
	function columnsLoaded() {
		return $this->colsLoaded;
	}

	/** Has index information been loaded? */
	function indexesLoaded() {
		return $this->indexesLoaded;
	}

	/**
	 * Get vendor specific optional information for this table.
	 * @return array vendorSpecificInfo[]
	 */
	function getVendorSpecificInfo() {
		if(!$this->vendorLoaded) $this->initVendorSpecificInfo();
		return $this->vendorSpecificInfo;
	}

	/** Adds a column to this table. */
	function addColumn(ColumnInfo $column) {
		$this->columns[$column->getName()] = $column;
	}

	/**
	 * Get the parent DatabaseInfo object.
	 * @return DatabaseInfo
	 */
	function getDatabase() {
		return $this->database;
	}

}