<?php

namespace App\Factory;

use App\Entity\Flight;
use App\Repository\FlightRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Flight|Proxy createOne(array $attributes = [])
 * @method static Flight[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Flight|Proxy find($criteria)
 * @method static Flight|Proxy findOrCreate(array $attributes)
 * @method static Flight|Proxy first(string $sortedField = 'id')
 * @method static Flight|Proxy last(string $sortedField = 'id')
 * @method static Flight|Proxy random(array $attributes = [])
 * @method static Flight|Proxy randomOrCreate(array $attributes = [])
 * @method static Flight[]|Proxy[] all()
 * @method static Flight[]|Proxy[] findBy(array $attributes)
 * @method static Flight[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Flight[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FlightRepository|RepositoryProxy repository()
 * @method Flight|Proxy create($attributes = [])
 */
final class FlightFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'status' => Flight::SALE_IS_OPEN
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
//             ->afterInstantiate(function(Flight $flight) {})
        ;
    }

    protected static function getClass(): string
    {
        return Flight::class;
    }
}
