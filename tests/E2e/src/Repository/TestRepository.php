<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TestEntity;
use Doctrine\ORM\EntityRepository;

final class TestRepository extends EntityRepository
{
    public function findByText(string $text): ?TestEntity
    {
        return $this->findOneBy(['text' => $text]);
    }
}
