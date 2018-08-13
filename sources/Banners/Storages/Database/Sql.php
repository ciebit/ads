<?php
namespace Ciebit\Ads\Banners\Storages\Database;

use Ciebit\Ads\Banners\Banner;
use Ciebit\Ads\Banners\Collection;
use Ciebit\Ads\Banners\Builders\FromArray as BuilderFromArray;
use Ciebit\Ads\Banners\Storages\Storage;
use Ciebit\Ads\Formats\Storages\Storage as FormatStorage;
use Ciebit\Ads\Links\Builders\Builder as LinkBuilder;
use Ciebit\Ads\Storages\Database\SqlHelper;
use Ciebit\Files\Storages\Storage as FileStorage;
use Exception;
use PDO;

use function array_column;
use function array_unique;

class Sql extends SqlHelper implements Storage
{
    private $pdo; #: PDO
    private $fileStorage; #: FileStorage
    private $formatStorage; #: FormatStorage
    private $linkBuilder; #: LinkBuilder
    private $table; #: string
    private $tableAssociation; #: string

    public function __construct(PDO $pdo, FileStorage $file, FormatStorage $format, LinkBuilder $link)
    {
        $this->fileStorage = $file;
        $this->formatStorage = $format;
        $this->linkBuilder = $link;
        $this->pdo = $pdo;
        $this->table = 'cb_ads_banners';
        $this->tableAssociation = 'cb_ads_association-banners';
    }

    public function addFilterByAdId(int $id, string $operator = '='): self
    {
        $key = 'ad_id';
        $aliasAssociation = 'ad_association';
        $sql = "`{$aliasAssociation}`.`ad_id` $operator :{$key}";

        $this
        ->addSqlUnion(
            "INNER JOIN `{$this->tableAssociation}` AS `$aliasAssociation`
            ON `$aliasAssociation`.`banner_id` = `banners`.`id`"
        )->addFilter($key, $sql, PDO::PARAM_INT, $id);

        return $this;
    }

    public function addFilterById(int $id, string $operator = '='): self
    {
        $key = 'id';
        $sql = "`banners`.`id` $operator :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_INT, $id);
        return $this;
    }

    public function delete(Banner $banner): Storage
    {
        $banner->setStatus(Status::TRASH());
        $this->update($banner);

        return $this;
    }

