<?php

namespace App\Models;

use PDO;
use App\ConnectionSingleton;
use App\Database\QueryBuilder;
use App\QueryBuilderSingleton;

class Model
{
    private $table;

    /**
     * The database connection.
     *
     * @var PDO
     */
    public static PDO $db;

    public static $hidden;

    /**
     * The query builder instance.
     *
     * @var QueryBuilder
     */
    public static QueryBuilder $queryBuilder;

    /**
     * Set the database connection for the model.
     *
     * @param PDO $db The database connection to use.
     */
    public static function setDb(PDO $db)
    {
        static::$db = $db;
        static::$queryBuilder = new QueryBuilder($db);
    }

    public function __construct()
    {
        static::$db = ConnectionSingleton::getInstance()->getConnection();
        static::$queryBuilder = QueryBuilderSingleton::getInstance()->getQueryBuilder();
    }

    public function __get($name)
{
    if (in_array($name, static::$hidden)) {
        return null;
    }

    return $this->$name;
}

    /**
     * Find a record by its primary key.
     *
     * @param int $id The primary key of the record to find.
     *
     * @return mixed The found record, or null if no record was found.
     */
    public static function find(int $id)
    {
        static::$queryBuilder->select()
            ->from(static::$table)
            ->where('id', '=', $id)
            ->limit(1);

        $stmt = static::$queryBuilder->getPDOStatement();
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            return new static($result);
        } else {
            return null;
        }
    }

    /**
     * Find all records in the table.
     *
     * @return array An array of all the records in the table.
     */
    public static function all()
    {
        static::$queryBuilder->select()
            ->from(static::$table);

        $stmt = static::$queryBuilder->getPDOStatement();
        $stmt->execute();
        $results = $stmt->fetchAll();

        $objects = [];
        foreach ($results as $result) {
            $objects[] = new static($result);
        }

        return $objects;
    }

    /**
     * Insert a new record into the table.
     *
     * @param array $attributes The attributes for the new record.
     *
     * @return bool Whether the insert was successful.
     */
    public static function insert(array $attributes)
    {
        static::$queryBuilder->insert($attributes)
            ->into(static::$table);

        $stmt = static::$queryBuilder->getPDOStatement();
        return $stmt->execute();
    }

    public static function delete(int $id)
    {
        return static::$queryBuilder->delete()
            ->from(static::$table)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function count()
    {
        return static::$queryBuilder->select()
            ->count()
            ->from(static::$table)
            ->execute()
            ->fetchColumn();
    }
    public static function exists(int $id)
    {
        return static::$queryBuilder->select()
            ->count()
            ->from(static::$table)
            ->where('id', '=', $id)
            ->execute()
            ->fetchColumn() > 0;
    }

    public static function max(string $column)
    {
        return static::$queryBuilder->select()
            ->max($column)
            ->from(static::$table)
            ->execute()
            ->fetchColumn();
    }

    /**
     * Get the minimum value of a column in the table.
     *
     * @param string $column The column to get the minimum value for.
     *
     * @return mixed The minimum value of the column, or null if the table is empty.
     */
    public static function min(string $column)
    {
        
        return static::$queryBuilder->table(static::$table)->min($column);
    }

    /**
     * Get the average value of a column in the table.
     *
     * @param string $column The column to get the average value for.
     *
     * @return float The average value of the column, or null if the table is empty.
     */
    public static function avg(string $column)
    {
        return static::$queryBuilder->table(static::$table)->avg($column)->execute();
    }

    /**
     * Get the sum of the values of a column in the table.
     *
     * @param string $column The column to get the sum for.
     *
     * @return int The sum of the values of the column.
     */
    public static function sum(string $column)
    {
        return static::$queryBuilder->table(static::$table)->sum($column)->execute();
    }

    public static function where(array $conditions)
    {
        $query = static::$queryBuilder->select()->from(static::$table);

        foreach ($conditions as $condition) {
            list($column, $operator, $value) = $condition;
            $query->where($column, $operator, $value);
        }

        $results = $query->get();

        $objects = [];
        foreach ($results as $result) {
            $objects[] = new static($result);
        }

        return $objects;
    }


    public static function whereIn(string $column, array $values)
    {
        $results = static::$queryBuilder->select()->from(static::$table)->whereIn($column, $values)->get();

        $objects = [];
        foreach ($results as $result) {
            $objects[] = new static($result);
        }

        return $objects;
    }
}
