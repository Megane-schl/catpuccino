<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    public const DEFAULT_PASSWORD = "M?sth3rb3";
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {}

    #[\Override]
    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {

        $dteUpdatedAt = self::faker()->dateTime();
        $dteDeletedAt = self::faker()->dateTime();

        return [
            'email'         => self::faker()->email(),
            'firstname'     => self::faker()->firstName(),
            'lastname'      => self::faker()->lastName(),
            'password'      => $this->userPasswordHasher->hashPassword(new User(), self::DEFAULT_PASSWORD),
            'isVerified'    => self::faker()->boolean(),
            'isBan'         => self::faker()->boolean(0.1),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            // 30% chance that updatedAt is filled with a datetime or else stay null
            'updatedAt' => self::faker()->boolean(0.3)
                ? \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween($dteUpdatedAt, '+1 year')) : null,
            'deletedAt' => self::faker()->boolean(0.3)
                ? \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween($dteDeletedAt, '+1 month')) : null,
            'roles' => [],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
