<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\WorkPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS = [
        [
            'username' => 'Wonder',
            'email' => 'wonder@gmail.com',
            'name' => 'Wonder Janusz',
            'password' => 'wonder123',
            'roles' => [User::ROLE_SUPERADMIN]
        ],[
            'username' => 'Jasio',
            'email' => 'jasio@gmail.com',
            'name' => 'Jasio Masio',
            'password' => 'jasio123',
            'roles' => [User::ROLE_ADMIN]
        ],[
            'username' => 'Marek',
            'email' => 'marek@gmail.com',
            'name' => 'Marek Bak',
            'password' => 'marek123',
            'roles' => [User::ROLE_WRITER]
        ],[
            'username' => 'Adam',
            'email' => 'adam@gmail.com',
            'name' => 'Adam Mak',
            'password' => 'adam123',
            'roles' => [User::ROLE_EDITOR]
        ],[
            'username' => 'Lukasz',
            'email' => 'lukasz@gmail.com',
            'name' => 'Lukasz Mar',
            'password' => 'lukasz123',
            'roles' => [User::ROLE_QUESTIONER]
        ]
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadWorkPost($manager);
        $this->loadQuestion($manager);
    }

    private function loadWorkPost(ObjectManager $manager)
    {

        for($i = 0 ; $i <100 ; $i++) {
            $workPost = new WorkPost();
            $workPost->setTitle($this->faker->realText(30));
            $workPost->setPublished($this->faker->dateTimeThisYear);
            $workPost->setContent($this->faker->realText());

            $authorReference = $this->getRandomUserReference($workPost);

            $workPost->setAuthor($authorReference);
            $workPost->setCV($this->faker->password());
            $workPost->setSlug($this->faker->slug);

            $this->setReference("work_post_$i", $workPost);

            $manager->persist($workPost);
        }

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixture['password']
            ));
            $user->setRoles($userFixture['roles']);

            $this->addReference('user_' . $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadQuestion(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1,10); $j++) {
                $question = new Question();
                $question->setContent($this->faker->realText());
                $question->setPublished($this->faker->dateTimeThisYear);

                $authorReference = $this->getRandomUserReference($question);

                $question->setAuthor($authorReference);
                $question->setWorkPost($this->getReference("work_post_$i"));

                $manager->persist($question);
            }
        }
        $manager->flush();
    }

    private function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0,4)];

        if ($entity instanceof WorkPost && !count(array_intersect(
                $randomUser['roles'],
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER]
            ))) {
            return $this->getRandomUserReference($entity);
        }

        if ($entity instanceof Question && !count(array_intersect(
                $randomUser['roles'],
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER, User::ROLE_QUESTIONER]
            ))) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference('user_' .$randomUser['username']);
    }


}
