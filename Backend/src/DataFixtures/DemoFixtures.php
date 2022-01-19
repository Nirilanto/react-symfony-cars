<?php

namespace App\DataFixtures;

use App\Entity\Cars;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DemoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $allUsers = [];
        $userDefault = (new User())
            ->setName("Demo")
            ->setEmail("test@test.com")
            ->setPlainPassword("123456");

        $manager->persist($userDefault);

        // User fixtures
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setName($faker->name())
                ->setEmail($faker->email)
                ->setPlainPassword("123456");

            $manager->persist($user);

            $allUsers[] = $user;
        }

        // Cars and comment fixtures
        for ($i = 0; $i < 100; $i++) {
            $cars = (new Cars())
                ->setName($faker->country)
                ->setType($faker->randomElement(['4x4', 'Citadine', 'Tout terrain']))
                ->setMark($faker->randomElement(['Peugeot', 'Citroen', 'Audi', 'Mercedes', 'BMW']))
                ->setAbout($faker->text(200))
                ->setImage($faker->randomElement(['/voiture/image1.jpg', '/voiture/image2.jpg', '/voiture/image3.jpg', '/voiture/image4.jpg', '/voiture/image5.jpg', '/voiture/image6.jpg']));

            $randomCommentCount = $faker->numberBetween(2, 10);

            for ($j = 0; $j <= $randomCommentCount; $j++) {
                $comment = (new Comment())
                    ->setBody($faker->paragraphs(2, true))
                    ->setDate($faker->dateTimeBetween())
                    ->setUsers($faker->randomElement($allUsers));
                $cars->addComment($comment);
            }

            $manager->persist($cars);
        }

        $manager->flush();
    }
}
