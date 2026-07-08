<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\CrmContactRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CrmContactRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['id' => '1', 'person_id' => '5', 'type' => 'customer', 'action' => 'general', 'entity_id' => '3']], 1);
        $repo = new CrmContactRepository($db);

        $result = $repo->findById(1);

        $this->assertNotNull($result);
        $this->assertSame(5, $result->getPersonId());
        $this->assertStringContainsString('0_crm_contacts', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmContactRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByPerson(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmContactRepository($db);

        $results = $repo->findByPerson(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('person_id = ?', $db->lastSql);
    }

    public function testFindByEntity(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmContactRepository($db);

        $results = $repo->findByEntity('customer', 'general', '3');

        $this->assertIsArray($results);
        $this->assertStringContainsString('type = ? AND action = ? AND entity_id = ?', $db->lastSql);
    }

    public function testFindByType(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmContactRepository($db);

        $results = $repo->findByType('customer');

        $this->assertIsArray($results);
        $this->assertStringContainsString('type = ?', $db->lastSql);
    }

    public function testFindPersonContacts(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CrmContactRepository($db);

        $results = $repo->findPersonContacts(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('JOIN 0_crm_persons', $db->lastSql);
    }
}
