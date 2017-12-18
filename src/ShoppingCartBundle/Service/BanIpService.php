<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Repository\BanIpRepository;

class BanIpService
{
    /** @var BanIpRepository */
    private $banIpRepository;

    /**
     * BanIpService constructor.
     * @param BanIpRepository $banIpRepository
     */
    public function __construct(BanIpRepository $banIpRepository)
    {
        $this->banIpRepository = $banIpRepository;
    }

    public function viewAll()
    {
        return $this->banIpRepository->findAll();
    }
}