<?php

namespace ShoppingCartBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Document;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Repository\ProductRepository;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class PaymentService implements PaymentServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * PaymentService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManagerInterface $entityManager,
                                TokenStorageInterface $tokenStorage,
                                ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array|Payment $payments
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function checkout($payments)
    {
        $documentId = 0;

        foreach ($payments as $payment) {
            /**
             * @var Payment $payment
             * @var Product $product
             */
            $product = $this->productRepository->find($payment->getProducts()->getId());
            $quantity = $product->getQtty() - $payment->getQtty();

            if ($quantity >= 0) {
                //$em = $this->getDoctrine()->getManager();
                $this->entityManager->getConnection()->beginTransaction();

                try {

                    if ($documentId === 0) {
                        $document = new Document();
                        $document->setIsBuy();
                        $this->entityManager->persist($document);
                        $this->entityManager->flush();
                        //$em->persist($document);
                        //$em->flush();
                        $documentId = $document->getId();
                    }
                    //Old owner added cash
                    $product->getOwner()->setCash($product->getOwner()->getCash() + $payment->getPrice());
                    $product->setQtty($quantity);
                    $this->entityManager->persist($product);
                    //$em->persist($product);

                    //New owner added paid
                    $payment->setPayment($payment->getPrice());
                    $pay = $payment->getUsers()->getCash() - $payment->getPrice();

                    $payment->getUsers()->setCash($pay);
                    $payment->setDocumentId($documentId);

                    $payment->setIsPaid();
                    $this->entityManager->persist($payment);
                    $this->entityManager->flush();
                    $this->entityManager->getConnection()->commit();
                    //$em->persist($payment);
                    //$em->flush();
                } catch (\Exception $e) {
                    $this->entityManager->getConnection()->rollBack();
                }
            }
        }
    }
}