<?php

namespace App\Models;

use PDO;
use App\ConnectionSingleton;
use App\Database\QueryBuilder;
use App\QueryBuilderSingleton;

class Model
{
    public static $table;

    /**
     * The database connection.
     *
     * @var PDO
     */
    public static PDO $db;

    /**
     * @var object $attributes The model's attributes
     */
    protected $attributes;

    /**
     * @var array $hidden The attributes that should be hidden from the JSON and array representations of the model
     */
    protected $hidden = [];

    /**
     * The name of the primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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

    /**
     * Constructor
     *
     * @param array $attributes The model's attributes
     */
    public function __construct($attributes = null)
    {
        static::$db = ConnectionSingleton::getInstance()->getConnection();
        static::$queryBuilder = QueryBuilderSingleton::getInstance()->getQueryBuilder();

        $this->attributes = $attributes;
    }

    /**
     * Magic getter for accessing model attributes
     *
     * @param string $key The attribute to get
     * @return mixed The value of the attribute, or null if the attribute does not exist
     */
    public function __get($key)
    {
        if (isset($this->attributes->$key)) {
            return $this->attributes->$key;
        }
    }
    /**
     * Magic setter for setting model attributes
     *
     * @param string $key The attribute to set
     * @param mixed $value The value to set for the attribute
     */
    public function __set($key, $value)
    {
        $this->attributes->$key = $value;
    }

    /**
     * Returns an array representation of the model's attributes
     *
     * @return array The model's attributes, with any attributes in the $hidden array excluded
     */
    public function toArray()
    {
        $attributes = $this->attributes;
        foreach ($this->hidden as $key) {
            unset($attributes[$key]);
        }
        return $attributes;
    }

    /**
     * Returns a JSON representation of the model's attributes
     *
     * @param int $options Options for json_encode
     * @return string The JSON representation of the model's attributes, with any attributes in the $hidden array excluded
     */
    public function toJson($options = 0)
    {
        $attributes = $this->toArray();
        return json_encode($attributes, $options);
    }

    public static function getTable(): string
    {
        return static::$table;
    }


