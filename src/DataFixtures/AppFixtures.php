<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
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
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager){
        $user = $this->getReference('user_admin');     

        for($i= 0 ; $i<=100 ; $i++){
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30))
                     ->setPublished($this->faker->dateTimeThisYear)
                    ->setContent($this->faker->realText(30))
                    ->setSlug($this->faker->slug)
                    ->setAuthor($user);
            $this->setReference("blog_post_$i",$blogPost);        
            $manager->persist($blogPost);        
            
        }
        $manager->flush();
 
    }

    public function loadComments(ObjectManager $manager){
          
        for ($i= 0 ; $i<=100 ; $i++) {
            for ($j= 0 ; $j<rand(1,10) ; $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText(30))
                        ->setPublished($this->faker->dateTimeThisYear)
                        ->setAuthor($this->getReference('user_admin'))
                        ->setBlogPost($this->getReference("blog_post_$i"));
                        $manager->persist($comment);  
            }
        }
        $manager->flush();
    }
    
    public function loadUsers(ObjectManager $manager){

        $user = new User();
        $user->setUsername('admin')
             ->setEmail('admin@gmail.com')
             ->setUsername('Rami Bellili')
             ->setFullname('Rami Bellili')
             ->setPassword($this->passwordEncoder->encodePassword(
                 $user,
                 'azerty'
             ));
             $this->addReference('user_admin', $user);
             $manager->persist($user);
             $manager->flush();
    }

    
    
}
