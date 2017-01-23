<?php

namespace Book\Model\Form;

use Zend\Form\Annotation\Hydrator;
use Zend\Form\Form;

class BookForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('book');
        $this->setAttribute('method', 'post');

        //$this->setHydrator(new ClassMethods);
        //$this->setHydrator(new Hydrator());

        $this->add([
            'name' => 'id',
            'attributes' => [
                'type'  => 'hidden',
            ],
        ]);

        $this->add([
            'name' => 'title',
            'attributes' => [
                'type'  => 'text',
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'isbn',
            'attributes' => [
                'type'  => 'text',
            ],
            'options' => [
                'label' => 'ISBN',
            ],
        ]);

        $this->add([
            'name' => 'author',
            'attributes' => [
                'type'  => 'text',
            ],
            'options' => [
                'label' => 'Author',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }
}