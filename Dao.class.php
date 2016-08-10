<?php
/**
 * 数据访问对象模式
 * 解决问题：如何创建透明访问任何数据源的对象(重复和数据源抽象化)
 */

abstract class baseDAO
{
    private $__connection;
    public function __construct()
    {
        $this->__connectToDB(DB_USER, DB_PASS, DB_HOST, DB_DATABASE);
    }

    private function __connectToDB($user, $pass, $host, $database)
    {
        $this->__connection = mysql_connect($host, $user, $pass);
        mysql_select_db($database, $this->__connection);
    }

    public function fetch($value, $key = NULL)
    {
        if(is_null($key))
        {
            $key = $this->_primaryKey;
        }

        $sql = "select * from {$this->_tableName} where {$key}='{$value}'";
        $results = mysql_query($sql, $this->__connection);

        $rows = array();
        while ($result = mysql_fetch_array($results))
        {
            $rows[] = $result;
        }
        return $rows;
    }

    public function update($keyedArray)
    {
        $sql = "update {$this->_tableName} set ";
        foreach ($keyedArray as $column=>$value)
        {
            $updates[] = "{$column} = '{$value}' ";
        }
        $sql .= implode(",",$updates);
        $sql .= "where {$this->_primaryKey}='{$keyedArray[$this->_primaryKey]}'";
        mysql_query($sql, $this->__connection);

    }
}

class userDAO extends baseDAO
{
    protected $_tableName = "userTable";
    protected $_primaryKey = "id";

    public function getUserByFirstName($name)
    {
        $result = $this->fetch($name, 'firstName');
        return $result;
    }
}


$user = new userDAO();
$id = 1;
$userInfo = $user->fetch($id);

$updates = array('id'=>1, 'firstName'=>'arlon');
$user->update($updates);

$all = $user->getUserByFirstName('arlon');
