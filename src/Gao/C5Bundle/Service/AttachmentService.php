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

    public function getAttachmentByIds($ids) {
        $this->em->getRepository('GaoC5Bundle:Attachment')->findBy(array('id' => $ids));
    }

    public function updateAttachment($userId, $referId, $attachment) {
        foreach ($attachment as $id) {
            $record = $this->em->getRepository('GaoC5Bundle:Attachment')->find((int)$id);
            if ($record->getUserId() == $userId) {
                $record->setReferId($referId);
            }
            $this->em->persist($record);
            $this->em->flush();
        }
    }
}
