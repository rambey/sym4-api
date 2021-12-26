<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager){

        $user = new User();
        $user->setUsername('belram')
             ->setFullname('bellili rami')
             ->setEmail('bellilirami@gmail.com')
             ->setPassword($this->passwordEncoder->encodePassword(
                 $user,
                 'secret_password'
             ))
             ;
             $manager->persist($user);        
             $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setTitle('A fist Post')
                 ->setPublished(new \DateTime('2021-12-12 12:00:00'))
                ->setContent('here is a content')
                ->setSlug('first-slug')
                ->setAuthor($user);
        $manager->persist($blogPost);        
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager){

    }
    

    
    
}
