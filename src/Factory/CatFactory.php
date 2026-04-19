<?php

namespace App\Factory;

use App\Entity\Cat;
use App\Enum\CatGender;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Cat>
 */
final class CatFactory extends PersistentObjectFactory
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
        return Cat::class;
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
            'name'          => self::faker()->text(40),
            'description'   => self::faker()->text(),
            'img'           => self::faker()->text(255),
            'gender'        => self::faker()->randomElement(CatGender::cases()),
            'createdAt'     => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'birthdate'     => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-10years', '-3 months')),
            'product'       => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Cat $cat): void {})
        ;
    }
}
