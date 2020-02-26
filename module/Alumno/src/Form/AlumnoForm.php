<?php
    namespace Alumno\Form;

    use Zend\Form\Form;
    
    class AlumnoForm extends Form
    {
        public function __construct($name = null)
        {
            parent::__construct('alumno');
    
            $this->add([
                'name' => 'run',
                'type' => 'text',
                'options' => [
                    'label' => 'Run',
                ],
            ]);
            $this->add([
                'name' => 'nombre',
                'type' => 'text',
                'options' => [
                    'label' => 'Nombre',
                ],
            ]);
            $this->add([
                'name' => 'apellido',
                'type' => 'text',
                'options' => [
                    'label' => 'Apellido',
                ],
            ]);
            $this->add([
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => [
                    'value' => 'Guardar',
                    'id'    => 'submitbutton',
                ],
            ]);
        }
    }
?>