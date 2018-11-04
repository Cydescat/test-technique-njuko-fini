<?php

namespace Participant\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ParticipantController extends AbstractActionController
{

    /** @var EntityManager $entityManager */
    private $entityManager;
    private $formElementManager;

    public function __construct($entityManager, $formElementManager)
    {
        $this->entityManager = $entityManager;
        $this->formElementManager = $formElementManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function listAction()
    {
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();

        return new ViewModel(
            array(
                "participants" => $participants
            )
        );

    }

    public function participantFormAction(){

        /** @var \Zend\Form\Form $form */
        $form = $this->formElementManager->get('participant_form');

        //Extraction des évenements dans la base de données puis ajout d'un champ select dans le formulaire
        $eventsDatabase = $this->entityManager->getRepository('Application\Entity\Event')->findAll();
        $eventsArray = [];

        foreach ($eventsDatabase as $eventDatabase)
            $eventsArray[$eventDatabase->getId()] = $eventDatabase->getName();
        $form->add([
            'name'    => 'event',
            'type'    => 'Zend\Form\Element\Select',
            'options'    => [
                'label'            => 'Evenement',
                'value_options'    => $eventsArray
            ],
        ]);



        $id = (int) $this->params()->fromRoute('id', 0);

        /** @var \Application\Entity\Participant $participant */
        if (0 !== $id)
        {
            try
            {
                $participant = $this->entityManager->getRepository('Application\Entity\Participant')->find($id);
                $form->bind($participant);
            } catch (\Exception $e)
            {
                return $this->redirect()->toRoute('participant/list');
            }
        }

        /** @var Request $request */
        $request = $this->getRequest();

        if (!$request->isPost())
            return ['form' => $form];

        // On vérifie et corrige si nécéssaire le probleme des entrées finissant par 00 secondes sur Chrome
        $checkTime = $request->getPost('time');

        if (strlen($checkTime) < 6)
        {
            $checkTime .= ':00';
            $request->getPost()->set('time', $checkTime);
        }

        $form->setData($request->getPost());

        if (!$form->isValid())
        {
            return ['form' => $form];
        }
        else
        {
            $participant = $form->getData();
            try
            {
                $this->entityManager->persist($participant);
                $this->entityManager->flush();
            } catch (ORMException $e)
            {
            }

            return $this->redirect()->toRoute('participant/list');
        }


    }

    public function generateBibNumbersAction()
    {

        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();

        $bibNumber = 1;
        foreach ($participants as $participant)
        {
            if ($bibNumber <= 100)
            {
                $participant->setBibNumber($bibNumber);
                $this->entityManager->persist($participant);
            }
            else
                break;
            $bibNumber++;
        }
        $this->entityManager->flush();

        return $this->redirect()->toRoute('participant/list');

    }

    public function deleteAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        $participant = $this->entityManager->getRepository('Application\Entity\Participant')->find($id);
        try
        {
            $this->entityManager->remove($participant);
            $this->entityManager->flush();
        } catch (ORMException $e)
        {
        }
        return $this->redirect()->toRoute('participant/list');

    }
}
