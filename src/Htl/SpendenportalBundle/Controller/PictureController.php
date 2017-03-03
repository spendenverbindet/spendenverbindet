<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PictureController extends Controller
{
    public function listFromProjectAction($projectId){

        $repository = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);
        $picture = $repository->getPicture();

        $responseArray = array();

        for($i=0;$i<count($picture);$i++){
            $item = array(
                "id"=>$picture[$i]->getId(),
                "pictureUrl"=>$picture[$i]->getPictureUrl(),
                "pictureUrl"=>$picture[$i]->getPictureUrl(),
                "created_at"=>$picture[$i]->getCreatedAt());
            array_push($responseArray, $item);
        }

        $responseArray = (object) $responseArray;

        return new JsonResponse($responseArray);
    }

    public function createPictureAction ($projectId, $pictureUrl) {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $projectId = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $date = new \DateTime('now');

            if (false) {
                throw $this->createNotFoundException(
                    'No category found for id  or not User found for id '
                );
            } else {

                $picture = new Picture();
                $picture->setPictureUrl($pictureUrl);
                $picture->setCreatedAt($date);
                $picture->setProjects($projectId);


                $em = $this->getDoctrine()->getManager();

                // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $em->persist($picture);

                // actually executes the queries (i.e. the INSERT query)
                $em->flush();

                return new \Symfony\Component\HttpFoundation\Response('Inserted Picture successful');
            }
            return new JsonResponse('not logged in');
        }
    }

    public function updateAction($pictureId,$newUrl){

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $em = $this->getDoctrine()->getManager();
            $picture = $em->getRepository('HtlSpendenportalBundle:Category')->find($pictureId);

            if (!$picture) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $pictureId
                );
            }

            $picture->setName($newUrl);

            $em->flush();

            return new Response('Updated picture successful');
        }
        return new JsonResponse('not logged in');
    }

    public function deleteAction($pictureId)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $em = $this->getDoctrine()->getManager();
            $picture = $em->getRepository('HtlSpendenportalBundle:Picture')->find($pictureId);

            if (!$picture) {
                throw $this->createNotFoundException(
                    'No category found for id ' . $pictureId
                );
            }

            /* Schauen ob es für dieses Picture childs existieren! */


            $em->remove($picture);
            $em->flush();


            return new JsonResponse('Picture has been deleted!');
        }
        return new JsonResponse('not logged in');
    }
}