    public function destroy(Banner $banner): Storage
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM `{$this->table}`
            WHERE `id` = :id
            LIMIT 1"
        );

        $statement->bindValue('id', $banner->getId());

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.banners.storages.database.error-destory', 1);
        }

        unset($banner);

        return $this;
    }

    public function get(): ?Banner
    {
        $statement = $this->pdo->prepare(
            "SELECT
            {$this->getFields()}
            FROM `{$this->table}` AS `banners`
            {$this->generateSqlUnion()}
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            LIMIT 0,1"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.banners.storages.database.error-get', 2);
        }

        if ($statement->rowCount() == 0) {
            return null;
        }

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        $data['file'] = $this->fileStorage->addFilterById($data['file_id'])->get();
        $data['format'] = $this->formatStorage->addFilterById($data['format_id'])->get();
        $data['link'] = $this->linkBuilder
        ->setHref($data['link_href'])
        ->setTarget($data['link_target'])
        ->build();

        return (new BuilderFromArray)->setData($data)->build();
    }

    public function getAll(): Collection
    {
        $statement = $this->pdo->prepare(
            "SELECT
            {$this->getFields()}
            FROM `{$this->table}` AS `banners`
            {$this->generateSqlUnion()}
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlOrder()}
            {$this->generateSqlLimit()}"
        );

        $this->bind($statement);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.banners.storages.database.error-get-all', 3);
        }

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $filesIds = array_unique(array_column($data, 'file_id'));
        if ($filesIds) {
            $this->populateFiles($data, $filesIds);
        }

        $formatsIds = array_unique(array_column($data, 'format_id'));
        if ($formatsIds) {
            $this->populateFormats($data, $formatsIds);
        }

        $collection = new Collection;
        $builder = new BuilderFromArray;

        foreach ($data as $banner) {
            $banner['link'] = $this->linkBuilder
            ->setHref($banner['link_href'])
            ->setTarget($banner['link_target'])
            ->build();

            $collection->add(
                $builder->setData($banner)->build()
            );
        }

        return $collection;
    }

    private function getFields(): string
    {
        return '
            `banners`.`id`,
            `banners`.`file_id`,
            `banners`.`format_id`,
            `banners`.`link_href`,
            `banners`.`link_target`,
            `banners`.`views`,
            `banners`.`date_start`,
            `banners`.`date_end`,
            `banners`.`status`
        ';
    }

    private function populateFiles(array &$data, array $ids): self
    {
        $storage = clone $this->fileStorage;

        $storage->addFilterByIds('=', ...$ids);
        $files = $storage->getAll();

        foreach ($data as $i => $banner) {
            $data[$i]['file'] = $files->getById($data[$i]['file_id']);
        }

        return $this;
    }

    private function populateFormats(array &$data, array $ids): self
    {
        $storage = clone $this->formatStorage;
        $storage->addFilterByIds($ids);

        $formats = $storage->getAll();

        foreach ($data as $i => $banner) {
            $data[$i]['format'] = $formats->getById($data[$i]['format_id']);
        }

        return $this;
    }

    public function setTable(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    public function store(Banner $banner): Storage
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO `{$this->table}` (
                `file_id`, `format_id`,
                `link_href`, `link_target`,
                `views`, `date_start`,
                `date_end`, `status`
            ) VALUES (
                :file_id, :format_id,
                :link_href, :link_target,
                :views, :date_start,
                :date_end, :status
            )"
        );

        $statement->bindValue(':file_id', $banner->getFile()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':format_id', $banner->getFormat()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':link_href', $banner->getLink()->getHref(), PDO::PARAM_STR);
        $statement->bindValue(':link_target', $banner->getLink()->getTarget(), PDO::PARAM_STR);
        $statement->bindValue(':views', $banner->getViews(), PDO::PARAM_STR);
        $statement->bindValue(':date_end', $banner->getDateEnd()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':date_start', $banner->getDateStart()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':status', $banner->getStatus(), PDO::PARAM_INT);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.banners.storages.database.error-store', 3);
        }

        $banner->setId($this->pdo->lastInsertId());

        return $this;
    }

    public function update(Banner $banner): Storage
    {
        $statement = $this->pdo->prepare(
            "UPDATE `{$this->table}`
            SET
                `file_id` = :file_id,
                `format_id` = :format_id,
                `link_href` = :link_href,
                `link_target` = :link_target,
                `views` = :views,
                `date_start` = :date_start,
                `date_end` = :date_end,
                `status = :status`
            WHERE `id` = :id
            LIMIT 1
            "
        );

        $statement->bindValue(':id', $Banner->getId(), PDO::PARAM_INT);
        $statement->bindValue(':file_id', $Banner->getFile()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':format_id', $Banner->getFormat()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':link_href', $Banner->getLink()->getHref(), PDO::PARAM_STR);
        $statement->bindValue(':link_target', $Banner->getLink()->getTarget(), PDO::PARAM_STR);
        $statement->bindValue(':views', $Banner->getViews(), PDO::PARAM_STR);
        $statement->bindValue(':date_end', $Banner->getDateStart()->format('Y-m-d'), PDO::PARAM_STR);
        $statement->bindValue(':date_start', $Banner->getDateEnd()->format('Y-m-d'), PDO::PARAM_STR);
        $statement->bindValue(':status', $Banner->getStatus(), PDO::PARAM_INT);

        if ($statement->execute() == false) {
            throw new Exception('ciebit.ads.banners.storages.database.error-update', 4);
        }

        return $this;
    }
}
