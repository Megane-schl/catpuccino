<?php

namespace App\Factory;

use App\Entity\Product;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Product>
 */
final class ProductFactory extends PersistentObjectFactory
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
        return Product::class;
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
            'name'          => self::faker()->text(100),
            'img'           => self::faker()->text(255),
            'description'   => self::faker()->text(),
            // 2 decimals / minimum 2euros / maximum 30 euros
            'price'         => self::faker()->randomFloat(2, 2, 10),
            'createdAt'     => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'category'      => CategoryFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
        ;
    }
}
