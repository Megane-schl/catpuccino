<?php

namespace App\Factory;

use App\Entity\Schedule;
use App\Enum\WeekDay;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Schedule>
 */
final class ScheduleFactory extends PersistentObjectFactory
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
        return Schedule::class;
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
            'day' => self::faker()->unique->randomElement(WeekDay::cases()),
            'openTime' => \DateTimeImmutable::createFromMutable(self::faker()->datetime()),
            'closeTime' => \DateTimeImmutable::createFromMutable(self::faker()->datetime()),
            'isClose' => self::faker()->boolean(10),
            'maxPeople' => self::faker()->numberBetween(15, 30),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Schedule $schedule): void {})
        ;
    }
}
