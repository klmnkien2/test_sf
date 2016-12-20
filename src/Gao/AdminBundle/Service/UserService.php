<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Gao\C5Bundle\Entity\Users;
use Gao\AdminBundle\Service\DataTableService;

/**
 * User Service.
 */
class UserService
{
    /**
     * EntityManager.
     */
    protected $em;

    /**
     * Container Interface.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param EntityManager $em        The EntityManager.
     * @param Container     $container The Container Interface.
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function updateLastLogin(Users $user) {

        $user->setLastLogin($user->getCurrentLogin());
        $user->setCurrentLogin(new \DateTime("now"));
        $this->em->persist($user);
        $this->em->flush();

    }

    public function getEntity($id)
    {
        return $this->em->getRepository('GaoC5Bundle:Users')->find($id);
    }

    public function removeEntity($id)
    {
        $entity = $this->getEntity($id);
        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }

    public function isExist($username)
    {
        $check = $this->em->getRepository('GaoC5Bundle:Users')->findBy( array( 'username' => $username ) );
        return !empty($check);
    }

    public function saveEntity($entity)
    {
        if ($entity->getPassword()) {
            $entity->setSalt(uniqid(mt_rand())); // Unique salt for user

            // Set encrypted password
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
        }

        $this->em->persist($entity);
        $this->em->flush();
    }


    /**
     * get data table from user
     *
     * @param int     $userId The id of admin user
     * @param string  $token  Token to do action
     *
     * @return Array results
     */
    public function getDataTable($userId, $token)
    {
        // DB table to use
        $table = 'users';

        // Table's primary key
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'username',  'dt' => 1 ),
            array( 'db' => 'full_name', 'dt' => 2 ),
            array( 'db' => 'phone',     'dt' => 3 ),
            array( 'db' => 'c_level',   'dt' => 4 ),
            array( 'db' => 'pd_count',  'dt' => 5 ),
            array( 'db' => 'pd_total',  'dt' => 6 ),
            array( 'db' => 'gd_count',  'dt' => 7 ),
            array( 'db' => 'gd_total',  'dt' => 8 ),
            array(
                'db'        => 'id',
                'dt'        => 9,
                'formatter' => function( $d, $row ) use ($token) {
                    return $this->actionFormatter($d, $token);
                }
            )
        );
        return DataTableService::getData( $_GET, $this->em->getConnection(), $table, $primaryKey, $columns );
    }

    public function actionFormatter($id, $token)
    {
        $editlink = $this->container->get('router')->generate('gao_admin_user_edit') . "?id=$id";
        $deletelink = $this->container->get('router')->generate('gao_admin_user_delete') . "?id=$id&token=$token";
        return "<a href='$editlink' class='editlink'>Edit</a> <a href='$deletelink' class='deletelink'>Delete</a>";
    }
}
