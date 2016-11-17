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

            $status = 'success';
            $uploadedURL = '';
            $message = '';

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
                    } else {
                        $status = 'failed';
                        $message = 'Invalid File Type';
                    }
                } else {
                    $status = 'failed';
                    $message = 'Size exceeds limit';
                }
            } else {
                $status = 'failed';
                $message = 'File Error';
            }

            return new JsonResponse(array(
                'status' => $status,
                'message' => $message,
                'uploadedURL' => $uploadedURL
            ));
        }
    }
}

?>
