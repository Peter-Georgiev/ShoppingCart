<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Role
 *
 * @ORM\Table(name="roles",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"InnoDB"})
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\RoleRepository")
 */
class Role implements RoleHierarchyInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\User", mappedBy="roles")
     */
    private $users;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array|null $roles
     * @return string
     */
    public function getReachableRoles(array $roles = null)
    {
        return $this->getName();
    }

    /**
     * Add user
     *
     * @param \ShoppingCartBundle\Entity\User $user
     *
     * @return Role
     */
    public function addUser(\ShoppingCartBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ShoppingCartBundle\Entity\User $user
     */
    public function removeUser(\ShoppingCartBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
