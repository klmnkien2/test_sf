<?php

/**
 * @category  Controller
 * @author    KienDV
 * @version   1.0
 */
namespace Gao\C5Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: AttachmentController
 *
 * @see Controller
 */
class AttachmentController extends Controller
{

    /**
     * uploadAction
     *
     * @param string $folder Folder to upload file
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function uploadAction($folder)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $reqFile = $request->files->get('uploadFile');
            if (is_array($reqFile)) {
                $reqFile = $reqFile[0];
            }

            $error = false;
            $attachment = null;

            $MAX_FILE_SIZE = 2000000000;

            $IMG_FILE_TYPES = array(
                'jpg',
                'jpeg',
                'bmp',
                'png'
            );

            if (($reqFile instanceof UploadedFile) && ($reqFile->getError() == '0')) {
                if (($reqFile->getSize() < $MAX_FILE_SIZE)) {
                    $originalName = $reqFile->getClientOriginalName();
                    $message = $originalName; // In case of success the message is client orignal name
                    $name_array = explode('.', $originalName);
                    $file_type = $name_array[sizeof($name_array) - 1];

                    if (in_array(strtolower($file_type), $IMG_FILE_TYPES)) {
                        // Start Uploading File
                        $uploadFileName = $this->get('attachment_service')->generateFileName($reqFile);
                        $uploadedURL = $this->get('attachment_service')->moveUploadedFile($reqFile, $folder ? $folder : 'default', $uploadFileName);

                        // create database record
                        $attachment = $this->get('attachment_service')->createAttachment($originalName, $uploadedURL, $folder ? $folder : 'default');

                        if (empty($attachment) || empty($attachment->getId())) {
                            $error = 'Can not insert attachment to database';
                        }
                    } else {
                        $error = 'Invalid File Type';
                    }
                } else {
                    $error = 'Size exceeds limit';
                }
            } else {
                $error = 'File Error';
            }

            return new JsonResponse(array(
                'error' => $error,
                'attachment' => array(
                    'id' => $attachment->getId(),
                    'name' => $attachment->getName(),
                    'url' => $attachment->getUrl()
                 )
            ));
        }
    }


    /**
     * deleteAction
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction()
    {
        $error = false;
        try {
            $request = $this->get('request');
            $id = $request->get('id');
            $token = $request->get('token');
            if (!$this->get('form.csrf_provider')->isCsrfTokenValid('attachment', $token)) {
                throw new BizExcetion("token wrong!");
            }
            $attachment = $this->get('attachment_service')->getEntity($id);
            if (empty($attachment)) {
                throw new BizExcetion("Attachment does not exist.");
            }
            $this->get('attachment_service')->deleteEntity($attachment);
        } catch (BizException $ex) {
            $error = $ex->getMessage();
        }

        return new JsonResponse(array(
            'error' => $error
        ));
    }
}

?>
