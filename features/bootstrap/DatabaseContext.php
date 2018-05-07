<?php declare(strict_types = 1);

namespace App\Behat;

use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;
use PHPUnit\Framework\Assert;
use Symfony\Bridge\Doctrine\ManagerRegistry;

/**
 * @author Alexander Miehe <alexander.miehe@tourstream.eu>
 */
class DatabaseContext extends RawMinkContext
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Given the entity manager is cleared
     */
    public function entityManagerIsCleared(): void
    {
        $this->managerRegistry->getManager()->clear();
    }

    /**
     * @param string $table
     * @param string $count
     *
     * @Then the table :table has :count element(s)
     */
    public function tableHasCount(string $table, string $count): void
    {
        /** @var Connection $conn */
        $conn = $this->managerRegistry->getConnection();

        /** @var Statement $stmn */
        $stmn = $conn->createQueryBuilder()->select('count(*)')->from($table)->execute();

        Assert::assertEquals($count, $stmn->fetch(FetchMode::COLUMN));
    }
}
