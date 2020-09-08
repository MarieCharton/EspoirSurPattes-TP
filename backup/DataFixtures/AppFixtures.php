<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Animal;
use App\Entity\Region;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Services\Slugger;
use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
        // private $passwordEncoder;
        // private $slugger;

        // public function __construct(UserPasswordEncoderInterface $passwordEncoder, Slugger $slugger)
        // {
        //         $this->passwordEncoder = $passwordEncoder;
        //         $this->slugger = $slugger;
        // }

        public function load(ObjectManager $manager)
        {
                // On crée une instance de Faker en français
                $faker = Faker\Factory::create('fr_FR');

                $regionLabels =  [
                        "Grand Est" =>  [
                                "Ardennes",
                                "Aube",
                                "Marne",
                                "Haute-Marne",
                                "Meurthe-et-Moselle",
                                "Meuse",
                                "Moselle",
                                "Bas-Rhin",
                                "Haut-Rhin",
                                "Vosges"
                        ],
                        "Nouvelle-Aquitaine" => [
                                "Charente",
                                "Charente-Maritime",
                                "Corrèze",
                                "Creuse",
                                "Dordogne",
                                "Gironde",
                                "Landes",
                                "Lot-et-Garonne",
                                "Pyrénées-Atlantiques",
                                "Deux-Sèvres",
                                "Vienne",
                                "Haute-Vienne",
                        ],
                        "Auvergne-Rhône-Alpes" => [
                                "Ain",
                                "Allier",
                                "Ardèche",
                                "Cantal",
                                "Drôme",
                                "Isère",
                                "Loire",
                                "Haute-Loire",
                                "Puy-de-Dôme",
                                "Rhône",
                                "Savoie",
                                "Haute-Savoie",
                        ],
                        "Bourgogne-Franche-Comté" => [
                                "Côte-d'Or",
                                "Doubs",
                                "Jura",
                                "Nièvre",
                                "Haute-Saône",
                                "Saône-et-Loire",
                                "Territoire de Belfort",
                                "Yonne",
                        ],
                        "Bretagne" => [
                                "Côtes-d'Armor",
                                "Finistère",
                                "Ille-et-Vilaine",
                                "Morbihan",
                        ],
                        "Centre-Val de Loire" => [
                                "Cher",
                                "Eure-et-Loire",
                                "Indre",
                                "Indre-et-Loire",
                                "Loir-et-Cher",
                                "Loiret",
                        ],
                        "Corse" => [
                                "Haute-Corse",
                                "Corse-du-Sud",
                        ],
                        "Île-de-France" => [
                                "Paris",
                                "Yvelines",
                                "Seine-et-Marne",
                                "Essonne",
                                "Hauts-de-Seine",
                                "Seine-Saint-Denis",
                                "Val-d'Oise",
                                "Val-de-Marne",
                        ],
                        "Occitanie" => [
                                "Ariège",
                                "Aude",
                                "Aveyron",
                                "Gard",
                                "Haute-Garonne",
                                "Gers",
                                "Hérault",
                                "Lot",
                                "Lozère",
                                "Hautes-Pyrénées",
                                "Pyrénées-Orientales",
                                "Tarn",
                                "Tarn-et-Garonne",
                        ],
                        "Hauts-de-France" => [
                                "Aisne",
                                "Nord",
                                "Oise",
                                "Pas-de-Calais",
                                "Somme",
                        ],
                        "Normandie" => [
                                "Calvados",
                                "Eure",
                                "Manche",
                                "Orne",
                                "Seine-Maritime",
                        ],
                        "Pays de la Loire" => [
                                "Loire-Atlantique",
                                "Maine-et-Loire",
                                "Mayenne",
                                "Vendée",
                                "Sarthe",
                        ],
                        "Provence-Alpes-Côte d'Azur" => [
                                "Alpes-de-Haute-Provence",
                                "Hautes-Alpes",
                                "Alpes-Maritimes",
                                "Bouches-du-Rhône",
                                "Var",
                                "Vaucluse",
                        ],
                ];

                $categoryLabels = [
                        "entraide",
                        "association",
                        "information",
                        "bénévolat",
                        "dons",
                        "manifestation",
                        "événement"
                ];

                //Création des catégories
                foreach ($categoryLabels as $value => $categoryLabel) {
                        $category = new Category();
                        $category->setName($categoryLabel);
                        $manager->persist($category);
                        $categories[] = $category;
                }
                
                $typeLabels = [
                        "chien",
                        "chat",
                        "oiseau",
                        "lapin",
                        "autre"
                ];

                // Création des Types d'animaux
                foreach ($typeLabels as $typeLabel) {
                        $type = new Type();
                        $type->setName($typeLabel);
                        $manager->persist($type);
                        $types[] = $type; 
                }

                // Création d'Utilisateurs
                for ($i = 1; $i <= 10; $i++) {
                        $user = new User();
                        $user->setUsername(ucfirst($faker->word()))
                             ->setEmail($faker->email)
                             ->setCreatedAt($faker->dateTime)
                             ->setPassword($faker->word)
                             ->setRoles(["ROLE_USER"]);
                        
                        // Hash du MDP avec Encoder
                        $plainPassword = $faker->word;
                        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
                        $user->setPassword($encodedPassword);
                        $manager->persist($user);
                        $users[] = $user;
                        
                        //Création d'articles aléatoires
                        for ($j = 1; $j <= mt_rand(3, 6); $j++) {
                          
                                
                                // Creation d'un Média pour chaque Article
                                $article = new Article();
        

                                $article->setTitle($faker->words(5, true))
                                        ->setSlug($this->slugger->slugify($article->getTitle()))
                                        ->setContent('Bear claw croissant lemon drops toffee chocolate. Jelly-o cheesecake cake. I love sweet icing jelly-o biscuit bonbon chocolate pastry lemon drops. Sesame snaps I love marshmallow marzipan apple pie bonbon. Cake macaroon biscuit I love carrot cake jelly beans I love I love jujubes. I love oat cake marzipan I love tart I love I love jujubes. Gummies halvah lollipop cake dessert marshmallow topping brownie ice cream.
                                        Gummi bears candy I love soufflé wafer pie. Cotton candy pastry jelly-o jujubes apple pie halvah ice cream bear claw toffee. Lollipop caramels gummies cookie chupa chups tart danish powder powder. Tootsie roll cookie pudding gummies tart. Tootsie roll I love sugar plum gummi bears pastry pie chocolate bar sesame snaps. Chocolate cake I love tiramisu I love brownie gummi bears. I love sweet cake candy carrot cake cake sweet I love. Chupa chups jelly cake cake toffee halvah chocolate bar I love.
                                        
                                        Croissant halvah I love danish candy canes cake. I love pastry croissant wafer oat cake. Biscuit cupcake oat cake. Jelly-o pudding oat cake dragée croissant sugar plum cheesecake. Pudding jujubes danish chocolate bar cotton candy I love. Soufflé gummi bears I love candy canes. Apple pie croissant icing gummies gummies candy canes dessert bear claw bonbon. Chocolate cake I love sweet roll chocolate bar cupcake I love lollipop cotton candy cupcake. Marzipan I love tootsie roll sweet. Ice cream tiramisu dessert I love lemon drops marzipan chocolate cake.')
                                        ->setUser($user)
                                        ->setCreatedAt($faker->dateTime);
                                $manager->persist($article);
                                $articles[] = $article;
                        }

                }

                // Create some comments
                foreach($articles as $article) {

                        for($c = 1; $c <= mt_rand(5, 9); $c++) {
                                
                                $comment = new Comment();
                                $comment->setContent($faker->text)
                                        ->setArticle($article)
                                        ->setUser($faker->randomElement($users))
                                        ->setCreatedAt($faker->dateTime);
                                $manager->persist($comment);
                        }
                }

                // Creations des Régions en Base de Données
                foreach($regionLabels as $regionLabel => $departmentLabels) {
                        $region = new Region();
                        $region->setName($regionLabel);
                        $manager->persist($region);
                        $regions[] = $region;

                        // Création des Départements en Base de Données
                        foreach ($departmentLabels as $departmentLabel) {
                                $department = new Department();
                                $department->setName($departmentLabel);
                                $department->setRegion($region);
                                $manager->persist($department);
                                $departments[] = $department;

                                // Création d'Animaux Aléatoires pour chaque Département
                                for ($i = 1; $i <= mt_rand(5, 7); $i++) {
                                        $animal = new Animal();
                                        $animal->setName(ucfirst($faker->word()))
                                                ->setStatus($faker->randomElement(['perdu', 'trouvé', 'adoption']))
                                                ->setAge((mt_rand(2, 7)))
                                                ->setIdentification($faker->randomElement(['Tatoué', 'Identifié', 'Non renseigné']))
                                                ->setDescription('Gummi bears macaroon tart liquorice pudding gummi bears I love fruitcake. Marzipan cake lollipop toffee topping. Gummies cotton candy cookie cookie candy canes. I love donut tiramisu. Chupa chups sesame snaps ice cream I love. Cheesecake candy tart tart gummies tiramisu cheesecake. Marzipan donut I love jelly beans marshmallow tiramisu donut. Candy canes macaroon biscuit.')
                                                ->setSex(($faker->randomElement(['Male', 'Femelle'])))
                                                ->setArea($faker->word)
                                                ->setCity($faker->city)
                                                ->setType($faker->randomElement($types))
                                                ->setImage($animal->getType() . mt_rand(1, 5) . '.jpg')
                                                // ->setImage($faker->randomElement($types)->getName() . mt_rand(1, 5) . '.jpg')
                                                ->setCreatedAt($faker->dateTime)
                                                ->setUser($faker->randomElement($users))
                                                ->setRegion($region)
                                                ->setDepartment($department);

                                        $manager->persist($animal);
                                }
                                
                        }
                }

                $manager->flush();
        }
}