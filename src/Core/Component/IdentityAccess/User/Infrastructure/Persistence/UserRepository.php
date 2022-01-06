<?php

declare(strict_types=1);

namespace App\Core\Component\IdentityAccess\User\Infrastructure\Persistence;

use App\Core\Component\IdentityAccess\User\Domain\User;
use Cycle\ORM\Select;
use Throwable;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

final class UserRepository extends Select\Repository
{
    private EntityWriter $entityWriter;

    public function __construct(Select $select, EntityWriter $entityWriter)
    {
        $this->entityWriter = $entityWriter;
        parent::__construct($select);
    }

    public function findAll(array $scope = [], array $orderBy = []): DataReaderInterface
    {
        return new EntityReader($this->select()->where($scope)->orderBy($orderBy));
    }

    /**
     * @param string $id
     *
     * @return User|null
     */
    public function findById(string $id): ?User
    {
        return $this->findByPK($id);
    }

    public function findByLogin(string $login): ?User
    {
        return $this->findBy('login', $login);
    }

    /**
     * @throws Throwable
     */
    public function save(User $user): void
    {
        $this->entityWriter->write([$user]);
    }

    private function findBy(string $field, string $value): ?User
    {
        return $this->findOne([$field => $value]);
    }
}
