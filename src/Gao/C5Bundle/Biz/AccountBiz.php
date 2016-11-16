<?php

namespace Gao\C5Bundle\Biz;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\C5Bundle\Biz\BizException;

/**
 * Class: AccountBiz.
 */
class AccountBiz
{
    /**
     * Service Container Interface.
     */
    private $container;

    /**
     * __construct.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function main()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $post = Request::createFromGlobals();
        if ($post->request->has('submit')) {
            $pin_number = $post->request->get('pin_number');
            $data = [
                //'oldPassword' => $post->request->get('oldPassword'),
                'newPassword' => $post->request->get('newPassword'),
                'retypePassword' => $post->request->get('retypePassword'),
                'fullName' => $post->request->get('fullName'),
                'vcbAccNumber' => $post->request->get('vcbAccNumber'),
                'phone' => $post->request->get('phone'),
                'email' => $post->request->get('email')
            ];
            $this->formProcess($user, $data);
        }

        $params = $this->prepareData($user);

        return $params;
    }

    private function prepareData($user) {

        return array(
            'vcbAccNumber' => $user->getVcbAccNumber(),
            'fullName' => $user->getFullName(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail()
        );
    }

    private function formProcess(&$user, $data) {
        $session = $this->container->get('request')->getSession();
        try {
            $update = false;

            // Validate
            if ($data['newPassword']) {
                if ($data['newPassword'] != $data['retypePassword']) {
                    $session->getFlashBag()->add('unsuccess', 'Password khong trung nhau, vui long nhap lai.');
                    return;
                }
            }

            if ($data['newPassword']) {
                // Set encrypted password
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($data['newPassword'], $user->getSalt());
                $user->setPassword($password);
                $update = true;
            }

            if ($data['fullName'] && $data['fullName'] != $user->getFullName()) {
                $user->setFullName($data['fullName']);
                $update = true;
            }

            if ($data['phone'] && $data['phone'] != $user->getPhone()) {
                $user->setPhone($data['phone']);
                $update = true;
            }

            if ($data['email'] && $data['email'] != $user->getEmail()) {
                $user->setEmail($data['email']);
                $update = true;
            }

            if ($data['vcbAccNumber'] && $data['vcbAccNumber'] != $user->getVcbAccNumber()) {
                $user->setVcbAccNumber($data['vcbAccNumber']);
                $update = true;
            }

            if ($update) {
                $this->container->get('security.context')->getToken()->setUser($user);
                $em = $this->container->get('doctrine')->getManager();
                $em->persist($user);
                $em->flush();

                $session->getFlashBag()->add('success', 'Cap nhat thanh cong.');
            }
        } catch (\Exception $ex) {
            $session->getFlashBag()->add('unsuccess', 'Cap nhat khong thanh cong.');
        }
    }
}
