<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\DocumentRepository")
 */
class Document
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
     * @var bool
     *
     * @ORM\Column(name="is_buy", type="boolean")
     */
    private $isBuy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sale", type="boolean")
     */
    private $isSale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_event", type="datetime")
     */
    private $dateEvent;

    /**
     * @var Payment
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Payment", mappedBy="documents")
     */
    private $payments;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->dateEvent = new \DateTime();
        $this->isBuy = false;
        $this->isSale = false;
        $this->payments = new ArrayCollection();
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
     * @return bool
     */
    public function isBuy(): bool
    {
        return $this->isBuy;
    }

    /**
     * @return Document
     */
    public function setIsBuy()
    {
        $this->isBuy = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSale(): bool
    {
        return $this->isSale;
    }

    /**
     * @return Document
     */
    public function setIsSale()
    {
        $this->isSale = true;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEvent(): \DateTime
    {
        return $this->dateEvent;
    }

    /**
     * @param \DateTime $dateEvent
     *
     * @return Document
     */
    public function setDateEvent(\DateTime $dateEvent)
    {
        $this->dateEvent = $dateEvent;

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
     */
    public function setPayments(Payment $payments): void
    {
        $this->payments = $payments;
    }

}

