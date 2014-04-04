<?php

namespace James\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="James\UserBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    protected $lastName;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstName($first_name)
    {
        $this->firstName = $first_name;
    }

    public function setLastName($last_name)
    {
        $this->lastName = $last_name;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function toJson()
    {
        return json_encode($this->toSerialize());
    }

    public static function toSerialize($user)
    {
        return array(
                    'id' => $user->getId(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail()
                    );
    }

    public static function toArraySerialize($userArray)
    {
        $users = array();

        foreach($userArray as $user){
            array_push($users, User::toSerialize($user));
        }

        return $users;
    }
}
