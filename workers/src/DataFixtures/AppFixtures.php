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
        $user = $this->getReference('user_admin');

        for($i = 0 ; $i <100 ; $i++) {
            $workPost = new WorkPost();
            $workPost->setTitle($this->faker->realText(30));
            $workPost->setPublished($this->faker->dateTimeThisYear);
            $workPost->setContent($this->faker->realText());
            $workPost->setAuthor($user);
            $workPost->setCV($this->faker->password());
            $workPost->setSlug($this->faker->slug);

            $this->setReference("work_post_$i", $workPost);

            $manager->persist($workPost);
        }

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@gmail.com');
        $user->setName('Dariusz Kitel');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'haslo123'
        ));
        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }

    private function loadQuestion(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1,10); $j++) {
                $question = new Question();
                $question->setContent($this->faker->realText());
                $question->setPublished($this->faker->dateTimeThisYear);
                $question->setAuthor($this->getReference('user_admin'));
                $question->setWorkPost($this->getReference("work_post_$i"));

                $manager->persist($question);
            }
        }
        $manager->flush();
    }


}
