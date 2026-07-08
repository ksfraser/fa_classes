<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\CrmCategoryRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CrmCategoryRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['id' => '1', 'type' => 'customer', 'action' => 'general', 'name' => 'General', 'description' => '', 'system' => '1', 'inactive' => '0']], 1);
        $repo = new CrmCategoryRepository($db);

        $result = $repo->findById(1);

        $this->assertNotNull($result);
        $this->assertSame('customer', $result->getType());
        $this->assertStringContainsString('0_crm_categories', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmCategoryRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByType(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmCategoryRepository($db);

        $results = $repo->findByType('customer');

        $this->assertIsArray($results);
        $this->assertStringContainsString('type = ?', $db->lastSql);
    }

    public function testFindByTypeAndAction(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmCategoryRepository($db);

        $result = $repo->findByTypeAndAction('customer', 'general');

        $this->assertNull($result);
        $this->assertStringContainsString('type = ? AND action = ?', $db->lastSql);
    }

    public function testFindActive(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmCategoryRepository($db);

        $results = $repo->findActive();

        $this->assertIsArray($results);
        $this->assertStringContainsString('inactive = 0', $db->lastSql);
    }
}
