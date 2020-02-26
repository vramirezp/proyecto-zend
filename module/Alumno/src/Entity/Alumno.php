<?php
    namespace Alumno\Entity;

    use RuntimeException;
    use Zend\Db\TableGateway\TableGatewayInterface;

    use DomainException;
    use Zend\Filter\StringTrim;
    use Zend\Filter\StripTags;
    use Zend\Filter\ToInt;
    use Zend\InputFilter\InputFilter;
    use Zend\InputFilter\InputFilterAwareInterface;
    use Zend\InputFilter\InputFilterInterface;
    use Zend\Validator\StringLength;
    
    class Alumno implements InputFilterAwareInterface
    {
        public $run;
        public $nombre;
        public $apellido;

        private $inputFilter;

        public function exchangeArray(array $data)
        {
            $this->run = !empty($data['run']) ? $data['run'] : null;
            $this->nombre = !empty($data['nombre']) ? $data['nombre'] : null;
            $this->apellido = !empty($data['apellido']) ? $data['apellido'] : null;
        }

        public function getArrayCopy()
        {
            return [
                'run'      => $this->run,
                'nombre'   => $this->nombre,
                'apellido' => $this->apellido,
            ];
        }

        public function setInputFilter(InputFilterInterface $inputFilter)
        {
            throw new DomainException(sprintf(
                '%s does not allow injection of an alternate input filter',
                __CLASS__
            ));
        }

        public function getInputFilter()
        {
            if ($this->inputFilter) {
                return $this->inputFilter;
            }

            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name' => 'run',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'nombre',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'apellido',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $this->inputFilter = $inputFilter;
            return $this->inputFilter;
        }
    }
?>