<?php

namespace App\DataFixtures;

use App\Entity\InstructorStatut;
use App\Entity\Speciality;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //Specialities
        $accessibility = new Speciality();
        $accessibility->setSpeciality('Accessibilité');

        $ecodesign = new Speciality();
        $ecodesign->setSpeciality('Eco-conception');

        $digitalFootprint = new Speciality();
        $digitalFootprint->setSpeciality('Empreinte digitale');

        $htmlCssJavascript = new Speciality();
        $htmlCssJavascript->setSpeciality('Html, Css, JavaScript');

        $wordPress = new Speciality();
        $wordPress->setSpeciality('Cms WordPress');

        $framewordJS = new Speciality();
        $framewordJS->setSpeciality('Framework JavaScript');

        $php = new Speciality;
        $php->setSpeciality('Php');

        $frameworkPhp = new Speciality();
        $frameworkPhp->setSpeciality('Framework Php');

        $dataBase = new Speciality();
        $dataBase->setSpeciality('Base de données');

        $manager->persist($accessibility);
        $manager->persist($ecodesign);
        $manager->persist($digitalFootprint);
        $manager->persist($htmlCssJavascript);
        $manager->persist($wordPress);
        $manager->persist($framewordJS);
        $manager->persist($php);
        $manager->persist($frameworkPhp);
        $manager->persist($dataBase);

        $jean = new Student();
        $jean->setPseudo('jean');
        $jean->setEmail('jean@test.fr');
        $jean->setPassword('123456');

        $manager->persist($jean);

        $enAttente = new InstructorStatut();
        $enAttente->setStatut('En attente');

        $valide = new InstructorStatut();
        $valide->setStatut('Validé');

        $refuse = new InstructorStatut();
        $refuse->setStatut('Refusé');

        $manager->persist($enAttente);
        $manager->persist($valide);
        $manager->persist($refuse);


        $manager->flush();
    }
}
