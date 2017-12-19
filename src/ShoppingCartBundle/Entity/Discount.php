<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Discount
 *
 * @ORM\Table(name="discounts",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"InnoDB"})
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\DiscountRepository")
 */
class Discount
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="percent", type="decimal", precision=10, scale=2)
     */
    private $percent;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_user", type="boolean")
     */
    private $isUser;

    /**
     * @var int
     *
     * @ORM\Column(name="user_days", type="integer")
     */
    private $userDays;

    /**
     * @var float
     *
     * @ORM\Column(name="user_cash", type="decimal", precision=10, scale=2)
     */
    private $userCash;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="discounts")
     */
    private $products;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Category", mappedBy="discounts")
     */
    private $categories;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->isUser = false;
        $this->userDays = 0;
        $this->userCash = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $startDate
     * @return Discount
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param $endDate
     * @return Discount
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param $percent
     * @return Discount
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     * @return Discount
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     * @return Discount
     */
    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->isUser;
    }

    /**
     * @return Discount
     */
    public function setIsUser()
    {
        $this->isUser = true;

        return $this;
    }

    public function getUserDays()
    {
        return $this->userDays;
    }

    /**
     * @param int $userDays
     * @return Discount
     */
    public function setUserDays(int $userDays)
    {
        $this->userDays = $userDays;

        return $this;
    }

    public function getUserCash()
    {
        return $this->userCash;
    }

    /**
     * @param float $userCash
     * @return Discount
     */
    public function setUserCash(float $userCash)
    {
        $this->userCash = $userCash;

        return $this;
    }
}

