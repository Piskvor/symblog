<?php
declare(strict_types=1);

namespace AppBundle\DataFixtures;

use AppBundle\Entity\ArticleTag;
use AppBundle\Entity\BlogArticle;
use AppBundle\Entity\BlogUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultContent extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new BlogUser('blogadmin', 'hunter2');
        $manager->persist($user);

        $fooTag = new ArticleTag('foo');
        $barTag = new ArticleTag('bar');
        $qagTag = new ArticleTag('qag');
        $qagTag->setShown(false);
        $manager->persist($fooTag);
        $manager->persist($barTag);
        $manager->persist($qagTag);
        $blogArticle = new BlogArticle();
        $blogArticle->setUrl('baz-quux');
        $blogArticle->setArticleText('<b>adasd</b>lklklk');
        $blogArticle->setTitle('Baz Quux');
        $blogArticle->setArticleShown(true);
        $blogArticle->setArticleDate(new \DateTime());
        $blogArticle->addTag($fooTag);
        $blogArticle->addTag($barTag);
        $blogArticle->addTag($qagTag);
        $manager->persist($blogArticle);
        $manager->flush();
    }
}