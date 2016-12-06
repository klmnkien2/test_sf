<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\C5Bundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gao\C5Bundle\Entity\Attachment;

/**
 * AttachmentService class.
 *
 * Common Service.
 */
class AttachmentService
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

    /**
     * move uploaded file
     *
     * @param UploadedFile $file
     * @param string $relativePath
     *
     * @return string upload file full string path
     **/
    public function moveUploadedFile(UploadedFile $file, $relativePath, $fileName)
    {
        $uploadBasePath = $this->container->get('kernel')->getRootDir() . '/../web/uploads';

        $targetDir = $uploadBasePath . DIRECTORY_SEPARATOR . $relativePath;
        if (! is_dir($targetDir)) {
            $ret = mkdir($targetDir, umask(), true);
            if (! $ret) {
                throw new \RuntimeException("Could not create target directory to move temporary file into.");
            }
        }
        $file->move($targetDir, $fileName);

        return '/uploads' . '/' . $relativePath . '/' . $fileName;
    }

    /**
     * generate file name
     *
     * @param UploadedFile $file
     *
     * @return string fileName
     **/
    public function generateFileName(UploadedFile $file)
    {
        return $file->getClientOriginalName();
    }

    public function createAttachment($originalName, $uploadedURL, $type) {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $attachment = new Attachment();
        $attachment->setUserId($user->getId());
        $attachment->setName($originalName);
        $attachment->setUrl($uploadedURL);
        $attachment->setType($type);

        $this->em->persist($attachment);
        $this->em->flush();

        return $attachment;
    }

    public function getEntity($id) {
        return $this->em->getRepository('GaoC5Bundle:Attachment')->find($id);
    }

    public function deleteEntity($attachment) {
        $this->em->remove($attachment);
        $this->em->flush();
    }

    /**
     * get transaction from user
     *
     * @param int $referId The id of refer
     * @param int $userId  The id of user
     *
     * @return array $attachment Is an array of Attachment
     */
    public function getAttachmentByRefer($referId, $userId)
    {
        try {
            $query = <<<SQL
SELECT
    a.id, a.name, a.url
FROM
    attachment a
WHERE
    a.refer_id = ? AND
    a.user_id = ?
SQL;
            $stmt = $this->em->getConnection()->prepare($query);
            $stmt->bindValue(1, $referId, \PDO::PARAM_INT);
            $stmt->bindValue(2, $userId, \PDO::PARAM_INT);
            $stmt->execute();

            $list = $stmt->fetchAll();
    
            return $list;
            //None record found exception
        } catch (\Exception $e) {
            new BizException('No record found...');
        }
    }

    public function updateAttachment($userId, $referId, $attachment) {
        $attachment_array = [];
        foreach ($attachment as $id) {
            $record = $this->em->getRepository('GaoC5Bundle:Attachment')->find((int)$id);
            if (!empty($record) && $record->getUserId() == $userId) {
                $record->setReferId($referId);

                $this->em->persist($record);
                $this->em->flush();

                $attachment_array[] = ['id' => $record->getId(), 'name' => $record->getName(), 'url' => $record->getUrl()];
            }
        }

        return $attachment_array;
    }
}
