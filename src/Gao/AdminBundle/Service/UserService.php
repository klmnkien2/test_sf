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
        $total_field = DataTableService::TOTAL_FIELD;
        $where_more = DataTableService::WHERE_MORE;

        $sql = <<<SQL
SELECT
    id,
    username,
    full_name,
    phone,
    blocked,
    c_level
FROM
    users
WHERE
    creator_id = $userId AND
    $where_more
SQL;

        $count_sql = <<<SQL
SELECT
    COUNT(id) AS $total_field
FROM
    users
WHERE
    creator_id = $userId AND
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
            array( 'db' => 'phone',     'dt' => 3 ),
            array( 'db' => 'c_level',   'dt' => 4 ),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function( $d, $row ) use ($token) {
                    return $this->statusFormatter($d, $row, $token);
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

    public function statusFormatter($id, $row, $token)
    {
        $statusSelect = '
          <select class="prg-userBlock" data-id="'.$id.'">
            <option value="0" '. ($row['blocked'] == 0?" selected":"") . ' >Non-Block</option>
            <option value="1" '. ($row['blocked'] == 1?" selected":"") . ' >Soft-Block</option>
            <option value="2" '. ($row['blocked'] == 2?" selected":"") . ' >Hard-Block</option>
          </select>';

        return $statusSelect;
    }

    public function actionFormatter($id, $token)
    {
        $editlink = $this->container->get('router')->generate('gao_admin_user_edit') . "?id=$id";
        $deletelink = $this->container->get('router')->generate('gao_admin_user_delete') . "?id=$id&token=$token";
        return 
            "<a href='$editlink' class='editlink'>Edit</a> " . 
            "<a href='$deletelink' class='deletelink'>Delete</a>";
    }
}
