<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="categories",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"InnoDB"})
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="category")
     */
    private $products;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_delete", type="boolean")
     */
    private $isDelete;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Discount", inversedBy="categories")
     * @ORM\JoinTable(name="categories_discounts",
     *     joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="discount_id", referencedColumnName="id")})
     */
    private $discounts;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->discounts = new ArrayCollection();
        $this->isDelete = false;
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
     * @return Category
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
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->isDelete;
    }

    /**
     * @return Category
     */
    public function setIsDelete()
    {
        $this->isDelete = true;

        return $this;
    }

    /**
     * @param Discount $discount
     * @return Category
     */
    public function addDiscount(Discount $discount)
    {
        $this->discounts[] = $discount;

        return $this;
    }

    /**
     * @return array
     */
    public function getDiscounts()
    {
        $stringDiscount = [];
        foreach ($this->discounts as $discount) {
            $stringDiscount[] = $discount;
        }

        return $stringDiscount;
    }

    /**
     * @param ArrayCollection $discounts
     */
    public function setDiscounts(ArrayCollection $discounts)
    {
        $this->discounts = $discounts;
    }
}
