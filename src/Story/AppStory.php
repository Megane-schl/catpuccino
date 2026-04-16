<?php

namespace App\Story;

use App\Enum\WeekDay;
use App\Factory\AllergenFactory;
use App\Factory\IngredientFactory;
use App\Factory\ScheduleFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {

        UserFactory::createSequence([
            [
                'email'     => 'grisou.grisou@gmail.com',
                'roles'     => ['ROLE_ADMIN'],
                'lastname'  => 'Grigri',
                'firstname' => 'Grisou'
            ],
            [
                'email'     => 'nougat.nougat@gmail.com',
                'roles'     => ['ROLE_MODO'],
                'lastname'  => 'Nounou',
                'firstname' => 'Nougat'
            ],
        ]);

        UserFactory::createMany(20);


        AllergenFactory::createSequence([
            [
                'name'     => 'Gluten',
                'info'  => 'Céréales (blé, épeautre, Khorasan, seigle, orge, avoine) et produits à base de ces céréales. Inclut sirops de glucose, maltodextrines et distillats alcooliques.',
            ],
            [
                'name' => 'Crustacés',
                'info' => 'Crustacés et produits à base de crustacés.'
            ],
            [
                'name' => 'Oeufs',
                'info' => 'Œufs et produits à base d’œufs.'
            ],
            [
                'name' => 'Poissons',
                'info' => 'Poissons et produits à base de poissons. Inclut gélatine de poisson (support vitamines) et ichtyocolle (clarification bière/vin).'
            ],
            [
                'name' => 'Arachides',
                'info' => 'Arachides et produits à base d’arachides.'
            ],
            [
                'name' => 'Soja',
                'info' => 'Soja et produits à base de soja (huiles raffinées, tocophérols naturels, phytostérols dérivés du soja).'
            ],
            [
                'name' => 'Lait',
                'info' => 'Lait et produits à base de lait (y compris lactose, lactosérum pour distillats et lactitol).'
            ],
            [
                'name' => 'Fruits à coques',
                'info' => 'Amandes, noisettes, noix (cajou, pécan, macadamia, Brésil, Queensland), pistaches et leurs dérivés alcooliques.'
            ],
            [
                'name' => 'Céleri',
                'info' => 'Céleri et produits à base de céleri.'
            ],
            [
                'name' => 'Moutarde',
                'info' => 'Moutarde et produits à base de moutarde (inclut acide béhénique pour émulsifiants E470a, E471, E477).'
            ],
            [
                'name' => 'Sésame',
                'info' => 'Graines de sésame et produits à base de graines de sésame.'
            ],
            [
                'name' => 'Sulfites',
                'info' => 'Anhydride sulfureux et sulfites en concentration de plus de 10 mg/kg ou 10 mg/l.'
            ],
            [
                'name' => 'Lupin',
                'info' => 'Lupin et produits à base de lupin.'
            ],
            [
                'name' => 'Mollusques',
                'info' => 'Mollusques et produits à base de mollusques.'
            ],

        ]);


        $objGluten          = AllergenFactory::find(['name' => 'Gluten']);
        $objOeufs           = AllergenFactory::find(['name' => 'Oeufs']);
        $objArachide        = AllergenFactory::find(['name' => 'Arachides']);
        $objSoja            = AllergenFactory::find(['name' => 'Soja']);
        $objLait            = AllergenFactory::find(['name' => 'Lait']);
        $objFruitsCoques    = AllergenFactory::find(['name' => 'Fruits à coques']);
        $objSesame          = AllergenFactory::find(['name' => 'Sésame']);

        IngredientFactory::createSequence([
            [
                'name'      => 'Sucre',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Sucre brun',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Chocolat au lait',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Chocolat noir',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Chocolat blanc',
                'isVegan'   => false,
                'allergen'  => []
            ],
            [
                'name'      => 'Café',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Fraise',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Framboise',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Matcha',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Vanille',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Lait',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Lait d\'avoine',
                'isVegan'   => true,
                'allergen'  => [$objGluten]
            ],
            [
                'name'      => 'Farine de blé',
                'isVegan'   => true,
                'allergen'  => [$objGluten]
            ],

            [
                'name'      => 'Farine sans gluten',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Oeufs',
                'isVegan'   => false,
                'allergen'  => [$objOeufs]
            ],
            [
                'name'      => 'Beurre',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Amande',
                'isVegan'   => true,
                'allergen'  => [$objFruitsCoques]
            ],
            [
                'name'      => 'Noisette',
                'isVegan'   => true,
                'allergen'  => [$objFruitsCoques]
            ],
            [
                'name'      => 'Lait d\'amande',
                'isVegan'   => true,
                'allergen'  => [$objFruitsCoques]
            ],
            [
                'name'      => 'Lait de noisette',
                'isVegan'   => true,
                'allergen'  => [$objFruitsCoques]
            ],
            [
                'name'      => 'Miel',
                'isVegan'   => false,
                'allergen'  => []
            ],
            [
                'name'      => 'Beurre de cacahuète',
                'isVegan'   => true,
                'allergen'  => [$objArachide]
            ],
            [
                'name'      => 'Nutella',
                'isVegan'   => false,
                'allergen'  => [$objFruitsCoques, $objLait]
            ],
            [
                'name'      => 'Huile',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Fromage',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Banane',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Praliné',
                'isVegan'   => true,
                'allergen'  => [$objFruitsCoques]
            ],
            [
                'name'      => 'Caramel',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Pâte à tartiner Spéculoos',
                'isVegan'   => true,
                'allergen'  => [$objGluten, $objSoja]
            ],
            [
                'name'      => 'Granola maison',
                'isVegan'   => false,
                'allergen'  => [$objGluten, $objFruitsCoques, $objSesame]
            ],

        ]);

        ScheduleFactory::createSequence([
            [
                'day'           => WeekDay::Monday,
                'openTime'      => new DateTimeImmutable('09:30'),
                'closeTime'     => new DateTimeImmutable('19:00'),
                'isClose'       => true,
                'maxPeople'     => 20,
            ],
            [
                'day'           => WeekDay::Tuesday,
                'openTime'      => new DateTimeImmutable('09:30'),
                'closeTime'     => new DateTimeImmutable('19:00'),
                'isClose'       => false,
                'maxPeople'     => 20,
            ],
            [
                'day'           => WeekDay::Wednesday,
                'openTime'      => new DateTimeImmutable('09:30'),
                'closeTime'     => new DateTimeImmutable('19:00'),
                'isClose'       => false,
                'maxPeople'     => 20,
            ],
            [
                'day'           => WeekDay::Thursday,
                'openTime'      => new DateTimeImmutable('10:30'),
                'closeTime'     => new DateTimeImmutable('20:00'),
                'isClose'       => false,
                'maxPeople'     => 20,
            ],
            [
                'day'           => WeekDay::Friday,
                'openTime'      => new DateTimeImmutable('10:30'),
                'closeTime'     => new DateTimeImmutable('20:00'),
                'isClose'       => false,
                'maxPeople'     => 20,
            ],
            [
                'day'           => WeekDay::Saturday,
                'openTime'      => new DateTimeImmutable('09:30'),
                'closeTime'     => new DateTimeImmutable('20:00'),
                'isClose'       => false,
                'maxPeople'     => 30,
            ],
            [
                'day'           => WeekDay::Sunday,
                'openTime'      => new DateTimeImmutable('09:30'),
                'closeTime'     => new DateTimeImmutable('20:00'),
                'isClose'       => false,
                'maxPeople'     => 30,
            ],

        ]);
    }
}
