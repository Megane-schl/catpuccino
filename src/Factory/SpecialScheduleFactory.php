<?php

namespace App\Factory;

use App\Entity\SpecialSchedule;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<SpecialSchedule>
 */
final class SpecialScheduleFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return SpecialSchedule::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'date' => \DateTimeImmutable::createFromMutable(
                self::faker()->unique()->dateTimeBetween('now', '+1 year')
            ),
            'openTime' => \DateTimeImmutable::createFromMutable(
                self::faker()->datetimeBetween('08:00', '10:30')
            ),
            'closeTime' => \DateTimeImmutable::createFromMutable(
                self::faker()->datetimeBetween('17:00', '23:00')
            ),
            'isClosed' => self::faker()->boolean(10),
            'maxPeople' => self::faker()->numberBetween(15, 30),
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTime()
            ),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(SpecialSchedule $specialSchedule): void {})
        ;
    }
}
