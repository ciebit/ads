<?php
namespace Ciebit\Ads\Storages\Database;

use PDOStatement;

use function implode;
use function is_array;

abstract class SqlHelper
{
    private $bindList; #:Array
    private $filtersSql; #:Array
    private $joinSql; #: array
    private $limit; #:int
    private $offset; #:int
    private $orderField; #:string
    private $orderDirection; #:string

    protected function addBind(string $key, int $type, $value): self
    {
        $this->bindList[] = [
            'key' => $key,
            'value' => $value,
            'type' => $type
        ];
        return $this;
    }

    protected function addFilter(string $key, string $sql, int $type, $value): self
    {
        $this->addBind($key, $type, $value);
        $this->addSqlFilter($sql);
        return $this;
    }

    protected function addSqlFilter(string $sql): self
    {
        $this->filtersSql[] = $sql;
        return $this;
    }

    protected function addSqlJoin(string $sql): self
    {
        $this->joinSql[] = $sql;
        return $this;
    }

    protected function bind(PDOStatement $statment): self
    {
        if (! is_array($this->bindList)) {
            return $this;
        }

        foreach ($this->bindList as $bind) {
            $statment->bindValue(":{$bind['key']}", $bind['value'], $bind['type']);
        }

        return $this;
    }

    protected function generateSqlFilters(): string
    {
        if (empty($this->filtersSql)) {
            return '1';
        }
        return implode(' AND ', $this->filtersSql);
    }

    protected function generateSqlLimit(): string
    {
        $init = (int) $this->offset;
        $sql =
            $this->limit === null
            ? ''
            : "LIMIT {$init},{$this->limit}";
        return $sql;
    }

    protected function generateSqlJoin(): string
    {
        if (! is_array($this->joinSql)) {
            return '';
        }

        return implode(' ', $this->joinSql);
    }

    protected function generateSqlOrder(): string
    {
        if ($this->orderField == null) {
            return '';
        }

        $direction = $this->orderDirection != null ?: 'ASC';

        return "ORDER BY {$this->orderField} {$direction}";
    }

    protected function setLimit(int $total): self
    {
        $this->limit = $total;
        return $this;
    }

    protected function setOffset(int $lineInit): self
    {
        $this->offset = $lineInit;
        return $this;
    }
}
