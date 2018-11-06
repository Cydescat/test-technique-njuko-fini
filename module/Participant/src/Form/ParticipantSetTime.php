<?php

namespace Participant\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ParticipantSetTime extends Form
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
            'type' => Element\Time::class,
            'name' => 'time',
            'options'=> [
                'label'  => 'Time',
                'format' => 'H:i:s',
            ],
            'attributes' => [
                'min' => '00:00:00',
                'max' => '99:59:59',
                'step' => '1', // seconds; default step interval is 60 seconds
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