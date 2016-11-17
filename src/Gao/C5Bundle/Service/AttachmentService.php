<?php

/**
 * @category Service
 *
 * @author KienDV
 *
 * @version 1.0
 */

namespace Gao\C5Bundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * AttachmentService class.
 *
 * Common Service.
 */
class AttachmentService
{
    /**
     * Container Interface.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container The Container Interface.
     */
    public function __construct(Container $container)
    {
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
}
