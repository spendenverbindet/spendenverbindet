<?php

namespace Htl\SpendenportalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Htl\SpendenportalBundle\Entity\Picture;

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

    public function insertMultiplePictures($pictureArray, $projectId){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $form = $this->createFormBuilder()
                ->add('pictureUrl')
                ->getForm();

        
                
                $date = new \DateTime('now');

                $items = false;

                $em = $this->getDoctrine()->getManager();

                foreach ($pictureArray as $item) {
                    $picture = new Picture();

                    $picture->setPictureUrl($item['pictureUrl']);
                    $picture->setCreatedAt($date);
                    $picture->setProjects($projectId);

                    $em->persist($picture);

                    // flush everything to the database every 20 inserts

                    $em->flush();
                    $em->clear();                     
            }
            return new JsonResponse('no files');
        }
        return false;
    }

    public function createPictureAction ($projectId) {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RECEIVER')) {

            $project = $this->getDoctrine()->getRepository('HtlSpendenportalBundle:Project')->find($projectId);

            $date = new \DateTime('now');

            if (false) {
                throw $this->createNotFoundException(
                    'No category found for id  or not User found for id '
                );
            } else {

                $picture = new Picture();
                $picture->setPictureUrl($_FILES['pictureUrl']['name']);
                $picture->setCreatedAt($date);
                $picture->setProjects($project);


                $em = $this->getDoctrine()->getManager();

                //file upload
                $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';
                $target_file = $target_dir . basename($_FILES["picture"]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                // Check if image file is a actual image or fake image
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["picture"]["tmp_name"]);
                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                        return new JsonResponse("File is not an image.");
                    }
                }
                // Check file size
                if ($_FILES["picture"]["size"] > 6000000) {
                    $uploadOk = 0;
                    return new JsonResponse("Sorry, your file is too large. Maximal 750kB");
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"
                    && $imageFileType != "GIF" && $imageFileType != ""
                ) {
                    $uploadOk = 0;
                    return new JsonResponse("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    return new JsonResponse("Sorry, your file was not uploaded.");
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    } else {
                        return new JsonResponse("Sorry, there was an error uploading your file.");
                    }
                }

                // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $em->persist($picture);

                // actually executes the queries (i.e. the INSERT query)
                $em->flush();
                
                return $this->redirectToRoute('htl_spendenportal_projekt_bearbeiten');
            }
            return new JsonResponse('not logged in');
        }
    }

    /*
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
    */

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

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bundles/htlspendenportal/img/';

            if(unlink($target_dir.$picture->getPictureUrl())){
                $em->remove($picture);
            }

            //$em->remove($picture);
            $em->flush();


        return $this->redirectToRoute('htl_spendenportal_projekt_bearbeiten');
        }
        return new JsonResponse('not logged in');
    }
}
