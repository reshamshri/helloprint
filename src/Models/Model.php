<?php


namespace Helloprint\Models;


use Helloprint\Database\DbConnection;
use Helloprint\Exceptions\ModelException;


/**
 * Class Model
 * @package Helloprint\Models
 */
class Model extends DbConnection
{
    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * @var string
     */
    protected string $table;

    /**
     * @return $this
     */
    public function save()
    {
        return $this->exists() ? $this->update() : $this->insert();
    }

    /**
     * @return array
     * @throws ModelException
     */
    protected function find(): array
    {
        $sql = sprintf("select %s from %s where %s = ?",
            implode(',', $this->getAttributes()), $this->table, $this->getKeyName());

        $bindParam = array($this->{$this->getKeyName()});
        $this->execStatement($sql, $bindParam);

        return  $this->query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return $this
     * @throws ModelException
     */
    public function insert(): self
    {
        $sql = sprintf('INSERT INTO %s ( %s ) VALUES( %s ) RETURNING %s',
            $this->table,
            implode(',', $this->fillable),
            $this->prepareBindingKeys(),
            $this->getKeyName());

        $this->execStatement($sql, $this->prepareBindingValues());
        $result = $this->query->fetch(\PDO::FETCH_ASSOC);
        $this->{$this->getKeyName()} = $result[$this->getKeyName()];

        return $this->tap($this->find());

    }

    /**
     * @return $this
     * @throws ModelException
     */
    public function update(): self
    {
        $toUpdate = $this->toUpdate();

        $fields = array_map(function ($field) {
            return $field . '= ?';
        }, array_keys($toUpdate));

        $sql = sprintf('UPDATE %s SET %s WHERE %s = ?',
            $this->table,
            implode(',', $fields),
            $this->getKeyName());


        $this->execStatement($sql, [...array_values($toUpdate), ...[$this->{$this->getKeyName()}]]);

        return $this->tap($this->find());
    }

    /**
     * @param array $where
     * @return array
     * @throws ModelException
     */
    public function where($where = [])
    {
        $toWhere = array_map(function ($field) {
            return $field . ' = ?';
        }, array_keys($where));

        $sql = sprintf("select %s from %s where %s",
            implode(',', $this->getAttributes()), $this->table,
            implode(",", $toWhere));

        $this->execStatement($sql, array_values($where));

        return $this->query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return bool
     * @throws ModelException
     */
    protected function exists(): bool
    {
        return $this->{$this->getKeyName()} && $this->find();
    }

    /**
     * @return string
     */
    protected function getKeyName(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return array_map(function ($item) {
            return $item["column_name"];
        }, $this->getTableMeta());
    }

    /**
     * @return array
     * @throws ModelException
     */
    protected function getTableMeta(): array
    {
        $sql = "SELECT column_name
                FROM  information_schema.columns
                WHERE table_name = ?";

        $this->execStatement($sql, [$this->table]);

        return $this->query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function toUpdate(): array
    {
        $toUpdate = [];
        foreach ($this->fillable as $fieldName) {
            if (isset($this->{$fieldName})) {
                $toUpdate[$fieldName] = $this->{$fieldName};
            }
        }

        if(!empty($toUpdate))
            $toUpdate['updated_at'] = date('Y-m-d H:i:s');

        return $toUpdate;
    }

    /**
     * @return string
     */
    private function prepareBindingKeys(): string
    {
        return implode(',', array_map(fn($field) => ':' . $field, $this->fillable));
    }

    /**
     * @return array
     */
    private function prepareBindingValues(): array
    {
        return array_map(function ($field) {
            return $this->{$field} ?? null;
        }, $this->fillable);
    }

    /**
     * @param array $result
     * @return $this
     */
    private function tap(array $result): self
    {
        array_walk($result, function ($value, $key) {
            $this->{$key} = $value;
        });

        return $this;
    }
}
