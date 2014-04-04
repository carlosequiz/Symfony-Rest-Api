<?php

namespace James\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use James\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $testUsers = array(
                                array(
                                        'firstName' => 'George',
                                        'lastName' => 'Carlin',
                                        'email' => 'gcarlin@test.com'
                                    ),
                                array(
                                        'firstName' => 'Louis',
                                        'lastName' => 'CK',
                                        'email' => 'lck@test.com'
                                    ),
                                array(
                                        'firstName' => 'Eddie',
                                        'lastName' => 'Murphy',
                                        'email' => 'emurphy@test.com'
                                    ),
                                array(
                                        'firstName' => 'Richard',
                                        'lastName' => 'Pryor',
                                        'email' => 'rpryor@test.com'
                                    ),
                                array(
                                        'firstName' => 'Bill',
                                        'lastName' => 'Cosby',
                                        'email' => 'bcosby@test.com'
                                    ),
                                array(
                                        'firstName' => 'Mitch',
                                        'lastName' => 'Hedberg',
                                        'email' => 'mhedberg@test.com'
                                    )
                            );

        foreach($testUsers as $testUser){
            $user = new User();
            $user->setFirstName($testUser['firstName']);
            $user->setLastName($testUser['lastName']);
            $user->setEmail($testUser['email']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}