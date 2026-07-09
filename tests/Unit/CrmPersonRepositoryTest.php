<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\CrmPersonRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CrmPersonRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['id' => '1', 'ref' => 'P001', 'name' => 'John', 'name2' => null, 'address' => null, 'phone' => null, 'phone2' => null, 'fax' => null, 'email' => null, 'lang' => null, 'notes' => '', 'inactive' => '0']], 1);
        $repo = new CrmPersonRepository($db);

        $result = $repo->findById(1);

        $this->assertNotNull($result);
        $this->assertSame(1, $result->getId());
        $this->assertStringContainsString('0_crm_persons', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmPersonRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByRef(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmPersonRepository($db);

        $result = $repo->findByRef('P001');

        $this->assertNull($result);
        $this->assertStringContainsString('ref = ?', $db->lastSql);
    }

    public function testFindByEmail(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmPersonRepository($db);

        $results = $repo->findByEmail('john@test.com');

        $this->assertIsArray($results);
        $this->assertStringContainsString('email = ?', $db->lastSql);
        $this->assertStringContainsString('inactive = 0', $db->lastSql);
    }

    public function testFindActive(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmPersonRepository($db);

        $results = $repo->findActive();

        $this->assertIsArray($results);
        $this->assertStringContainsString('inactive = ?', $db->lastSql);
    }

    public function testSearch(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmPersonRepository($db);

        $results = $repo->search('John');

        $this->assertIsArray($results);
        $this->assertStringContainsString('LIKE ?', $db->lastSql);
        $this->assertStringContainsString('LIMIT 50', $db->lastSql);
    }
}
