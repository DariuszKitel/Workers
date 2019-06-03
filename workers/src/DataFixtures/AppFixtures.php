<?php

namespace App\DataFixtures;

use App\Entity\WorkPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $workPost = new WorkPost();
        $workPost->setTitle('Pierwszy wpis');
        $workPost->setPublished(new \DateTime());
        $workPost->setContent('Opis oferty');
        $workPost->setAuthor('Marek Baj');
        $workPost->setCV('sdcnk39u8huncc3asd2');
        $workPost->setSlug('First-slug');

        $manager->persist($workPost);

        $workPost = new WorkPost();
        $workPost->setTitle('Drugi wpis');
        $workPost->setPublished(new \DateTime());
        $workPost->setContent('Opis drugiej oferty');
        $workPost->setAuthor('Pawel Baj');
        $workPost->setCV('sdcnk39u8hu34cc3asd2');
        $workPost->setSlug('Second-slug');

        $manager->persist($workPost);


        $manager->flush();
    }
}
