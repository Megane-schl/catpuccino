<?php

namespace App\Story;

use App\Enum\CatGender;
use App\Enum\WeekDay;
use App\Factory\AllergenFactory;
use App\Factory\CategoryFactory;
use App\Factory\CatFactory;
use App\Factory\IngredientFactory;
use App\Factory\ProductFactory;
use App\Factory\ScheduleFactory;
use App\Factory\SpecialScheduleFactory;
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
            [
                'name'      => 'Pomme',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Cannelle',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Myrtille',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Crème fouettée',
                'isVegan'   => false,
                'allergen'  => [$objLait]
            ],
            [
                'name'      => 'Mascarpone',
                'isVegan'   => false,
                'allergen'  => [$objLait, $objOeufs]
            ],
            [
                'name'      => 'Biscuit cuillère',
                'isVegan'   => false,
                'allergen'  => [$objGluten, $objOeufs]
            ],
            [
                'name'      => 'Pâte feuilletée',
                'isVegan'   => false,
                'allergen'  => [$objGluten, $objLait]
            ],
            [
                'name'      => 'Cacao',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Menthe',
                'isVegan'   => true,
                'allergen'  => []
            ],
            [
                'name'      => 'Oreo',
                'isVegan'   => false,
                'allergen'  => [$objGluten, $objSoja]
            ],
            [
                'name'      => 'Glace vanille',
                'isVegan'   => false,
                'allergen'  => [$objLait, $objOeufs]
            ],
            [
                'name'      => 'Pain brioché',
                'isVegan'   => false,
                'allergen'  => [$objGluten, $objLait, $objOeufs]
            ],

        ]);

        $objSucre         = IngredientFactory::find(['name' => 'Sucre']);
        $objChocolatLait  = IngredientFactory::find(['name' => 'Chocolat au lait']);
        $objChocolatNoir  = IngredientFactory::find(['name' => 'Chocolat noir']);
        $objCafe          = IngredientFactory::find(['name' => 'Café']);
        $objFraise        = IngredientFactory::find(['name' => 'Fraise']);
        $objMatcha        = IngredientFactory::find(['name' => 'Matcha']);
        $objVanille       = IngredientFactory::find(['name' => 'Vanille']);
        $objLait       = IngredientFactory::find(['name' => 'Lait']);
        $objFarine        = IngredientFactory::find(['name' => 'Farine de blé']);
        $objOeufs     = IngredientFactory::find(['name' => 'Oeufs']);
        $objBeurre        = IngredientFactory::find(['name' => 'Beurre']);
        $objCaramel       = IngredientFactory::find(['name' => 'Caramel']);
        $objMiel          = IngredientFactory::find(['name' => 'Miel']);
        $objFromage       = IngredientFactory::find(['name' => 'Fromage']);
        $objCannelle      = IngredientFactory::find(['name' => 'Cannelle']);
        $objPomme         = IngredientFactory::find(['name' => 'Pomme']);
        $objMyrtille      = IngredientFactory::find(['name' => 'Myrtille']);
        $objCremeFouettee = IngredientFactory::find(['name' => 'Crème fouettée']);
        $objMascarpone    = IngredientFactory::find(['name' => 'Mascarpone']);
        $objBiscuit       = IngredientFactory::find(['name' => 'Biscuit cuillère']);
        $objPateFeuilletee = IngredientFactory::find(['name' => 'Pâte feuilletée']);
        $objCacao         = IngredientFactory::find(['name' => 'Cacao']);
        $objOreo          = IngredientFactory::find(['name' => 'Oreo']);
        $objGlaceVanille  = IngredientFactory::find(['name' => 'Glace vanille']);
        $objPainBrioche   = IngredientFactory::find(['name' => 'Pain brioché']);
        $objSucreBrun      = IngredientFactory::find(['name' => 'Sucre brun']);
        $objChocolatBlanc  = IngredientFactory::find(['name' => 'Chocolat blanc']);
        $objFramboise      = IngredientFactory::find(['name' => 'Framboise']);
        $objLaitAvoine     = IngredientFactory::find(['name' => 'Lait d\'avoine']);
        $objFarineSansGluten = IngredientFactory::find(['name' => 'Farine sans gluten']);
        $objAmande         = IngredientFactory::find(['name' => 'Amande']);
        $objNoisette       = IngredientFactory::find(['name' => 'Noisette']);
        $objLaitAmande     = IngredientFactory::find(['name' => 'Lait d\'amande']);
        $objLaitNoisette   = IngredientFactory::find(['name' => 'Lait de noisette']);
        $objPraline        = IngredientFactory::find(['name' => 'Praliné']);
        $objSpeculoos      = IngredientFactory::find(['name' => 'Pâte à tartiner Spéculoos']);
        $objGranola        = IngredientFactory::find(['name' => 'Granola maison']);
        $objMenthe         = IngredientFactory::find(['name' => 'Menthe']);
        $objHuile          = IngredientFactory::find(['name' => 'Huile']);
        $objBanane         = IngredientFactory::find(['name' => 'Banane']);

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

        SpecialScheduleFactory::createSequence([
            [
                'date'          => new DateTimeImmutable('2026-05-01'),
                'name'         => 'Congé',
                'openTime'      => null,
                'closeTime'     => null,
                'isClosed'       => true,
                'maxPeople'     => 0,
            ],
            [
                'date'          => new DateTimeImmutable('2026-07-14'),
                'name'         => 'Fête nationale',
                'openTime'      => null,
                'closeTime'     => null,
                'isClosed'       => true,
                'maxPeople'     => 0,
            ],
            [
                'date'          => new DateTimeImmutable('2026-12-25'),
                'name'         => 'Noël',
                'openTime'      => null,
                'closeTime'     => null,
                'isClosed'       => true,
                'maxPeople'     => 20,
            ],
            [
                'date'          => new DateTimeImmutable('2026-04-23'),
                'name'         => 'Goûter des Patounes',
                'openTime'      => new DateTimeImmutable('11:00'),
                'closeTime'     => new DateTimeImmutable('15:00'),
                'isClosed'       => false,
                'maxPeople'     => 20,
            ],
            [
                'date'          => new DateTimeImmutable('2026-01-23'),
                'name'         => 'Goûter avec Minou',
                'openTime'      => new DateTimeImmutable('10:00'),
                'closeTime'     => new DateTimeImmutable('21:00'),
                'isClosed'       => false,
                'maxPeople'     => 15,
            ],
            [
                'date'          => new DateTimeImmutable('2026-07-19'),
                'name'         => 'Brunch',
                'openTime'      => new DateTimeImmutable('08:00'),
                'closeTime'     => new DateTimeImmutable('12:00'),
                'isClosed'       => false,
                'maxPeople'     => 20,
            ],
            [
                'date'          => new DateTimeImmutable('2026-12-24'),
                'name'         => 'Noël entouré de chats',
                'openTime'      => new DateTimeImmutable('18:00'),
                'closeTime'     => new DateTimeImmutable('23:00'),
                'isClosed'       => false,
                'maxPeople'     => 20,
            ],

        ]);


        CategoryFactory::createSequence([
            [
                'name'         => 'Boisson chaude',
            ],
            [
                'name'         => 'Boisson froide',
            ],
            [
                'name'         => 'Pâtisserie',
            ],
            [
                'name'         => 'Viennoiserie',
            ],
        ]);

        $objBoissonChaude = CategoryFactory::find(['name' => 'Boisson chaude']);
        $objBoissonFroide = CategoryFactory::find(['name' => 'Boisson froide']);
        $objPatisserie    = CategoryFactory::find(['name' => 'Pâtisserie']);
        $objViennoiserie  = CategoryFactory::find(['name' => 'Viennoiserie']);

        $arrImagesProduct = [
            'apfel_strudel.png',
            'apple_pie.png',
            'blueberry_shake.png',
            'caramel_latte.png',
            'cat_coffee_cute.png',
            'catpuccino_special_cat.png',
            'cheesecake.png',
            'chocolate_cake.png',
            'coffee_heart.png',
            'coffee_oddish.png',
            'cookies.png',
            'croissant.png',
            'french_toast_miffy.png',
            'grisoucchiato.png',
            'hot_chocolate.png',
            'latte_macchiato.png',
            'matcha_latte.png',
            'muffin.png',
            'muffin_oreo.png',
            'strawberry_cake.png',
            'strawberry_iced_drink.png',
            'tiramisu.png',
            'viennese_hot_chocolate.png',
            'pain_chocolat.png'
        ];

        //copying the img in upload
        foreach ($arrImagesProduct as $imageP) {
            if (file_exists('public/fixtures/' . $imageP)) {
                copy('public/fixtures/' . $imageP, 'public/uploads/products/' . $imageP);
            }
        }

        ProductFactory::createSequence([
            [
                'name'        => 'Apfel Strudel',
                'img'         => 'apfel_strudel.png',
                'description' => 'Strudel aux pommes croustillant, parfumé à la cannelle, servi tiède avec une touche de sucre glace.',
                'category'    => $objPatisserie,
                'ingredients' => [$objPomme, $objCannelle, $objSucre, $objBeurre, $objFarine],
            ],
            [
                'name'        => 'Tarte aux pommes',
                'img'         => 'apple_pie.png',
                'description' => 'Tarte aux pommes fondante à l\'américaine, dorée au four avec une pâte maison.',
                'category'    => $objPatisserie,
                'ingredients' => [$objPomme, $objCannelle, $objSucre, $objBeurre, $objFarine, $objOeufs],
            ],
            [
                'name'        => 'Milkshake Myrtilles',
                'img'         => 'blueberry_shake.png',
                'description' => 'Milkshake onctueux aux myrtilles fraîches, mixé avec de la glace vanille.',
                'category'    => $objBoissonFroide,
                'ingredients' => [$objMyrtille, $objLait, $objGlaceVanille, $objSucre],
            ],
            [
                'name'        => 'Caramel Latte',
                'img'         => 'caramel_latte.png',
                'description' => 'Latte crémeux au caramel, préparé avec du lait de noisette pour une touche gourmande.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objLaitNoisette, $objCaramel, $objSucre],
            ],
            [
                'name'        => 'Café Chat',
                'img'         => 'cat_coffee_cute.png',
                'description' => 'Notre café signature avec un art latte en forme de chat, doux et aromatique.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objLait],
            ],
            [
                'name'        => 'Catpuccino',
                'img'         => 'catpuccino_special_cat.png',
                'description' => 'Le cappuccino emblématique de la maison, mousse veloutée et cacao saupoudré.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objLait, $objCacao, $objSucre],
            ],
            [
                'name'        => 'Cheesecake',
                'img'         => 'cheesecake.png',
                'description' => 'Cheesecake crémeux sur biscuit croquant, avec un coulis de fruits rouges.',
                'category'    => $objPatisserie,
                'ingredients' => [$objFromage, $objSucre, $objBeurre, $objOeufs, $objVanille],
            ],
            [
                'name'        => 'Gâteau au Chocolat',
                'img'         => 'chocolate_cake.png',
                'description' => 'Fondant au chocolat noir intense, cœur moelleux et cacao pur.',
                'category'    => $objPatisserie,
                'ingredients' => [$objChocolatNoir, $objBeurre, $objOeufs, $objFarine, $objSucre],
            ],
            [
                'name'        => 'Coeur Crème',
                'img'         => 'coffee_heart.png',
                'description' => 'Café latte avec un cœur de crème vanillée, décoré d\'un art latte en forme de cœur.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objLait, $objVanille, $objCremeFouettee],
            ],
            [
                'name'        => 'Café Mystherbe',
                'img'         => 'coffee_oddish.png',
                'description' => 'Café infusé aux herbes secrètes de la maison, une expérience unique et apaisante.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objMiel, $objMenthe],
            ],
            [
                'name'        => 'Cookies coeur',
                'img'         => 'cookies.png',
                'description' => 'Cookies pour l\'amour de Grisou',
                'category'    => $objPatisserie,
                'ingredients' => [$objChocolatNoir, $objFarineSansGluten],
            ],
            [
                'name'        => 'Croissant',
                'img'         => 'croissant.png',
                'description' => 'Croissant pur beurre, feuilleté et doré, croustillant à l\'extérieur et fondant à l\'intérieur.',
                'category'    => $objViennoiserie,
                'ingredients' => [$objFarine, $objBeurre, $objOeufs, $objSucre],
            ],
            [
                'name'        => 'Pain Perdu',
                'img'         => 'french_toast_miffy.png',
                'description' => 'Pain brioché doré au beurre, parfumé à la vanille et saupoudré de cannelle.',
                'category'    => $objViennoiserie,
                'ingredients' => [$objPainBrioche, $objOeufs, $objLait, $objVanille, $objCannelle, $objSucre],
            ],
            [
                'name'        => 'Chocolat Chaud',
                'img'         => 'hot_chocolate.png',
                'description' => 'Chocolat chaud signature avec un marshmallow',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objChocolatLait, $objLait, $objSucre],
            ],
            [
                'name'        => 'Latte Macchiato',
                'img'         => 'latte_macchiato.png',
                'description' => 'Latte macchiato classique aux trois couches, lait, espresso et mousse.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objCafe, $objLait],
            ],
            [
                'name'        => 'Matcha Latte',
                'img'         => 'matcha_latte.png',
                'description' => 'Latte au matcha japonais, préparé avec du lait d\'amande pour une version végétale.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objMatcha, $objLaitAmande, $objSucre],
            ],
            [
                'name'        => 'Muffin',
                'img'         => 'muffin.png',
                'description' => 'Muffin moelleux aux pépites de chocolat, sans gluten et fait maison chaque matin.',
                'category'    => $objPatisserie,
                'ingredients' => [$objFarineSansGluten, $objOeufs, $objBeurre, $objSucre, $objChocolatLait],
            ],
            [
                'name'        => 'Muffin Oreo',
                'img'         => 'muffin_oreo.png',
                'description' => 'Muffin gourmand aux morceaux d\'Oreo, avec un cœur fondant au chocolat blanc.',
                'category'    => $objPatisserie,
                'ingredients' => [$objFarine, $objOeufs, $objBeurre, $objSucre, $objOreo],
            ],
            [
                'name'        => 'Gâteau à la Fraise',
                'img'         => 'strawberry_cake.png',
                'description' => 'Génoise légère garnie de fraises fraîches et de crème chantilly maison.',
                'category'    => $objPatisserie,
                'ingredients' => [$objFraise, $objFarine, $objOeufs, $objSucre, $objCremeFouettee],
            ],
            [
                'name'        => 'Fraise Glacée',
                'img'         => 'strawberry_iced_drink.png',
                'description' => 'Boisson glacée à la fraise, rafraîchissante et fruitée, parfaite pour l\'été.',
                'category'    => $objBoissonFroide,
                'ingredients' => [$objFraise, $objSucre, $objGlaceVanille],
            ],
            [
                'name'        => 'Tiramisu',
                'img'         => 'tiramisu.png',
                'description' => 'Tiramisu traditionnel au mascarpone et café, saupoudré de cacao amer.',
                'category'    => $objPatisserie,
                'ingredients' => [$objMascarpone, $objCafe, $objBiscuit, $objCacao, $objSucre, $objOeufs],
            ],
            [
                'name'        => 'Chocolat Chaud Viennois',
                'img'         => 'viennese_hot_chocolate.png',
                'description' => 'Chocolat chaud onctueux au lait d\'avoine, surmonté d\'une généreuse crème fouettée.',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objChocolatNoir, $objLaitAvoine, $objSucre, $objCremeFouettee],
            ],
            [
                'name'        => 'Pain au Chocolat',
                'img'         => 'pain_chocolat.png',
                'description' => 'Viennoiserie feuilletée pur beurre avec deux barres de chocolat noir fondant.',
                'category'    => $objViennoiserie,
                'ingredients' => [$objFarine, $objBeurre, $objChocolatNoir, $objOeufs, $objSucre],
            ],
            [
                'name'        => 'Grisoucchiato',
                'img'         => 'grisoucchiato.png',
                'description' => 'Grisoucchiato un délice tigré sur son nuage de pattoune ',
                'category'    => $objBoissonChaude,
                'ingredients' => [$objLaitNoisette, $objCafe],
            ],
        ]);

        $arrImagesCats = [
            'grisou2.png',
            'guignol.png',
            'lion.png',
            'moccha.png',
            'nougat.png',
        ];

        //copying the img in upload
        foreach ($arrImagesCats as $imageC) {
            if (file_exists('public/fixtures/' . $imageC)) {
                copy('public/fixtures/' . $imageC, 'public/uploads/cats/' . $imageC);
            }
        }

        $objCatpuccino      = ProductFactory::find(['name' => 'Catpuccino']);
        $objChocolatChaud   = ProductFactory::find(['name' => 'Chocolat Chaud Viennois']);
        $objGrisoucchiato   = ProductFactory::find(['name' => 'Grisoucchiato']);
        $objMatchaLatte     = ProductFactory::find(['name' => 'Matcha Latte']);

        CatFactory::createSequence([
            [
                'name'        => 'Grisou',
                'img'         => 'grisou2.png',
                'description' => 'Grisou voleuse en série de bâtons et reine du café',
                'gender'      => CatGender::Female,
                'product'     => $objGrisoucchiato,
            ],
            [
                'name'        => 'Guignol',
                'img'         => 'guignol.png',
                'description' => 'Guignol, le clown du café, toujours prêt à faire des bêtises.',
                'gender'      => CatGender::Male,
                'product'     => $objCatpuccino,
            ],
            [
                'name'        => 'Lion',
                'img'         => 'lion.png',
                'description' => 'Lion, majestueux et paresseux, râle 20h par jour.',
                'gender'      => CatGender::Male,
            ],
            [
                'name'        => 'Moccha',
                'img'         => 'moccha.png',
                'description' => 'Moccha, douce et câline, adore se blottir sur les genoux.',
                'gender'      => CatGender::Female,
                'product'     => $objMatchaLatte,

            ],
            [
                'name'        => 'Nougat',
                'img'         => 'nougat.png',
                'description' => 'Nougat, gourmande insatiable, surveille la vitrine à pâtisseries.',
                'gender'      => CatGender::Female,
                'product'     => $objChocolatChaud,
            ],
        ]);
    }
}
