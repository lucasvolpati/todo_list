<?php

namespace Source\Core;


abstract class Model {

    /** @var object|null */
    protected $data;

    /** @var \PDOException|null */
    protected $fail;

    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @return null|object
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return \PDOException
     */
    public function fail(): ?\PDOException 
    {
        return $this->fail;
    }

    /**
     * @param string $entity
     * @param array $data
     * @return int|null
     */
    protected function create(string $entity, array $data): ?int
    {
        try {
            
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $stmt = Connect::getInstance()->prepare("INSERT INTO {$entity} ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $select
     * @param string $params
     * @return PDOStatement|null
     */
    protected function read(string $select, string $params = null): ?\PDOStatement
    {
        try {
            $stmt = Connect::getInstance()->prepare($select); 
            if ($params) {
                parse_str($params, $paramsArr);
                foreach($paramsArr as $key => $value) {
                    if ($key == 'limit' || $key == 'offset') {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_INT);
                    }else {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
                    }
                }
            }

            $stmt->execute();
            return $stmt;

        } catch (\PDOException $e) {
            $this->fail = $e;
        }
    }

    protected function update(string $entity, array $data, string $terms, string $params): ?int
    {
        try {
            $dateSet = [];
            foreach ($data as $bind => $value) {
                $dateSet[] = "{$bind} = :{$bind}";
            }
            $dateSet = implode(", ", $dateSet);
            parse_str($params, $params);

            $stmt = Connect::getInstance()->prepare("UPDATE {$entity} SET {$dateSet} WHERE {$terms}");
          
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $th) {
            $this->fail = $th;
            return null;
        }
    }

    protected function delete(string $entity, string $terms, string $id): ?int
    {
        try {
            $stmt = Connect::getInstance()->prepare("DELETE FROM {$entity} WHERE {$terms}");
            // parse_str($params, $parames);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach (static::$safe as $unset) { 
            unset($safe[$unset]);
        }
        return $safe;
    }


    /**
     * @return null|array
     */
    protected function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
        }

        return $filter;
    }

}
