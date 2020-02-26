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

        // Retrieve the alumno with the specified run. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $alumno = $this->table->getAlumno($run);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('alumno', ['action' => 'alumno']);
        }

        $form = new AlumnoForm();
        $form->bind($alumno);
        $form->get('submit')->setAttribute('value', 'Modificar');

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
}