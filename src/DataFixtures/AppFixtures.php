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

        $materielNet = new Project();
        $materielNet->setName("Materiel.net");
        $materielNet->setSIREN(76143189345671);

        $ldlcUser = new Client();
        $ldlcAdmin = new Client();
        $matNetUser = new Client();
        $matNetAdmin = new Client();

        $ldlcUser->setClientName("User_1");
        $ldlcUser->setEmail("user.1@gldlc.com");
        $ldlcUser->setPassword($this->userPasswordHasher->hashPassword($ldlcUser, "AGO123ER78"));
        $ldlcUser->setProject($ldlc);
        $ldlcUser->setRoles(['ROLE_USER']);
        $ldlcUser->setPhoneNumber('0674759132');

        $ldlcAdmin->setClientName("Admin_1");
        $ldlcAdmin->setEmail("admin.1@ldlc.com");
        $ldlcAdmin->setPassword($this->userPasswordHasher->hashPassword($ldlcAdmin, "AGO123ER78"));
        $ldlcAdmin->setProject($ldlc);
        $ldlcAdmin->setRoles(['ROLE_ADMIN']);
        $ldlcAdmin->setPhoneNumber('0632354198');

        $matNetUser->setClientName("User_1");
        $matNetUser->setEmail("user.1@material.net");
        $matNetUser->setPassword($this->userPasswordHasher->hashPassword($matNetUser, "AGO123ER78"));
        $matNetUser->setProject($materielNet);
        $matNetUser->setRoles(['ROLE_USER']);
        $matNetUser->setPhoneNumber('0614129691');

        $matNetAdmin->setClientName("Admin_1");
        $matNetAdmin->setEmail("admin.1@material.net");
        $matNetAdmin->setPassword($this->userPasswordHasher->hashPassword($matNetAdmin, "AGO123ER78"));
        $matNetAdmin->setProject($materielNet);
        $matNetAdmin->setRoles(['ROLE_ADMIN']);
        $matNetAdmin->setPhoneNumber('0614153278');

        $iPhoneDz = new Phone();
        $iPhoneDz->setBrand("Apple");
        $iPhoneDz->setName("iPhone 12");
        $iPhoneDz->setCpu(["frequency" => 3.1, "hearts" => 6, "name" => "A 14 Bionic"]);
        $iPhoneDz->setGpu(["frequency" => 1.0]);
        $iPhoneDz->setRam(["capacity" => 4, "type" => "LPDDR4x", "frequency" => 2133]);
        $iPhoneDz->setScreen(["frequency" => 60, "definition" => "2532 x 1170", "type" => "XDR"]);
        $iPhoneDz->setDescription("No desc yet.");
        $iPhoneDz->setPrice(600.0);
        $iPhoneDz->setImageUrl("unknowjpg");
        $iPhoneDz->setCount(200);

        $iPhoneQzPM = new Phone();
        $iPhoneQzPM->setBrand("Apple");
        $iPhoneQzPM->setName("iPhone 15 Pro Max");
        $iPhoneQzPM->setCpu(["frequency" => 2.6, "hearts" => 6, "name" => "A 17 Pro"]);
        $iPhoneQzPM->setGpu(["frequency" => 1.0]);
        $iPhoneQzPM->setRam(["capacity" => 8, "type" => "LPDDR4x", "frequency" => 2133]);
        $iPhoneQzPM->setScreen(["frequency" => 120, "definition" => "2796 x 1290", "type" => "XDR"]);
        $iPhoneQzPM->setDescription("No desc yet.");
        $iPhoneQzPM->setPrice(2000.0);
        $iPhoneQzPM->setImageUrl("unknowjpg");
        $iPhoneQzPM->setCount(150);


        $phoneSamZFQ = new Phone();
        $phoneSamZFQ->setBrand("Samsug");
        $phoneSamZFQ->setName("Galaxy Z Flip 4");
        $phoneSamZFQ->setCpu(["frequency" => 3.0, "hearts" => 8, "name" => "Snapdragon 8+ gen 1"]);
        $phoneSamZFQ->setGpu(["frequency" => 0.818]);
        $phoneSamZFQ->setRam(["capacity" => 8, "type" => "LPDDR4x", "frequency" => 2133]);
        $phoneSamZFQ->setScreen(["frequency" => 120, "definition" => "1080 x 2640", "type" => "AMOLED"]);
        $phoneSamZFQ->setDescription("No desc yet.");
        $phoneSamZFQ->setPrice(800.0);
        $phoneSamZFQ->setImageUrl("unknowjpg");
        $phoneSamZFQ->setCount(200);

        $phoneSamZFC = new Phone();
        $phoneSamZFC->setBrand("Samsug");
        $phoneSamZFC->setName("Galaxy Z Fold 5");
        $phoneSamZFC->setCpu(["frequency" => 3.36, "hearts" => 8, "name" => "Snapdragon 8 gen 2"]);
        $phoneSamZFC->setGpu(["frequency" => 0.818]);
        $phoneSamZFC->setRam(["capacity" => 8, "type" => "LPDDR4x", "frequency" => 2133]);
        $phoneSamZFC->setScreen(["frequency" => 120, "definition" => "1812 x 2176", "type" => "AMOLED"]);
        $phoneSamZFC->setDescription("No desc yet.");
        $phoneSamZFC->setPrice(2039.0);
        $phoneSamZFC->setImageUrl("unknowjpg");
        $phoneSamZFC->setCount(200);


        $phoneAsus = new Phone();
        $phoneAsus->setBrand("ASUS ");
        $phoneAsus->setName("ROG Phone 7 ");
        $phoneAsus->setCpu(["frequency" => 3.2, "hearts" => 8, "name" => "Snapdragon 8 gen 2"]);
        $phoneAsus->setGpu(["frequency" => 0.97]);
        $phoneAsus->setRam(["capacity" => 16, "type" => "LPDDR4x", "frequency" => 2133]);
        $phoneAsus->setScreen(["frequency" => 165, "definition" => "1080 x 2448", "type" => "AMOLED"]);
        $phoneAsus->setDescription("No desc yet.");   
        $phoneAsus->setPrice(900.0);
        $phoneAsus->setImageUrl("unknowjpg");
        $phoneAsus->setCount(200);

        $manager->persist($materielNet);
        $manager->persist($ldlc);

        $manager->persist($matNetUser);
        $manager->persist($ldlcUser);
        $manager->persist($matNetAdmin);
        $manager->persist($ldlcAdmin);
        
        $manager->persist($iPhoneDz);
        $manager->persist($iPhoneQz);
        $manager->persist($phoneSamZFQ);
        $manager->persist($phoneSamZFC);
        $manager->persist($phoneAsus);


        $manager->flush();
    }
}
