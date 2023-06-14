<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Project;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the Client's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $client, string $newHashedPassword): void
    {
        if (!$client instanceof Client) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $client->setPassword($newHashedPassword);

        $this->save($client, true);
    }

    /**
     * @return Client[] Returns an array of User objects
     */
    public function findAllInThisProject(Project $project): array
    {
        $queryClient = $this->createQueryBuilder('c')
            ->andWhere('c.project = :project')
            ->setParameter('project', $project)
            ->orderBy('c.id', 'ASC');

        $query = $queryClient->getQuery();
        $query->setFetchMode(Client::class, "project", ClassMetadata::FETCH_EAGER);
        return $query->getResult();
    
    }

    public function findOneById($id, $project): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->andWhere('c.project = :project')
            ->setParameter('project', $project)
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getByUserName(string $username): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.email = :val')
            ->setParameter('val', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        
    }
}
