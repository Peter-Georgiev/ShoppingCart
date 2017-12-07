<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"InnoDB"})
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reg_time", type="datetime")
     */
    private $regTime;

    /**
     * @var string
     *
     * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
     */
    private $cash;

    /**
     * @var Product
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="owner")
     */
    private $products;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var Payment
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Payment", mappedBy="users")
     */
    private $payments;

    /**
     * @var Review
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Review", mappedBy="owner")
     */
    private $reviews;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_ban", type="boolean")
     */
    private $isBan;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->regTime = new \DateTime();
        $this->roles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->isBan = false;
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set regTime
     *
     * @param \DateTime $regTime
     *
     * @return User
     */
    public function setRegTime($regTime)
    {
        $this->regTime = $regTime;

        return $this;
    }

    /**
     * Get regTime
     *
     * @return \DateTime
     */
    public function getRegTime()
    {
        return $this->regTime;
    }

    /**
     * @return float
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param float $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }

    /**
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product $product
     *
     * @return User
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @param ArrayCollection $roles
     */
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];

        foreach ($this->roles as $role) {
            /** @var $role Role */
            $stringRoles[] = $role->getReachableRoles();
        }

        return $stringRoles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function isOwner(Product $product)
    {
        return $product->getOwnerId() == $this->getId();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array("ROLE_ADMIN", $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isEdit()
    {
        return in_array("ROLE_EDIT", $this->getRoles());
    }

    /**
     * Remove product
     *
     * @param \ShoppingCartBundle\Entity\Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Remove role
     *
     * @param \ShoppingCartBundle\Entity\Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return ArrayCollection|Payment
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param Payment $payments
     * @return User
     */
    public function setPayments(Payment $payments)
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * @return ArrayCollection|Review
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review $reviews
     * @return User
     */
    public function setReviews(Review $reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBan(): bool
    {
        return $this->isBan;
    }

    /**
     * @param bool $isBan
     * @return User
     */
    public function setIsBan(bool $isBan)
    {
        $this->isBan = $isBan;

        return $this;
    }
}
