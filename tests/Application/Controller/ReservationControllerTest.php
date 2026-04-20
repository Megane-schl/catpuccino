<?php

namespace App\Tests\Application\Controller;

use App\Enum\WeekDay;
use App\Factory\ScheduleFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;


#[ResetDatabase]
class ReservationControllerTest extends WebTestCase
{

    /**
     * Method to test if the reservation page is accessible 
     */
    public function testIndexPageShow(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reservation/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Votre moment câlin');
    }

    /**
     * Method to test if the reservation create page isn't accessible if not logged
     */
    public function testReservationPageWithoutLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reservation/create');

        $this->assertResponseRedirects('/login');
    }

    /**
     * Method to test if the reservation create page is accessible if login
     */
    public function testReservationPageLogin(): void
    {
        $client = static::createClient();

        $objUser = UserFactory::createOne();

        $client->loginUser($objUser);

        $crawler = $client->request('GET', '/reservation/create');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Method to test that a user can see available time slots for a specific date
     */
    public function testCreateReservationPageShowTimeSlots(): void
    {

        $client = static::createClient();

        $objUser = UserFactory::createOne();

        $client->loginUser($objUser);

        //array of week day 
        $arrDays = [
            1 => WeekDay::Monday,
            2 => WeekDay::Tuesday,
            3 => WeekDay::Wednesday,
            4 => WeekDay::Thursday,
            5 => WeekDay::Friday,
            6 => WeekDay::Saturday,
            7 => WeekDay::Sunday,
        ];

        $objSchedule    = ScheduleFactory::createOne([
            'day'       => $arrDays[date('N')],
            'openTime'  => new DateTimeImmutable('00:01'),
            'closeTime' => new DateTimeImmutable('23:59'), //< for the sime slot is not passed
            'isClose'   => false,
            'maxPeople' => 20,

        ]);

        $crawler = $client->request('GET', '/reservation/create');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Réserver un créneau');

        //$this->assertSelectorTextNotContains('p', 'Aucun créneau disponible');
    }

    /**
     * Method to test that a user can successfully create a reservation with the create form
     */
    // public function testCreateReservationFormSubmit(): void
    // {
    //     $client = static::createClient();

    //     $objUser = UserFactory::createOne();

    //     $client->loginUser($objUser);

    //     $crawler = $client->request('GET', '/reservation/create');

    //     $this->assertResponseIsSuccessful();
    // }*/
}
