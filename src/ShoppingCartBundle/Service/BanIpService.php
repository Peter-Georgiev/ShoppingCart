<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Repository\BanIpRepository;

class BanIpService implements BanIpServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BanIpRepository */
    private $banIpRepository;

    /**
     * BanIpService constructor.
     * @param BanIpRepository $banIpRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                BanIpRepository $banIpRepository)
    {
        $this->entityManager = $entityManager;
        $this->banIpRepository = $banIpRepository;
    }


    public function viewAll()
    {
        return $this->banIpRepository->findAll();
    }
}