<?php


namespace Tests\Feature\Repositories\Company;


use App\Models\Company\Company;
use App\Repositories\Company\SectorRepository;
use App\Models\Company\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var SectorRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = resolve(SectorRepository::class);
    }

    /** @test */
    public function it_should_find_an_existing_sector()
    {
        $sector = Sector::factory()
            ->state(['name' => 'First Sector'])
            ->count(1)
            ->create()
            ->first();

        Sector::factory(2)->create();

        $result = $this->repository->findByNameOrCreate('First Sector');

        $this->assertEquals($sector->id, $result->id);
    }

    /** @test */
    public function it_should_create_a_new_sector_if_not_found()
    {
        $sector = Sector::factory()
            ->state(['name' => 'First Sector'])
            ->count(1)
            ->create()
            ->first();

        $result = $this->repository->findByNameOrCreate('Second Sector');

        $this->assertNotEquals($sector->id, $result->id);
        $this->assertDatabaseHas('sectors', [
            'name'  => 'Second Sector'
        ]);
    }
}
