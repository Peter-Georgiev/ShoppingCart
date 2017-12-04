<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var float
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
     * @var float
     *
     * @ORM\Column(name="discount", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(name="payment", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $payment;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_paid", type="boolean")
     */
    private $isPaid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_purchases", type="datetime")
     */
    private $datePurchases;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer")
     */
    private $productId;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\Product", inversedBy="payments")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $products;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\User", inversedBy="payments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="document_id", type="integer", nullable=true)
     */
    private $documentId;

    /**
     * @var Document
     *
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\Document", inversedBy="payments")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $documents;


    /**
     * Payment constructor.
     */
    public function __construct()
    {
        $this->datePurchases = new \DateTime();
        $this->discount = 0.00;
        $this->payment = 0.00;
        $this->isPaid = false;
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
     * @return float
     */
    public function getQtty(): string
    {
        return $this->qtty;
    }

    /**
     * @param int $qtty
     *
     * @return Payment
     */
    public function setQtty(int $qtty)
    {
        $this->qtty = $qtty;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return Payment
     */
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return Payment
     */
    public function setDiscount(float $discount)
    {
        $this->discount = $discount;

        if ($discount > 0) {
            $discount = ($discount / $this->price) * 100;
            $this->payment = $this->price - $discount;
            return $this;
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPayment(): float
    {
        return $this->payment;
    }

    /**
     * @param float $payment
     *
     * @return Payment
     */
    public function setPayment(float $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    /**
     * @return Payment
     */
    public function setIsPaid()
    {
        $this->isPaid = true;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatePurchases()
    {
        return $this->datePurchases;
    }

    /**
     * @param $datePurchases
     * @return Payment
     */
    public function setDatePurchases($datePurchases)
    {
        $this->datePurchases = $datePurchases;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return Product
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product $products
     */
    public function setProducts(Product $products)
    {
        $this->products = $products;

        $this->price = $this->products->getPrice();
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $userId
     * @return Payment
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return User
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param $users
     * @return Payment
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @param int $documentId
     * @return Payment
     */
    public function setDocumentId(int $documentId)
    {
        $this->documentId = $documentId;

        return $this;
    }

    /**
     * @return Document
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document $documents
     * @return Payment
     */
    public function setDocuments(Document $documents)
    {
        $this->documents = $documents;

        return $this;
    }


}

