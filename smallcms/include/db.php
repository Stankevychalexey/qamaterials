<?php

class DB
{
    protected $_connection = null;

    public function __construct($host, $user, $password, $db) {
        $this->_connection = new Mysqli($host, $user, $password, $db);
        $this->_connection->query("SET NAMES UTF8");

        if ($this->_connection->connect_error) {
            die('Connect Error (' . $this->_connection->connect_errno . ') '
                    . $this->_connection->connect_error);
        }
    }

    public function query($sql)
    {
        $res = $this->_connection->query($sql);

        return $res;
    }

    public function get_row($sql)
    {
        if ($res = $this->query($sql)){
            $row = $res->fetch_assoc();

            return $row;
        } else {
            die(sprintf('Error: %s <br> SQL: %s <br>', $this->_connection->error, $sql));
        }
    }

    public function get_rows($sql)
    {
        $result = array();
        if ($res = $this->query($sql)){
            while($row = $res->fetch_assoc()) {
                $result[] = $row;
            }
            return $result;
        } else {
            die(sprintf('Error: %s <br> SQL: %s <br>', $this->_connection->error, $sql));
        }
    }
}
