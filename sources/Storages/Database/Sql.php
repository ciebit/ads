<?php
namespace Ciebit\Ads\Storages\Database;

use Ciebit\Ads\Ad;
use Ciebit\Ads\Collection;
use Ciebit\Ads\Status;
use Ciebit\Ads\Banners\Storages\Storage as BannersStorage;
use Ciebit\Ads\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Storages\Database\SqlHelper;
use Ciebit\Ads\Storages\Storage;
use Exception;
use PDO;

class Sql extends SqlHelper implements Storage
{
    private $bannerStorage; #: BannersStorage
    private $pdo; #: pdo
    private $table; #: string
    private $tableAssociation; #: string
    private $tableBanner; #: string
    private $tableFormat; #: string

    public function __construct(PDO $pdo, BannersStorage $banner)
    {
        $this->bannerStorage = $banner;
        $this->pdo = $pdo;
        $this->table = 'cb_ads';
        $this->tableAssociation = 'cb_ads_association-banners';
        $this->tableBanner = 'cb_ads_banners';
        $this->tableFormat = 'cb_ads_formats';
    }

    public function addFilterByFormatId(int $id, string $operator = '='): Storage
    {
        $key = 'format_id';
        $formatAlias = 'format';
        $bannerAlias = 'banner';
        $associationAlias = 'association';
        $sql = "`{$formatAlias}`.`id` $operator :{$key}";

        $this
        ->addSqlJoin(
            "INNER JOIN `{$this->tableAssociation}` AS `{$associationAlias}`
            ON `{$associationAlias}`.`ad_id` = `{$this->table}`.`id`"
        )
        ->addSqlJoin(
            "INNER JOIN `{$this->tableBanner}` AS `{$bannerAlias}`
            ON `{$bannerAlias}`.`id` = `{$associationAlias}`.`banner_id`"
        )
        ->addSqlJoin(
            "INNER JOIN `{$this->tableFormat}` AS `{$formatAlias}`
            ON `{$formatAlias}`.`id` = `{$bannerAlias}`.`format_id`"
        )
        ->addFilter($key, $sql, PDO::PARAM_INT, $id);

        return $this;
    }

    public function addFilterById(int $id, string $operator = '='): Storage
    {
        $key = 'id';
        $sql = "`id` $operator :{$key}";

        $this->addFilter($key, $sql, PDO::PARAM_INT, $id);
        return $this;
    }

    public function addFilterByStatus(Status $status, string $operator = '='): Storage
    {
        $key = 'status';
        $sql = "`status` $operator :{$key}";

        $this->addFilter($key, $sql, PDO::PARAM_INT, $status->getValue());
        return $this;
    }

    public function delete(Ad $ad): Storage
    {
        $ad->setStatus(Status::TRASH());
        $this->update($ad);

        return $this;
    }

    public function destroy(Ad $ad): Storage
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM `{$this->table}`
            WHERE `id` = :id
            LIMIT 1'
        );

        $statement->bindParam('id', $ad->getId());

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.storages.database.erro-destroy', 1);
        }

        unset($ad);

        return $this;
    }

    private function getFields(): string
    {
        return "
            `{$this->table}`.`id`,
            `{$this->table}`.`name`,
            `{$this->table}`.`date_start`,
            `{$this->table}`.`date_end`,
            `{$this->table}`.`status`
        ";
    }

    public function get(): ?Ad
    {
        $statement = $this->pdo->prepare(
            "SELECT
            {$this->getFields()}
            FROM `{$this->table}`
            {$this->generateSqlJoin()}
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            LIMIT 0,1"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.storages.database.erro-get', 2);
        }

        if ($statement->rowCount() == 0) {
            return null;
        }

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $bannersStorage = clone $this->bannerStorage;
        $bannersStorage->addFilterByAdId($data['id']);
        $data['banners'] = $bannersStorage->getAll();

        return (new BuilderFromArray)->setData($data)->build();
    }

    public function getAll(): Collection
    {
        $statement = $this->pdo->prepare(
            "SELECT
            {$this->getFields()}
            FROM `{$this->table}`
            {$this->generateSqlJoin()}
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            {$this->generateSqlLimit()}"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.storages.database.erro-get-all', 3);
        }

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $filesIds = array_unique(array_column($data, 'file_id'));
    }

    public function setTable(string $table): self
    {
        $this->table = $name;
        return $this;
    }

    public function store(Ad $Ad): Storage
    {
        $Connection = $this->Connection->conectar();

        $statement = $Connection->prepare(
            "INSERT INTO `cb-ad` (
                `name`, `status`,
                `date_start`, `date_end`
            ) VALUES (
                :name, :status,
                :date_start, :date_end
            )"
        );

        $statement->bindValue(':date_end', $Ad->getDateEnd()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':date_start', $Ad->getDateStart()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':name', $Ad->getName(), PDO::PARAM_STR);
        $statement->bindValue(':status', $Ad->getStatus(), PDO::PARAM_INT);

        if (! $statement->execute()) {
            throw MessageFactory::criar('ads', 'storage_store_error', $statement->errorInfo());
        }
        $id = $Connection->lastInsertId();
        $Ad->setId($id);

        return $this;
    }

    public function update(Ad $Ad): Storage
    {
        $Connection = $this->Connection->conectar();

        $statement = $Connection->prepare(
            "UPDATE `cb-ad`
            SET
                `name` = :name,
                `status` = :status,
                `date_start` = :date_start,
                `date_end` = :date_end
            WHERE `id` = :id
            LIMIT 1
            "
        );

        $statement->bindValue(':date_end', $Ad->getDateStart()->format('Y-m-d'), PDO::PARAM_STR);
        $statement->bindValue(':date_start', $Ad->getDateEnd()->format('Y-m-d'), PDO::PARAM_STR);
        $statement->bindValue(':id', $Ad->getId(), PDO::PARAM_INT);
        $statement->bindValue(':name', $Ad->getName(), PDO::PARAM_STR);
        $statement->bindValue(':status', $Ad->getStatus(), PDO::PARAM_INT);

        if (! $statement->execute()) {
            throw MessageFactory::criar('ads', 'storage_update_error', $statement->errorInfo());
        }

        return $this;
    }
}
