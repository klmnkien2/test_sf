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
use Gao\AdminBundle\Entity\Admin;
use Gao\AdminBundle\Service\DataTableService;

/**
 * Admin Service.
 */
class AdminService
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

    public function updateLastLogin(Admin $admin) {

        $admin->setLastLogin($admin->getCurrentLogin());
        $admin->setCurrentLogin(new \DateTime("now"));
        $this->em->persist($admin);
        $this->em->flush();

    }

    public function getEntity($id)
    {
        return $this->em->getRepository('GaoAdminBundle:Admin')->find($id);
    }

    public function isExist($username)
    {
        $check = $this->em->getRepository('GaoAdminBundle:Admin')->findBy( array( 'username' => $username ) );
        return !empty($check);
    }

    public function saveEntity($admin)
    {
        if ($admin->getPassword()) {
            $admin->setSalt(uniqid(mt_rand())); // Unique salt for user

            // Set encrypted password
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
            $password = $encoder->encodePassword($admin->getPassword(), $admin->getSalt());
            $admin->setPassword($password);
        }

        $this->em->persist($admin);
        $this->em->flush();
    }


    /**
     * get transaction from user
     *
     * @param int     $id      The id of user
     * @param Request $request Http server request
     *
     * @return Array results
     */
    public function getAdminByUserId($id, $request)
    {
        // DB table to use
        $table = 'admin';

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
            array( 'db' => 'email',     'dt' => 3 ),
            array( 'db' => 'phone',     'dt' => 4 ),
            array(
                'db'        => 'last_login',
                'dt'        => 5,
                'formatter' => function( $d, $row ) {
                    return date( 'jS M y', strtotime($d));
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 6,
                'formatter' => function( $d, $row ) {
                    return $this->actionFormatter($d);
                }
            )
        );
        return DataTableService::getData( $_GET, $this->em->getConnection(), $table, $primaryKey, $columns );
    }

    public function actionFormatter($id)
    {
        $editlink = $this->container->get('router')->generate('gao_admin_account_edit') . "?id=$id";
        return "<a href='$editlink' class='editlink'>Edit</a> <a href='#' class='deletelink' data-id='$id'>Delete</a>";
    }
}
