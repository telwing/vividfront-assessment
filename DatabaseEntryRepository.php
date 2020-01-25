<?php

class DatabaseEntryRepository
{
    const ADD_ACTION = 'add';
    const DELETE_ACTION = 'delete';

    public static $states = [
        'MI',
        'OH',
        'PA'
    ];

    protected $connection;

    protected $table = 'entries';

    protected $fields = [
        'id' => PDO::PARAM_INT,
        'name' => PDO::PARAM_STR,
        'email' => PDO::PARAM_STR,
        'state' => PDO::PARAM_STR,
        'interested' => PDO::PARAM_INT,
    ];

    public function findAll()
    {
        $query = $this->getFindAllQuery();

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function create(array $payload)
    {
        $fields = $this->getCreateFields();
        $data = $this->filterPayload($payload, $fields);
        $query = $this->getCreateQuery();

        foreach ($fields as $field => $dataType) {
            if (
                method_exists($this, "validate{$field}")
                && ! $this->{"validate{$field}"}($data[$field])
            ) {
                throw new Exception("Invalid data for field: {$field}");
            }

            if (! $query->bindParam($field, $data[$field], $dataType)) {
                throw new Exception("Failed to bind :{$field} param");
            }
        }

        $result = $query->execute();

        if (false === $result) {
            throw new Exception('Create failed: query failed to execute');
        }
    }

    public function delete($id)
    {
        $fields = $this->getDeleteFields();
        $data = $this->filterPayload(compact('id'), $fields);
        $query = $this->getDeleteQuery();

        foreach ($fields as $field => $dataType) {
            if (! $query->bindParam($field, $data[$field], $dataType)) {
                throw new Exception("Failed to bind :{$field} param");
            }
        }

        $result = $query->execute();

        if (false === $result) {
            throw new Exception('Delete failed: query failed to execute');
        }
    }

    public function getConnection()
    {
        if (! isset($this->connection)) {
            $this->connection = new PDO(
                'mysql:dbname=vividfront;host=127.0.0.1;charset=UTF8',
                'root',
                ''
            );
        }

        return $this->connection;
    }

    protected function getFindAllQuery()
    {
        return $this->getConnection()->prepare(
            "SELECT * FROM {$this->table}"
        );
    }

    protected function getCreateQuery()
    {        
        return $this->getConnection()->prepare(
            "INSERT INTO {$this->table}(name, email, state, interested) VALUES(:name, :email, :state, :interested)"
        );
    }

    protected function getDeleteQuery()
    {
        return $this->getConnection()->prepare(
            "DELETE FROM {$this->table} WHERE id = :id"
        );
    }

    protected function getCreateFields()
    {
        $fields = $this->fields;

        unset($fields['id']);

        return $fields;
    }

    protected function getDeleteFields()
    {
        return ['id' => $this->fields['id']];
    }

    protected function filterPayload(array $payload = [], array $baseFields = null)
    {
        $fields = array_fill_keys(array_keys($baseFields ?? $this->fields), null);

        $fields['interested'] = 0;

        return array_intersect_key($payload, $fields) + $fields;
    }
    
    protected function validateName($value)
    {
        return preg_match("/^[^\d~!@#$%^&*()]+$/", $value);
    }

    protected function validateEmail($value)
    {
        return preg_match("/^[^\@]*\@[^\.]+(?=\.)/", $value);
    }

    protected function validateState($value)
    {
        return in_array($value, static::$states);
    }
}
