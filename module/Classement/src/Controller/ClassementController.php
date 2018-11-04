<?php

namespace Classement\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ClassementController extends AbstractActionController
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function generalAction()
    {
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findBy(array(), array('time' => 'ASC'));

        return new ViewModel(
            array(
                "participants" => $participants
            )
        );
    }

    public function hommesAction()
    {
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findBy(array('sex' => 'male'), array('time' => 'ASC'));

        return new ViewModel(
            array(
                "participants" => $participants
            )
        );
    }

    public function femmesAction()
    {
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findBy(array('sex' => 'female'), array('time' => 'ASC'));

        return new ViewModel(
            array(
                "participants" => $participants
            )
        );
    }
}
