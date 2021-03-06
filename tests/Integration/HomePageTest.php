<?php

namespace Tests\Integration;

use App\Package;
use Tests\TestCase;

final class HomePageTest extends TestCase
{
    public function testEmptyRecord(): void
    {
        $this->get('/')
            ->assertSeeText('It seems nothing is here!');
    }

    public function testSomeRecord(): void
    {
        /** @var Package $package1 */

        $package1 = Package::factory()->create();

        /** @var Package $package2 */

        $package2 = Package::factory()->create();

        $this->get('/')
            ->assertSeeText($package1->name)
            ->assertSeeText($package1->description)
            ->assertSeeText($package2->name)
            ->assertSeeText($package2->description);
    }

    public function testHideOfficialPackages(): void
    {
        /** @var Package $package1 */

        $package1 = Package::factory()->create([
            'name' => 'fruitcake/laravel-cors',
        ]);

        /** @var Package $package2 */

        $package2 = Package::factory()->create();

        $this->get('/')
            ->assertDontSeeText($package1->name)
            ->assertDontSeeText($package1->description)
            ->assertSeeText($package2->name)
            ->assertSeeText($package2->description);
    }

    public function testRankingLinks(): void
    {
        $month = route('ranking', [
            'type' => 'monthly',
            'date' => now()->subDays(2)->format('Y-m'),
        ]);

        $year = route('ranking', [
            'type' => 'yearly',
            'date' => now()->year,
        ]);

        $this->get('/')
            ->assertSee($month)
            ->assertSee($year);
    }
}
