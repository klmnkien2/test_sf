<?php
namespace Gao\AdminBundle\Entity;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

class AdminRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
        ->where('u.username = :username OR u.email = :email')
        ->setParameter('username', $username)
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
    
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Gao\AdminBundle\Entity\Admin';
    }
}