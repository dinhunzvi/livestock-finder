<?php 
    /*
     * database.php
     */

    class Database {

        /**
         * @var $_m_instance
         */
        private static $_m_instance;

        private $_error, $_query, $_results, $_count;
        private PDO $_database;

        /**
         * Database constructor.
         * @throws Exception
         */
        public function __construct () {
            $database_configuration = Config::get_instance()->get( 'database' );
            $dsn = 'mysql:host=' . $database_configuration['server'] . ';dbname=';
            $dsn .= $database_configuration['database'] . ';charset=' . $database_configuration['charset'];

            $database_options = Config::get_instance()->get( 'database_options' );
            try {
                $this->_database = new PDO( $dsn, $database_configuration['username'], 
                $database_configuration['password'], $database_options );
            } catch ( PDOException $exception ) {
                die( 'Could not connect to database server: ' . $exception->getMessage() );
            }
        }

        /**
         * @return Database
         */
        public static function get_instance (): Database {
            if ( self::$_m_instance === null ) {
                self::$_m_instance = new Database();
            }

            return self::$_m_instance;

        }

        /**
         * @param $sql
         * @param array $params
         * @return $this
         */
        public function query( $sql, $params = array() ) {
            $this->_error = false;
            if ( $this->_query = $this->_database->prepare( $sql ) ) {
                $x = 1;
                if ( count ( $params ) ) {
                    foreach( $params as $param ) {
                        $this->_query->bindValue( $x, $param );
                        $x++;
                    }
                }

                if ( $this->_query->execute() ) {
                    $this->_results = $this->_query->fetchAll( PDO::FETCH_OBJ );
                    $this->_count = $this->_query->rowCount();
                } else {
                    $this->_error = true;
                }
            }

            return $this;

        }

        /**
         * @param $action
         * @param $table
         * @param array $where
         * @return $this|false
         */
        public function action ( $action, $table, $where = array() ) {
            if ( count ( $where ) === 3 ) {
                $operators = array( '=', '>', '<', '<=', '>=', );

                $field = $where[0];
                $operator = $where[1];
                $value = $where[2];

                if ( in_array( $operator, $operators ) ) {
                    $sql = "{$action} from {$table} where {$field} {$operator} ?";
                    if ( !$this->query( $sql, array( $value ) )->error() ) {
                        return $this;
                    }

                }

            }

            return false;
        }

        /**
         * @param $table
         * @param $where
         * @return $this|false
         */
        public function get ( $table, $where ) {
            return $this->action( 'select *', $table, $where );
        }

        /**
         * @param $table
         * @param $where
         * @return $this|false
         */
        public function delete ( $table, $where ) {
            return $this->action( 'delete', $table, $where );
        }

        /**
         * @param $table
         * @param array $fields
         * @return bool
         */
        public function insert ( $table, $fields = array() ) {
            $keys = array_keys( $fields );
            $values = null;
            $x = 1;

            foreach( $fields as $field ) {
                $values .= '?';
                if ( $x < count ( $fields ) ) {
                    $values .= ', ';
                }
                $x++;
            }
            $sql = "insert into {$table} ( `" . implode( '`, `', $keys ) . "` ) values ( {$values} )";

            if ( !$this->query( $sql, $fields )->error() ) {
                return true;
            } 

            return false; 
            
        }

        /**
         * @param $table
         * @param array $where
         * @param array $fields
         * @return bool
         */
        public function update ( $table, $where = array(), $fields = array() ) {
            $set = '';  
            $x = 1; 

            foreach( $fields as $name => $value ) {
                $set .= "{$name} = ?";

                if ( $x < count( $fields ) ) {
                    $set .= ", ";
                }
                $x++;
            }

            if ( count( $where ) === 2 ) {
                $update_condition = $where[0];
                $update_value = $where[1];
            }

            $sql = "update {$table} set {$set} where {$update_condition} = {$update_value}";

            if ( !$this->query( $sql, $fields )->error() ) {
                return true;
            } 

            return false; 

        }

        /**
         * @return mixed
         */
        public function results () {
            return $this->_results;
        }

        /**
         * @return mixed
         */
        public function first () {
            return $this->results()[0];
        }

        /**
         * @return mixed
         */
        public function error () {
            return $this->_error;
        }

        /**
         * @return mixed
         */
        public function count () {
            return $this->_count;
        }

        /**
         * @return int
         */
        public function last_id (): int {
            return $this->_database->lastInsertId();
        }


    }