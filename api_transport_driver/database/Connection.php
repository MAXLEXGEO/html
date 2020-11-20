<?php

/**
 * Connection is a singleton implementation.
 * getConnection() returning an instance of PDO connection.
 */

class Connection
{

    /**
     * Singleton instance
     *
     * @var Connection
     */
    protected static $_instance = null;

    /**
     * Returns singleton instance of Connection
     *
     * @return Connection
     */
    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Connection();
        }

        return self::$_instance;
    }

    /**
     * Hide constructor, protected so only subclasses and self can use
     */
    protected function __construct() {}

    public function __destruct(){}

    /**
     * Return a PDO connection using the dsn and credentials provided
     *
     * @param string $dsn The DSN to the database
     * @param string $username Database username
     * @param string $password Database password
     * @return PDO connection to the database
     * @throws PDOException
     * @throws Exception
     */
    public function getConnection($dsn, $username, $password)
    {
        $conn = null;
        try {

            $conn = new \PDO($dsn, $username, $password);

            //Set common attributes
            $conn->exec("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
            $conn->exec("SET CHARACTER SET 'utf8'");
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;

        } catch (PDOException $e) {
            // throw $e;
            // die($e->getMessage());
            die('Whoops! Something went wrong... :(');
        }
        catch(Exception $e) {
            // throw $e;
            // die($e->getMessage());
            die('Whoops! Something went wrong... :(');
        }
    }

    /** PHP seems to need these stubbed to ensure true singleton **/
    public function __clone()
    {
        return false;
    }

    public function __wakeup()
    {
        return false;
    }
}