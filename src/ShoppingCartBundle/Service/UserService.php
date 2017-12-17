<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use ShoppingCartBundle\Entity\Role;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Repository\RoleRepository;
use ShoppingCartBundle\Repository\UserRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

class UserService implements UserServiceInterface
{

}