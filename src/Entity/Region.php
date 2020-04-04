<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

     //?RELATIONS

        /**
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="region")
     */
    private $animals;

    /**
     * @ORM\OneToMany(targetEntity="Department", mappedBy="region", cascade="remove")
     */
    private $departments;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->departments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of animals
     */ 
    public function getAnimals()
    {
        return $this->animals;
    }

    /**
     * Set the value of animals
     *
     * @return  self
     */ 
    public function setAnimals($animals)
    {
        $this->animals = $animals;

        return $this;
    }

    /**
     * Get the value of departments
     */ 
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set the value of departments
     *
     * @return  self
     */ 
    public function setDepartments($departments)
    {
        $this->departments = $departments;

        return $this;
    }
}
