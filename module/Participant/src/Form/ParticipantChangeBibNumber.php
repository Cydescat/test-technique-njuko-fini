<?php

namespace Participant\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ParticipantChangeBibNumber extends Form
{

    public function __construct($name = null)
    {

        parent::__construct('user');

        $this->setAttribute('class', 'form-horizontal');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'type' => 'Number',
            'name' => 'bibNumber',
            'options' => [
                'label' => 'bibNumber',
            ],
            'attributes' => [
                'min' => '1',
                'max' => '100',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'class' => 'btn btn-primary',
                'value' => 'Sauvegarder'
            ],
        ]);
    }
}