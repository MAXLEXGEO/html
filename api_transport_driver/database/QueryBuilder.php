<?php

/**
 * Manage queries in databese
 */

class QueryBuilder
{
    //TODO: REFACTOR!!! Change the structure of the code to make it easier to modify in the future.
    protected $pdo;
    protected $prefix     = null;
    protected $select     = '*';
    protected $from       = null;
    protected $where      = null;
    protected $limit      = null;
    protected $join       = null;
    protected $orderBy    = null;
    protected $groupBy    = null;
    protected $having     = null;
    protected $grouped    = false;
    protected $numRows    = 0;
    protected $insertId   = null;
    protected $query      = null;
    protected $error      = null;
    protected $result     = [];
    protected $op         = ['=', "!=", '<', '>', "<=", ">=", "<>"];
    protected $cache      = null;
    protected $cacheDir   = null;
    protected $queryCount = 0;

    /**
     * New QueryBuilder instance
     * @paqm PDO $pdo Connection with PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table($table)
    {
        $this->from = (is_array($table))
            ? implode(', ', array_map(
                function($value) {
                    return $this->prefix . $value;
                }, $table))
            : $this->prefix . $table;

        return $this;
    }

    public function select($fields = '*')
    {
        $select = (is_array($fields)) ? implode(', ', $fields) : $fields;
        $this->select = ($this->select == '*' ? $select : $this->select . ', ' . $select);

        return $this;
    }

    public function max($field, $name = null)
    {
        $func = $this->buildFunction('max', $field, $name);
        $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

        return $this;
    }

    public function min($field, $name = null)
    {
        $func = $this->buildFunction('min', $field, $name);
        $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

        return $this;
    }

    public function sum($field, $name = null)
    {
        $func = $this->buildFunction('sum', $field, $name);
        $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

        return $this;
    }

    public function count($field, $name = null)
    {
        $func = $this->buildFunction('count', $field, $name);
        $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

        return $this;
    }

    public function avg($field, $name = null)
    {
        $func = $this->buildFunction('avg', $field, $name);
        $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

        return $this;
    }

    private function buildFunction($fnName, $field, $name = null)
    {
        return strtoupper($fnName) . '(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
    }

    public function join($table, $field1 = null, $op = null, $field2 = null, $type = '')
    {
        $on = $field1;
        $table = $this->prefix . $table;

        if (!is_null($op)) {
            $on = (!in_array($op, $this->op)
                ? $this->prefix . $field1 . " = " . $this->prefix . $op
                : $this->prefix . $field1 . ' ' . $op . ' ' . $this->prefix . $field2);
        }

        if (is_null($this->join)) {
            $this->join = ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on;
        } else {
            $this->join = $this->join . ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on;
        }

        return $this;
    }

    public function innerJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'INNER ');

        return $this;
    }

    public function leftJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'LEFT ');

        return $this;
    }

    public function rightJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'RIGHT ');

        return $this;
    }

    public function fullOuterJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'FULL OUTER ');

        return $this;
    }

    public function leftOuterJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'LEFT OUTER ');

        return $this;
    }

    public function rightOuterJoin($table, $field1, $op = '', $field2 = '')
    {
        $this->join($table, $field1, $op, $field2, 'RIGHT OUTER ');

        return $this;
    }

    public function where($where, $op = null, $val = null, $type = '', $and_or = 'AND')
    {
        if (is_array($where)) {
            $_where = [];

            foreach ($where as $column => $data) {
                $_where[] = $type . $column . '=' . $this->escape($data);
            }

            $where = implode(' ' . $and_or . ' ', $_where);
        } else {
            if(is_array($op)) {
                $x = explode('?', $where);
                $w = '';
                foreach($x as $k => $v) {
                    if(!empty($v)) {
                        $w .= $type . $v . (isset($op[$k]) ? $this->escape($op[$k]) : '');
                    }
                }
                $where = $w;
            } elseif (!in_array($op, $this->op) || $op == false) {
                $where = $type . $where . " = " . $this->escape($op);
            } else {
                $where = $type . $where . ' ' . $op . ' ' . $this->escape($val);
            }
        }

        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = (is_null($this->where))
            ? $where
            : $this->where . ' ' . $and_or . ' ' . $where;

        return $this;
    }

    public function orWhere($where, $op = null, $val = null)
    {
        $this->where($where, $op, $val, '', 'OR');

        return $this;
    }

    public function notWhere($where, $op = null, $val = null)
    {
        $this->where($where, $op, $val, 'NOT ', 'AND');

        return $this;
    }

    public function orNotWhere($where, $op = null, $val = null)
    {
        $this->where($where, $op, $val, 'NOT ', 'OR');

        return $this;
    }

    public function grouped(Closure $obj)
    {
        $this->grouped = true;
        call_user_func_array($obj, [$this]);
        $this->where .= ')';

        return $this;
    }

    public function in($field, array $keys, $type = '', $and_or = 'AND')
    {
        if (is_array($keys)) {
            $_keys = [];

            foreach ($keys as $k => $v) {
                $_keys[] = (is_numeric($v) ? $v : $this->escape($v));
            }

            $keys = implode(', ', $_keys);

            $where = $field . ' ' . $type . 'IN (' . $keys . ')';

            if ($this->grouped) {
                $where = '(' . $where;
                $this->grouped = false;
            }

            $this->where = (is_null($this->where))
                ? $where
                : $this->where . ' ' . $and_or . ' ' . $where;
        }

        return $this;
    }

    public function notIn($field, array $keys)
    {
        $this->in($field, $keys, 'NOT ', 'AND');

        return $this;
    }

    public function orIn($field, array $keys)
    {
        $this->in($field, $keys, '', 'OR');

        return $this;
    }

    public function orNotIn($field, array $keys)
    {
        $this->in($field, $keys, 'NOT ', 'OR');

        return $this;
    }

    public function between($field, $value1, $value2, $type = '', $and_or = 'AND')
    {
        $where = $field . ' ' . $type . 'BETWEEN ' . $this->escape($value1) . ' AND ' . $this->escape($value2);
        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = (is_null($this->where))
            ? $where
            : $this->where . ' ' . $and_or . ' ' . $where;

        return $this;
    }

    public function notBetween($field, $value1, $value2)
    {
        $this->between($field, $value1, $value2, 'NOT ', 'AND');

        return $this;
    }

    public function orBetween($field, $value1, $value2)
    {
        $this->between($field, $value1, $value2, '', 'OR');

        return $this;
    }

    public function orNotBetween($field, $value1, $value2)
    {
        $this->between($field, $value1, $value2, 'NOT ', 'OR');

        return $this;
    }

    public function like($field, $data, $type = '', $and_or = 'AND')
    {
        $like = $this->escape($data);
        $where = $field . ' ' . $type . 'LIKE ' . $like;

        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = (is_null($this->where))
            ? $where
            : $this->where . ' ' . $and_or . ' ' . $where;

        return $this;
    }

    public function orLike($field, $data)
    {
        $this->like($field, $data, '', 'OR');

        return $this;
    }

    public function notLike($field, $data)
    {
        $this->like($field, $data, 'NOT ', 'AND');

        return $this;
    }

    public function orNotLike($field, $data)
    {
        $this->like($field, $data, 'NOT ', 'OR');

        return $this;
    }

    public function limit($limit, $limitEnd = null)
    {
        $this->limit = (!is_null($limitEnd))
            ? $limit . ', ' . $limitEnd
            : $limit;

        return $this;
    }

    public function orderBy($orderBy, $order_dir = null)
    {
        if (!is_null($order_dir)) {
            $this->orderBy = $orderBy . ' ' . strtoupper($order_dir);
        } else {
            $this->orderBy = (stristr($orderBy, ' ') || $orderBy == 'rand()')
                ? $orderBy
                : $orderBy . ' ASC';
        }

        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->groupBy = (is_array($groupBy))
            ? implode(", ", $groupBy)
            : $groupBy;

        return $this;
    }

    public function having($field, $op = null, $val = null)
    {
        if (is_array($op)) {
            $x = explode('?', $field);
            $w = '';

            foreach($x as $k => $v) {
                if(!empty($v)) {
                    $w .= $v . (isset($op[$k]) ? $this->escape($op[$k]) : '');
                }
            }

            $this->having = $w;
        } elseif (!in_array($op, $this->op)) {
            $this->having = $field . ' > ' . $this->escape($op);
        } else {
            $this->having = $field . ' ' . $op . ' ' . $this->escape($val);
        }

        return $this;
    }

    public function numRows()
    {
        return $this->numRows;
    }

    public function insertId()
    {
        return $this->insertId;
    }

    public function error()
    {
        $msg = '<pre>';
        $msg .= '<h1>Database Error</h1>';
        $msg .= '<h4>Query: <em style="font-weight:normal;">"'.$this->query.'"</em></h4>';
        $msg .= '<h4>Error: <em style="font-weight:normal; color: #ff1744;">'.$this->error.'</em></h4>';
        $msg .= '</pre>';
        die($msg);
    }

    public function get($type = false)
    {
        $this->limit = 1;
        $query = $this->getAll(true);

        if ($type == true) {
            return $query;
        }

        return $this->query( $query, false, (($type == "array") ? true : false) );
    }

    //TODO: There are a lot of if's in this method, it's so ugly
    public function getAll($type = false)
    {
        $query = 'SELECT ' . $this->select . ' FROM ' . $this->from;

        if (!is_null($this->join))
            $query .= $this->join;

        if (!is_null($this->where))
            $query .= ' WHERE ' . $this->where;

        if (!is_null($this->groupBy))
            $query .= ' GROUP BY ' . $this->groupBy;

        if (!is_null($this->having))
            $query .= ' HAVING ' . $this->having;

        if (!is_null($this->orderBy))
            $query .= ' ORDER BY ' . $this->orderBy;

        if (!is_null($this->limit))
            $query .= ' LIMIT ' . $this->limit;

        if($type == true) {
            return $query;
        }

        return $this->query( $query, true, (($type == "array") ? true : false) );
    }

    public function insert($data)
    {
        $column = implode(', ', array_keys($data));
        $values = implode(', ', array_map([$this, "escape"], $data));
        $query  = 'INSERT INTO ' . $this->from . ' (' . $column . ') VALUES (' . $values . ')';
        $query  = $this->query($query);

        if ($query) {
            $this->insertId = $this->pdo->lastInsertId();

            return $this->insertId();
        }

        return false;
    }

    //TODO: There are a lot of if's in this method, it's so ugly
    public function update($data)
    {
        $query = 'UPDATE ' . $this->from . ' SET ';
        $values = [];

        foreach ($data as $column => $value) {

            if($value == 'NULL'){
                $values[] = $column . '=' . 'NULL';
            }else{
                $values[] = $column . '=' . $this->escape($value);
            }
        }

        $query .= (is_array($data) ? implode(', ', $values) : $data);

        if (!is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        if (!is_null($this->orderBy)) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }

        return $this->query($query);
    }

    //TODO: Remove if's there are a lot of if's in this method, it's so ugly
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->from;

        if (!is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        if (!is_null($this->orderBy)) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }

        if($query == 'DELETE FROM ' . $this->from) {
            $query = 'TRUNCATE TABLE ' . $this->from;
        }

        return $this->query($query);
    }

    //TODO: Implement functionallity for "optimize", "check", "repair", "checksum", "analyze"
    //Maybe this class could optimize the database and avoid information inconsistencies (good feature)
    public function query($query, $all = true, $array = false)
    {
        $this->reset();

        if (is_array($all)) {
            $x = explode('?', $query);
            $q = '';

            foreach($x as $k => $v) {
                if(!empty($v)) {
                    $q .= $v . (isset($all[$k]) ? $this->escape($all[$k]) : '');
                }
            }

            $query = $q;
        }

        $this->query = preg_replace("/\s\s+|\t\t+/", ' ', trim($query));
        $str = false;

        foreach (["select", "optimize", "check", "repair", "checksum", "analyze"] as $value) {
            if (stripos($this->query, $value) === 0) {
                $str = true;
                break;
            }
        }

        $cache = false;

        if (!is_null($this->cache)) {
            $cache = $this->cache->getCache($this->query, $array);
        }

        if (!$cache && $str) {
            $sql = $this->pdo->query($this->query);

            if ($sql) {
                $this->numRows = $sql->rowCount();

                if (($this->numRows > 0)) {
                    if ($all) {
                        $q = [];
                        while ($result = ($array == false) ? $sql->fetchAll(PDO::FETCH_OBJ) : $sql->fetchAll(PDO::FETCH_ASSOC)) {
                            $q[] = $result;
                        }
                        $this->result = $q[0];
                    } else {
                        $q = ($array == false) ? $sql->fetch(PDO::FETCH_OBJ) : $sql->fetch(PDO::FETCH_ASSOC);
                        $this->result = $q;
                    }
                }

                if (!is_null($this->cache)) {
                    $this->cache->setCache($this->query, $this->result);
                }

                $this->cache = null;
            } else {
                $this->cache = null;
                $this->error = $this->pdo->errorInfo();
                $this->error = $this->error[2];

                return $this->error();
            }
        } elseif ((!$cache && !$str) || ($cache && !$str)) {
            $this->cache = null;
            $this->result = $this->pdo->exec($this->query);

            if ($this->result === false)
            {
                $this->error = $this->pdo->errorInfo();
                $this->error = $this->error[2];

                return $this->error();
            }
        } else {
            $this->cache = null;
            $this->result = $cache;
        }
        $this->queryCount++;

        return $this->result;
    }

    public function escape($data)
    {
        return (is_null($data)) ? null : $this->pdo->quote(trim($data));
    }

    //TODO: Cache the information to not make many requests to the server and return the information quickly, IN PROGRESS
    // public function cache($time)
    // {
    //     $this->cache = new Cache($this->cacheDir, $time);
    //     return $this;
    // }

    public function queryCount()
    {
        return $this->queryCount;
    }

    public function getQuery()
    {
        return $this->query;
    }

    protected function reset()
    {
        $this->select   = '*';
        $this->from     = null;
        $this->where    = null;
        $this->limit    = null;
        $this->orderBy  = null;
        $this->groupBy  = null;
        $this->having   = null;
        $this->join     = null;
        $this->grouped  = false;
        $this->numRows  = 0;
        $this->insertId = null;
        $this->query    = null;
        $this->error    = null;
        $this->result   = [];

        return;
    }
}