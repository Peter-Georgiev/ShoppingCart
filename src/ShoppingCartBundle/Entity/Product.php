<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"InnoDB"})
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var int
     *
     * @ORM\Column(name="qtty", type="integer")
     */
    private $qtty;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var int
     *
     * @ORM\Column(name="owner_id", type="integer")
     */
    private $ownerId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
     private $category;

    /**
    * @var bool
     *
    * @ORM\Column(name="is_delete", type="boolean")
    */
     private $isDelete;

    /**
     * @var int
     *
     * @ORM\Column(name="most_wanted", type="integer")
     */
     private $mostWanted;

    /**
     * @var Payment
     *
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Payment", mappedBy="products")
     */
    private $payments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Review", inversedBy="products")
     * @ORM\JoinTable(name="products_reviews",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="review_id", referencedColumnName="id")})
     */
    private $reviews;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Discount", inversedBy="products")
     * @ORM\JoinTable(name="products_discounts",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="discount_id", referencedColumnName="id")})
     */
    private $discounts;


    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
        $this->payments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->discounts = new ArrayCollection();
        $this->isDelete = false;
        $this->mostWanted = 1000;
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
     * @return Product
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
     * Set measure
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get measure
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set qtty
     *
     * @param string $qtty
     *
     * @return Product
     */
    public function setQtty($qtty)
    {
        $this->qtty = $qtty;

        return $this;
    }

    /**
     * Get qtty
     *
     * @return string
     */
    public function getQtty()
    {
        return $this->qtty;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = floatval($price);

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded(): \DateTime
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded(\DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }
    /**
     * @param int $ownerId
     */
    public function setOwnerId(int $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return Product
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get int
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return Product
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->isDelete;
    }

    /**
     * @return Product
     */
    public function setIsDelete()
    {
        $this->isDelete = true;

        return $this;
    }

    /**
     * @return int
     */
    public function getMostWanted(): int
    {
        return $this->mostWanted;
    }

    /**
     * @param int $mostWanted
     * @return Product
     */
    public function setMostWanted(int $mostWanted)
    {
        $this->mostWanted = $mostWanted;

        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayments(): Payment
    {
        return $this->payments;
    }

    /**
     * @param Payment $payments
     * @return Product
     */
    public function setPayments(Payment $payments)
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * @param Review $review
     * @return Product
     */
    public function addReview(Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReviews()
    {
        $stringReviews = [];
        foreach ($this->reviews as $review) {
            $stringReviews[] = $review;
        }

        return $this->reviews;
    }

    /**
     * @param ArrayCollection $reviews
     */
    public function setReviews(ArrayCollection $reviews)
    {
        $this->reviews[] = $reviews;
    }

    /**
     * @param Discount $discount
     * @return Product
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
