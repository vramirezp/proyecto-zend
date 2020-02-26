<?php
namespace Alumno\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Alumno\Entity\Alumno;

class AlumnoTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAlumno($run)
    {
        $run = (String) $run;
        $rowset = $this->tableGateway->select(['run' => $run]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se encontró el RUN: %d',
                $run
            ));
        }

        return $row;
    }

    public function saveAlumno(Alumno $alumno)
    {
        $data = [
            'run' => $alumno->run,
            'nombre' => $alumno->nombre,
            'apellido'  => $alumno->apellido,
        ];

        $run = $alumno->run;

        /*if($run === 0){
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAlumno($run);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'No se puede modificar el alumno con id %d; does not exist',
                $run
            ));
        }*/

        try
        {
            $this->getAlumno($run);
            $this->tableGateway->update($data, ['run' => $run]);
        }
        catch (RuntimeException $e)
        {
            $this->tableGateway->insert($data);
            return;
        }
    }

    public function deleteAlumno($run)
    {
        $this->tableGateway->delete(['run' => (String) $run]);
    }
}
?>