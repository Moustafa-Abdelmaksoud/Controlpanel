<?php
    class MysqlAdapter
    {
        protected $_config =array();
        protected $_link;
        protected $_result;
        /* Constructor */
        public function __construct(array $confg)
        {
            if(count($confg) !==4){
                throw new InvalidArgumentException('Invalid number of connection parameters');
            }
            $this->_config = $confg;
        }
        /* connect to MySQL */
        public function connect()
        {
            // connect only once
            if($this->_link === null){
                list($host, $user, $password, $database) = $this->_config;
                if(!$this->_link = @mysqli_connect($host, $user, $password, $database)){
                    throw new RuntimeException('Error connecting to the server : '.
                    mysqli_connect_error());
                }
                unset($host, $user, $password, $database);
            }
            return $this-> _link;
        }
        /* Excute the specified query */
        public function query($query)
        {
            if(!is_string($query) || empty($query)){
                throw new InvalidArgumentException('The specified quert is not valid');
            }
            // lazy connect to MySQL
            $this->connect();
            if(!$this->_result = mysqli_query($this->_link, $query)){
                throw new RuntimeException('Error executing the specified query '.
                $query.mysqli_error($this->_link));
            }
            return $this->_result;

        }
        /* Perform a SELECT statment */
        public function select($table, $where ='', $fields ='*', $order='', $limit = null, $offset = null)
        {
            $query = 'SELECT'. $fields . ' FROM ' . $table
                    .(($where) ? ' WHERE ' . $where : '')
                    .(($limit) ? ' LIMIT ' . $limit : '')
                    .(($offset && $limit) ? ' OFFSET ' . $offset : '')
                    .(($order) ? ' ORDER BY ' . $order : '');
            $this->query($query);
            return $this->countRows();        
        }
        /* Perform a INSERT statment */
        public function insert($table, array $data)
        {
            $fields = implode(',',array_keys($data));
            $values = implode(',',array_map(array($this, 'quoteValue'), array_values($data)));
            $query = 'INSERT INTO ' . $table . ' (' .$fields. ') ' . ' VALUES (' .$values. ')';
            $this->query($query);
            return $this->getInsertId();
        }
        /* Perform a UPDATE statment */
        public function update($table, array $data, $where = '')
        {
            $set = array();
            foreach ($data as $field => $value) {
                $set[] = $field . ' = ' . $this->quoteValue($value);
            }
            $set = implode(',', $set);
            $query = 'UPDATE ' . $table . ' SET ' . $set
                . (($where) ? ' WHERE ' . $where : '');
            $this->query($query);
            return $this->getAffectedRows();
        }
        /* Perform a DELETE statment */
        public function delete($table, $where = '')
        {
            $query = 'DELETE FROM ' . $table . 
                    (($where) ? ' WHERE ' . $where : '');
            $this->query($query);
            return $this->getAffectedRows();
        }
        /* Fetch a single row from the current result set (as an associative array) */
        public function fetch()
        {
            if ($this->_result !== null) {
                if (($row = mysqli_fetch_array($this->_result, MYSQLI_ASSOC)) === false) {
                    $this->freeResult();
                }
                return $row;
            }
            return false;
        }
        /* Fetch all rows from the current result set (as an associative array) */
        public function fetchAll()
        {
            if ($this->_result !== null) {
                if (($all = mysqli_fetch_all($this->_result, MYSQLI_ASSOC)) === false) {
                    $this->freeResult();
                }
                return $all;
            }
            return false;
        }
        /* Get the inserted id */
        public function getInsertId()
        {
            return $this->_link !== null
                ? mysqli_insert_id($this->_link) : null;
        }

        /* Get the number of rows returned by the current result set */
        public function countRows()
        {
            return $this->_result !== null
                ? mysqli_num_rows($this->_result) : 0;
        }

        /* Get the number of affected rows */
        public function getAffectedRows()
        {
            return $this->_link !== null
                ? mysqli_affected_rows($this->_link) : 0;
        }
        /* Escape the specified value */
        public function quoteValue($value)
        {
            $this->connect();
            if($value === null){
                $value = 'NULL';
            }else if(!is_numeric($value)){
                $value = "'". mysqli_real_escape_string($this->_link, $value). "'";
            }
            return $value;
        }
        /* Free up the current result set */
        public function freeResult()
        {
            if ($this->_result === null) {
                return false;
            }
            mysqli_free_result($this->_result);
            return true;
        }

        /* Close explicitly the database connection */
        public function disconnect()
        {
            if ($this->_link === null) {
                return false;
            }
            mysqli_close($this->_link);
            $this->_link = null;
            return true;
        }

        /* Close automatically the database connection when the instance of the class is destroyed */
        public function __destruct()
        {
            $this->disconnect();
        }





    }
?>    