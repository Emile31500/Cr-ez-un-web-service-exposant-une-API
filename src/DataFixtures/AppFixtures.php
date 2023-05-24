<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Client;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->userPasswordHasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $ldlc = new Project();
        $ldlc->setName("LDLC");
        $ldlc->setSIREN(13597328412679);

        $materiel_net = new Project();
        $materiel_net->setName("Materiel.net");
        $materiel_net->setSIREN(76143189345671);

        $ldlc_user = new Client();
        $ldlc_admin = new Client();
        $mat_net_user = new Client();
        $mat_net_admin = new Client();

        $ldlc_user->setName("User_1");
        $ldlc_user->setEmail("user.1@gldlc.com");
        $ldlc_user->setPassword($this->userPasswordHasher->hashPassword($ldlc_user, "AGO123ER78"));
        $ldlc_user->setProject($ldlc);
        $ldlc_user->setRoles(['ROLE_USER']);
        $ldlc_user->setPhoneNumber('0674759132');

        $ldlc_admin->setName("Admin_1");
        $ldlc_admin->setEmail("admin.1@ldlc.com");
        $ldlc_admin->setPassword($this->userPasswordHasher->hashPassword($ldlc_admin, "AGO123ER78"));
        $ldlc_admin->setProject($ldlc);
        $ldlc_admin->setRoles(['ROLE_ADMIN']);
        $ldlc_admin->setPhoneNumber('0632354198');

        $mat_net_user->setName("User_1");
        $mat_net_user->setEmail("user.1@material.net");
        $mat_net_user->setPassword($this->userPasswordHasher->hashPassword($mat_net_user, "AGO123ER78"));
        $mat_net_user->setProject($materiel_net);
        $mat_net_user->setRoles(['ROLE_USER']);
        $mat_net_user->setPhoneNumber('0614129691');

        $mat_net_admin->setName("Admin_1");
        $mat_net_admin->setEmail("admin.1@material.net");
        $mat_net_admin->setPassword($this->userPasswordHasher->hashPassword($mat_net_admin, "AGO123ER78"));
        $mat_net_admin->setProject($materiel_net);
        $mat_net_admin->setRoles(['ROLE_ADMIN']);
        $mat_net_admin->setPhoneNumber('0614153278');

        $phone_apple = new Phone();
        $phone_apple->setBrand("Apple");
        $phone_apple->setName("iPhone 12");
        $phone_apple->setCpu(["frequency" => 3.1, "hearts" => 6, "name" => "A 14 Bionic"]);
        $phone_apple->setGpu(["frequency" => 1.0]);
        $phone_apple->setRam(["capacity" => 4, "type" => "LPDDR4x", "frequency" => 2133]);
        $phone_apple->setScreen(["frequency" => 60, "definition" => "2532 x 1170", "type" => "XDR"]);
        $phone_apple->setDescription("No desc yet.");
        $phone_apple->setPrice(600.0);
        $phone_apple->setImageUrl("unknowjpg");
        $phone_apple->setCount(200);

        $phone_sam = new Phone();
        $phone_sam->setBrand("Samsug");
        $phone_sam->setName("Galaxy Z Flip 4");
        $phone_sam->setCpu(["frequency" => 3.0, "hearts" => 8, "name" => "Snapdragon 8+ gen 1"]);
        $phone_sam->setGpu(["frequency" => 0.818]);
        $phone_sam->setRam(["capacity" => 8, "type" => "LPDDR4x", "frequency" => 2133]);
        $phone_sam->setScreen(["frequency" => 120, "definition" => "1080 x 2640", "type" => "AMOLED"]);
        $phone_sam->setDescription("No desc yet.");
        $phone_sam->setPrice(800.0);
        $phone_sam->setImageUrl("unknowjpg");
        $phone_sam->setCount(200);

        $phone_asus = new Phone();
        $phone_asus->setBrand("ASUS ");
        $phone_asus->setName("ROG Phone 7 ");
        $phone_asus->setCpu(["frequency" => 3.2, "hearts" => 8, "name" => "Snapdragon 8 gen 2"]);
        $phone_asus->setGpu(["frequency" => 0.97]);
        $phone_asus->setRam(["capacity" => 16, "type" => "LPDDR4x", "frequency" => 2133]);
        $phone_asus->setScreen(["frequency" => 165, "definition" => "1080 x 2448", "type" => "AMOLED"]);
        $phone_asus->setDescription("No desc yet.");   
        $phone_asus->setPrice(900.0);
        $phone_asus->setImageUrl("unknowjpg");
        $phone_asus->setCount(200);

        $manager->persist($materiel_net);
        $manager->persist($ldlc);

        $manager->persist($mat_net_user);
        $manager->persist($ldlc_user);
        $manager->persist($mat_net_admin);
        $manager->persist($ldlc_admin);
        
        $manager->persist($phone_apple);
        $manager->persist($phone_sam);
        $manager->persist($phone_asus);


        $manager->flush();
    }
}
