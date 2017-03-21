<?php
// src/Htl/SpendenportalBundle/Controller/RegistrationController.php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);


        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);



                $erg = $user->getIsDonator();

                if($erg == 0){
                    $rolesArr = array('ROLE_RECEIVER');
                    $user->setRoles($rolesArr);


                    // pdf-file start

                    // $file stores the uploaded PDF file
                    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                    $file = $user->getFileUpload();

                    $originalFileName = $file->getClientOriginalName();

                    // Generate a unique name for the file before saving it
                    $fileName = $originalFileName.'_'.md5(uniqid()).'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('brochures_directory'),
                        $fileName
                    );

                    // Update the 'pdf' property to store the PDF file name
                    // instead of its contents
                    $user->setFileUpload($fileName);

                    // pdf file end




                }else{
                    $rolesArr = array('ROLE_DONATOR');
                    $user->setRoles($rolesArr);
                }


                //  persist the $user variable
                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }




    /**
     * Dont send the email, show him the message that we should proof his pdf file first and his age
     */
    public function checkEmailAction()
    {


        $email2 = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $user2 = $this->get('fos_user.user_manager')->findUserByEmail($email2);


        if($user2->getIsDonator() == true){


            // User ist ein Spender, also confirm email senden!

            $email = $this->get('session')->get('fos_user_send_confirmation_email/email');

            if (empty($email)) {
                return new RedirectResponse($this->get('router')->generate('fos_user_registration_register'));
            }

            $this->get('session')->remove('fos_user_send_confirmation_email/email');
            $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

            if (null === $user) {
                throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
            }

            return $this->render('HtlSpendenportalBundle::check_email.html.twig');


        }else{

            // User ist ein Empfänger, also keine email senden ! Nur View rendern

            return $this->render('HtlSpendenportalBundle::proof_data.html.twig');

        }

    }


    
    /**
     * Tell the user his account is now confirmed.
     */
    public function confirmedAction()
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('HtlSpendenportalBundle::account_confirmed.html.twig');
    }


}