    /**
     * Returns all of the model's attributes
     *
     * @return array The model's attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
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
        static::$queryBuilder->reset();
        static::$queryBuilder->select()
            ->from(static::$table)
            ->where(static::$table . '.' . 'id', '=', $id)
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
        static::$queryBuilder->reset();
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
        static::$queryBuilder->reset();
        static::$queryBuilder->insert($attributes)
            ->into(static::$table);

        $stmt = static::$queryBuilder->getPDOStatement();
        return $stmt->execute();
    }

    /**
     * Update a record in the database.
     *
     * @param array $attributes The attributes to update.
     *
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(array $attributes): bool
    {
        static::$queryBuilder->reset();
        static::$queryBuilder->update($attributes)
            ->table(static::$table)
            ->where($this->primaryKey, '=', $this->{$this->primaryKey});

        $stmt = static::$queryBuilder->getPDOStatement();
        return $stmt->execute();
    }

    public static function delete(int $id)
    {
        static::$queryBuilder->reset();
        static::$queryBuilder->delete()
            ->from(static::$table)
            ->where('id', '=', $id);

        $stmt = static::$queryBuilder->getPDOStatement();
        return $stmt->execute();
    }

    public static function count()
    {
        static::$queryBuilder->reset();
        static::$queryBuilder->select()
            ->count()
            ->from(static::$table);

        return static::$queryBuilder->fetchColumn();
    }
    public static function exists(int $id)
    {
        static::$queryBuilder->reset();
        static::$queryBuilder->select()
            ->count()
            ->from(static::$table)
            ->where('id', '=', $id);

        return static::$queryBuilder->fetchColumn() > 0;
    }

    public static function max(string $column)
    {
        static::$queryBuilder->reset();
        static::$queryBuilder->select()
            ->max($column)
            ->from(static::$table);

        return static::$queryBuilder->fetchColumn();
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
        static::$queryBuilder->reset();
        static::$queryBuilder->table(static::$table)
            ->min($column);

        return static::$queryBuilder->fetchColumn();
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
        static::$queryBuilder->reset();
        static::$queryBuilder->table(static::$table)
            ->avg($column);

        return static::$queryBuilder->fetchColumn();
    }

    public function hasMany(string $related, string $foreignKey, string $localKey): QueryBuilder
    {
        static::$queryBuilder->reset();
        $relatedModel = new $related;
        $relatedTable = $relatedModel::getTable();
        $thisTable = static::getTable();
        return static::$queryBuilder->select()
            ->from("{$relatedTable} AS r")
            ->join("{$thisTable} AS l", "r.{$foreignKey}", "=", "l.{$localKey}")
            ->where("l.{$localKey}", "=", $this->{$localKey});
    }
    public function hasManyThrough($relatedModel, $throughModel, $foreignKey = null, $throughKey = null, $localKey = null)
    {
        static::$queryBuilder->reset();
        // Set default foreign and through keys if not provided
        if ($foreignKey === null) {
            $foreignKey = strtolower((new $relatedModel)->getTable()) . '_id';
        }
        if ($throughKey === null) {
            $throughKey = strtolower((new $throughModel)->getTable()) . '_id';
        }
    
        // Get the names of the related and through tables
        $relatedTable = $relatedModel::getTable();
        $throughTable = $throughModel::getTable();
    
        // Build the query
        $query = static::$queryBuilder
            ->select()
            ->from($relatedTable)
            ->join($throughTable, $relatedTable . '.' . $foreignKey, '=', $throughTable . '.' . $throughKey)
            ->where($throughTable . '.' . $localKey, '=', $this->attributes->{$this->primaryKey});
    
        // Execute the query and fetch the results
        $stmt = $query->getPDOStatement();
        $stmt->execute();
        $results = $stmt->fetchAll();
    
        // Create an array of related model instances
        $relatedModels = [];
        foreach ($results as $result) {
            $relatedModels[] = new $relatedModel($result);
        }
    
        // Return the array of related models
        return $relatedModels;
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
        static::$queryBuilder->reset();
        static::$queryBuilder->table(static::$table)
            ->sum($column);

        return static::$queryBuilder->fetchColumn();
    }

    public static function where(array $conditions): QueryBuilder
    {
        static::$queryBuilder->reset();
        $query = static::$queryBuilder->select()->from(static::$table);

        foreach ($conditions as $condition) {
            list($column, $operator, $value) = $condition;
            $query->where($column, $operator, $value);
        }

        return $query;
    }


    public static function whereIn(string $column, array $values): QueryBuilder
    {
        static::$queryBuilder->reset();
        $results = static::$queryBuilder->select()->from(static::$table)->whereIn($column, $values);

        return $results;
    }

    /**
     * Retrieves a model object with a given attribute value, or creates a new model object with the given attribute value if one does not exist
     *
     * @param string $attribute The attribute to check
     * @param mixed $value The value to check for
     * @param array $attributes The attributes to set for the new model object if one is created
     * @return Model The model object with the given attribute value
     */
    public static function firstOrCreate($attribute, $value, $attributes = [])
    {
        static::$queryBuilder->reset();
        $model = static::$queryBuilder->from(static::$table)->where($attribute, '=', $value)->first();
        if (!$model) {
            $attributes[$attribute] = $value;
            $model = new static($attributes);
            $model->save();
        }
        return $model;
    }

    /**
     * Retrieves a model object with a given attribute value, or creates a new model object with the given attribute value if one does not exist
     *
     * @param string $attribute The attribute to check
     * @param mixed $value The value to check for
     * @param array $attributes The attributes to set for the new model object if one is created
     * @return Model The model object with the given attribute value
     */
    public static function create($attributes)
    {
        static::$queryBuilder->reset();
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    public function save()
    {
        if (isset($this->{$this->primaryKey})) {
            // If primary key is set, update record
            return static::update((array) $this->attributes);
        } else {
            // If primary key is not set, insert new record
            return static::insert((array)$this->attributes);
        }
    }

    public function get_object_vars()
    {
        return get_object_vars($this);
    }
}
