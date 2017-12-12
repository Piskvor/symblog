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
        $bazTag = new ArticleTag('baz');
        $qagTag = new ArticleTag('qag');
        $qagTag->setShown(false);
        $manager->persist($fooTag);
        $manager->persist($barTag);
        $manager->persist($bazTag);
        $manager->persist($qagTag);
        $manager->flush();

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

        $blogArticle = new BlogArticle();
        $blogArticle->setUrl('correct-horse');
        $blogArticle->setArticleText('correct horse battery staple');
        $blogArticle->setTitle('Correct Horse');
        $blogArticle->setArticleShown(true);
        $blogArticle->setArticleDate(new \DateTime());
        $blogArticle->addTag($bazTag);
        $blogArticle->addTag($barTag);
        $blogArticle->addTag($qagTag);
        $manager->persist($blogArticle);

        $blogArticle = new BlogArticle();
        $blogArticle->setUrl('battery-staple');
        $blogArticle->setArticleText('correct horse battery staple');
        $blogArticle->setTitle('Battery Staple');
        $blogArticle->setArticleShown(true);
        $blogArticle->setArticleDate(new \DateTime());
        $blogArticle->addTag($qagTag);
        $manager->persist($blogArticle);

        $blogArticle = new BlogArticle();
        $blogArticle->setUrl('asdf');
        $blogArticle->setArticleText('asdfghjkl');
        $blogArticle->setTitle('asdf');
        $blogArticle->setArticleShown(true);
        $blogArticle->setArticleDate(new \DateTime());
        $blogArticle->addTag($fooTag);
        $manager->persist($blogArticle);

        $blogArticle = new BlogArticle();
        $blogArticle->setUrl('qwert');
        $blogArticle->setArticleText('qwertyuiop');
        $blogArticle->setTitle('qwert');
        $blogArticle->setArticleShown(true);
        $blogArticle->setArticleDate(new \DateTime());
        $manager->persist($blogArticle);

        $manager->flush();
    }
}