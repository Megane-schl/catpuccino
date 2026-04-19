<?php

namespace App\Tests\Application\Controller;

use App\Factory\AllergenFactory;
use App\Factory\CategoryFactory;
use App\Factory\IngredientFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\Story\AppStory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;
use Zenstruck\Foundry\Attribute\WithStory;

#[ResetDatabase]
class ProductControllerTest extends WebTestCase
{
    /**
     * Method to test if the product page is accessible 
     */
    public function testIndexPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        // assertSelect => take the first selector find on the page
        $this->assertSelectorTextContains('h1', 'La carte de Catpuccino');

        // Test the number of product by default (the database is empty -> 0
        $this->assertAnySelectorTextContains('div', '0 Produit(s) trouvé(s) !');
    }

    /**
     * Method to test the product page display 1 product when one exist in the database
     */
    public function testIndexPageShowOneProduct(): void
    {
        $client = static::createClient();

        ProductFactory::createOne();    //< Generate a product in the database with random data

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        $this->assertAnySelectorTextContains('div', '1 Produit(s) trouvé(s) !');
    }

    /**
     * Method to test that the product page displays the correct count when many products exists
     */
    public function testIndexPageShowManyProduct(): void
    {
        $client = static::createClient();

        ProductFactory::createMany(50);    //< Generate 50 products in the database with random data

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        $this->assertAnySelectorTextContains('div', '50 Produit(s) trouvé(s) !');
    }

    /**
     * Test that the product page display the correct count when we generate it with the AppStory
     */
    #[WithStory(AppStory::class)]
    public function testIndexPageShowWithStory(): void
    {
        // If Stories is uses, make sure the kernel hasn't already already started 
        static::ensureKernelShutdown();

        $client = static::createClient();

        $crawler = $client->request('GET', '/product/');

        $this->assertResponseIsSuccessful();

        $this->assertAnySelectorTextContains('div', '24 Produit(s) trouvé(s) !');
    }

    /**
     * Test that the create page product without being logged in redirect to login page
     */
    public function testCreatePageShowWithoutLoggin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/create');

        $this->assertResponseRedirects('/login');
    }

    /** 
     * Method to test if a user with ROLE_USER cannot access to the create page product and redirect a 403
     */
    public function testCreatePageShowWithUserRole(): void
    {

        $client = static::createClient();

        $objUser = UserFactory::createOne();

        $client->loginUser($objUser);

        $crawler = $client->request('GET', '/product/create');

        $this->assertResponseStatusCodeSame(403);
    }


    /** 
     * Method to test if a user with ROLE_MODO can access to the create page product
     */
    public function testCreatePageShowWithModoRole(): void
    {

        $client = static::createClient();

        $objUser = UserFactory::createOne(['roles' => ['ROLE_MODO']]);

        $client->loginUser($objUser);

        $crawler = $client->request('GET', '/product/create');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Method to test that a moderator can successfully create a product with the create form
     */
    public function testCreateProductFormSubmit(): void
    {
        $client = static::createClient();
        $objUser = UserFactory::createOne(['roles' => ['ROLE_MODO']]);      //< creation of an user in the database
        $client->loginUser($objUser);                                      //< The created user login on the application

        // Ingredient factory is configurate with a random 0 to 3 allergen
        AllergenFactory::createMany(3);
        $objIngredient  = IngredientFactory::createOne();
        $objCategory    = CategoryFactory::createOne();

        $crawler = $client->request('GET', '/product/create');

        $form = $crawler->selectButton('Enregistrer')->form();

        // https://symfony.com/doc/current/testing.html#interacting-with-the-response

        $client->submitForm('Enregistrer', [
            'product_form[name]'            => 'Latte Macchiato Vanille',
            'product_form[price]'           => 5.50,
            'product_form[description]'     => 'Miam miaou grisouuuuuu',
            'product_form[category]'        => $objCategory->getId(),
            'product_form[ingredients]'     => $objIngredient->getId()
        ]);

        $this->assertResponseRedirects();

        $client->followRedirect();

        $this->assertRouteSame('app_product_show');
    }
}
