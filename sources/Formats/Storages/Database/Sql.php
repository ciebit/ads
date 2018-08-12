<?php
namespace Ciebit\Ads\Formats\Storages\Database;

use Ciebit\Ads\Formats\Format;
use Ciebit\Ads\Formats\Collection;
use Ciebit\Ads\Formats\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Formats\Storages\Storage;
use Ciebit\Ads\Storages\Database\SqlHelper;
use Exception;
use PDO;

class Sql extends SqlHelper implements Storage
{
    static private $counterKey = 0;
    private $pdo; #: Pdo
    private $table; #: string

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'cb_ads-formats';
    }

    public function addFilterById(int $id, string $operator = '='): Storage
    {
        $key = 'id';
        $sql = "`id` $operator :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_INT, $id);
        return $this;
    }

    public function addFilterByIds(array $ids, string $operator = '='): self
    {
        $keyPrefix = 'id';
        $keys = [];

        foreach ($ids as $id) {
            $key = $keyPrefix.self::$counterKey++;
            $this->addBind($key, PDO::PARAM_INT, $id);
            $keys[] = $key;
        }

        $keysStr = implode(', :', $keys);
        $this->addSqlFilter("`id` IN (:{$keysStr})");

        return $this;
    }

    public function delete(Format $format): self
    {
        $format->setStatus(Status::TRASH());
        $this->update($format);

        return $this;
    }

    public function destroy(Format $format): self
    {
        $statement = $this->pdo->prepare("
            DELETE FROM `{$this->table}`
            WHERE `id` = :id
            LIMIT 1
        ");

        $statement->bindValue('id', $format->getId());

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.formats.storages.database.error-destory', 1);
        }

        unset($format);

        return $this;
    }

    public function get(): ?Format
    {
        $statement = $this->pdo->prepare(
            "SELECT
            {$this->getFields()}
            FROM `{$this->table}`
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            LIMIT 0,1"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.formats.storages.database.error-get', 3);
        }

        if ($statement->rowCount() == 0) {
            return null;
        }

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return (new BuilderFromArray)->setData($data)->build();
    }

    public function getAll(): Collection
    {
        $statement = $this->pdo->prepare(
            "SELECT SQL_CALC_FOUND_ROWS
            {$this->getFields()}
            FROM `{$this->table}`
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            {$this->generateSqlLimit()}"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.formats.storages.database.error-get-collection', 2);
        }

        $collection = new Collection;
        $builder = new BuilderFromArray;

        while ($ad = $statement->fetch(PDO::FETCH_ASSOC)) {
            $collection->add($builder->setData($ad)->build());
        }

        return $collection;
    }

    public function store(Format $format): Store
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO `{$this->table}` (
                `name`, `width`,
                `height`, `status`
            ) VALUES (
                :name, :width,
                :height, :status
            )"
        );

        $statement->bindValue(':name', $format->getName(), PDO::PARAM_STR);
        $statement->bindValue(':width', $format->getWidth(), PDO::PARAM_INT);
        $statement->bindValue(':height', $format->getHeight(), PDO::PARAM_INT);
        $statement->bindValue(':status', $format->getStatus(), PDO::PARAM_INT);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.formats.storages.database.error-store', 4);
        }

        $id = $this->pdo->lastInsertId();
        $format->setId($id);

        return $this;
    }

    public function update(Format $format): self
    {
        $Connection = $this->Connection->conectar();

        $statement = $Connection->prepare(
            "UPDATE `{$this->table}` SET
            `name` = :name,
            `width` = :width,
            `height` = :height,
            `status` = :status
            WHERE `id` = :id
            LIMIT 1"
        );

        $statement->bindValue(':id', $Format->getId(), PDO::PARAM_INT);
        $statement->bindValue(':name', $Format->getName(), PDO::PARAM_STR);
        $statement->bindValue(':width', $Format->getWidth(), PDO::PARAM_INT);
        $statement->bindValue(':height', $Format->getHeight(), PDO::PARAM_INT);
        $statement->bindValue(':status', $Format->getStatus(), PDO::PARAM_INT);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.formats.storages.database.error-update', 5);
        }

        return $this;
    }
}
