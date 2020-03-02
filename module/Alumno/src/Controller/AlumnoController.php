<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Alumno\Controller;

use Alumno\Model\AlumnoTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Alumno\Form\AlumnoForm;
use Alumno\Entity\Alumno;
use Zend\View\Model\JsonModel;

class AlumnoController extends AbstractActionController
{
    private $table;

    public function __construct(AlumnoTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'alumnos' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AlumnoForm();
        $form->get('submit')->setValue('Agregar');
        $form->setAttribute('id', 'alumnoform');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $alumno = new Alumno();
        $form->setInputFilter($alumno->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $alumno->exchangeArray($form->getData());
        $this->table->saveAlumno($alumno);
        return $this->redirect()->toRoute('alumno');
    }

    public function editAction()
    {
        $run = $this->params()->fromRoute('run', 0);

        if ('' === $run) {
            return $this->redirect()->toRoute('alumno', ['action' => 'add']);
        }

        try {
            $alumno = $this->table->getAlumno($run);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('alumno', ['action' => 'alumno']);
        }

        $form = new AlumnoForm();
        $form->bind($alumno);
        $form->get('submit')->setAttribute('value', 'Modificar');
        $form->setAttribute('id', 'alumnoform');

        $request = $this->getRequest();
        $viewData = ['run' => $run, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($alumno->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveAlumno($alumno);
        return $this->redirect()->toRoute('alumno');
    }

    public function deleteAction()
    {
        $run = (String) $this->params()->fromRoute('run', 0);
        if (!$run) {
            return $this->redirect()->toRoute('alumno');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Sí') {
                $run = (String) $request->getPost('run');
                $this->table->deleteAlumno($run);
            }

            return $this->redirect()->toRoute('alumno');
        }

        return [
            'run'    => $run,
            'alumno' => $this->table->getAlumno($run),
        ];
    }

    public function eliminarAction()
    {
        $run = (String) $this->params()->fromRoute('run', 0);
        if (!$run) {
            return $this->redirect()->toRoute('alumno');
        }

        $view = new JsonModel(array('data' => 0));

        if($this->table->deleteAlumno($run) == true)
        {
            $view = new JsonModel(array('data' => 1)); 
            $view->setTerminal(true);

            /*return $this->redirect()->toRoute('alumno');

            return [
                'run'    => $run,
                'alumno' => $this->table->getAlumno($run),
            ];*/
        }

        return $view;
    }

    public function modificarAction()
    {
        $run = $this->params()->fromRoute('run', 0);
        $nombre = $this->params()->fromRoute('nombre', 0);
        $apellido = $this->params()->fromRoute('apellido', 0);

        $alumno = new Alumno();

        $alumno->run = $run;
        $alumno->nombre = $nombre;
        $alumno->apellido = $apellido;

        $view = new JsonModel(array('data' => $run));
        $view->setTerminal(true);

        try
        {
            //$alumno2 = $this->table->getAlumno($run);

            if($this->table->saveAlumno($alumno) == true)
            {
                $view = new JsonModel(array('data' => 1)); 
            }
        }
        catch (\Exception $e)
        {
            $view = new JsonModel(array('data' => $run.$nombre.$apellido));
        }

        return $view;
    }

    public function cargarAction(){
        $alumnos = $this->table->fetchAll();

        $request = $this->getRequest(); 
        $query = $request->getQuery();
        $view = new JsonModel(array('nombre' => 'holaaaa'));

        if ($request->isXmlHttpRequest() || $query->get('showJson') == 1)
        {
            $jsonData = array();
            $idx = 0;
            
            foreach ($alumnos as $alumno){
                $temp = array( 
                    'run' => $alumno->run, 
                    'nombre' => $alumno->nombre, 
                    'apellido' => $alumno->apellido 
                 );  
                 $jsonData[$idx++] = $temp;
            }

            $view = new JsonModel($jsonData); 
            $view->setTerminal(true);
        }
        else
        {
            $view = new ViewModel();
        }

        return $view;
    }
}