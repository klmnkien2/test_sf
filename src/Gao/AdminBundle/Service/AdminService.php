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
        $check = $this->em->getRepository('GaoAdminBundle:Admin')->findBy( array( 'username' => $username ) );
        return !empty($check);
    }

    public function saveEntity($admin)
    {
        $this->em->persist($admin);
        $this->em->flush();
    }


    /**
     * get transaction from user
     *
     * @param int     $accountId The id of admin user
     * @param string  $token     Token to do action
     *
     * @return Array results
     */
    public function getAdminTable($accountId, $token)
    {
        $total_field = DataTableService::TOTAL_FIELD;
        $where_more = DataTableService::WHERE_MORE;

        $sql = <<<SQL
SELECT
    id,
    username,
    full_name,
    phone,
    email,
    last_login
FROM
    admin
WHERE
    creator_id = $accountId AND
    $where_more
SQL;

        $count_sql = <<<SQL
SELECT
    COUNT(id) AS $total_field
FROM
    admin
WHERE
    creator_id = $accountId AND
    $where_more
SQL;

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
                'formatter' => function( $d, $row ) use ($token) {
                    return $this->actionFormatter($d, $token);
                }
            )
        );
        return DataTableService::getCustomData( $_GET, $this->em->getConnection(), $sql, $count_sql, $columns );
    }

    public function actionFormatter($id, $token)
    {
        $editlink = $this->container->get('router')->generate('gao_admin_account_edit') . "?id=$id";
        $deletelink = $this->container->get('router')->generate('gao_admin_account_delete') . "?id=$id&token=$token";
        return "<a href='$editlink' class='editlink'>Edit</a> <a href='$deletelink' class='deletelink'>Delete</a>";
    }
}
