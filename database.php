<?php

$link = mysqli_connect('localhost', 'root', '', 'logistics');
if (mysqli_connect_errno()) {
    echo 'Ошибк подключении база данюх (' . mysqli_connect_errno() . ') : ' . mysqli_connect_error();
    exit();
}

class database
{
    public static $table_name;
    public static $db_fields;
    public $id;

    protected function create_array()
    {
        $result = [];
        foreach (static::$db_fields as $value) {
            $result[$value] = $this->$value;
        }

        return $result;
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();

    }

    protected function create()
    {
        $order = $this->create_array();
        $sql = "INSERT INTO " . static::$table_name . "(" . implode(',', array_keys($order)) . ") VALUES('"
            . implode("','", array_values($order)) . "')";

        $this->get_sql_result($sql);
    }

    protected function update()
    {
        $order = $this->create_array();
        $sql = "UPDATE " . static::$table_name . " SET " . "(" . implode("','", array_values($order)) . "')" . " WHERE id = " . $this->id;
        $this->get_sql_result($sql);
    }

    public function delete()
    {
        $sql = "DELETE FROM " . static::$table_name . " WHERE id=" . $this->id;
        $this->get_sql_result($sql);
    }

    public function get_table()
    {

        $sql = "SELECT * FROM " . static::$table_name;

        return $this->get_sql_result($sql);
    }

    protected function get_sql_result($sql)
    {
        global $link;
        $result = mysqli_query($link, $sql);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $categories;
    }

    public  function clear_value($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}