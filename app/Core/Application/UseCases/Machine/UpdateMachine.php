<?php

namespace App\Core\Application\UseCases\Machine;

use App\Core\Domain\Repositories\MachineRepositoryInterface;
use App\Core\Domain\Entities\Machine;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateMachine
{
    public function __construct(
        private MachineRepositoryInterface $repository
    ) {}

    public function execute(string $id, array $data): Machine
    {
        $machine = $this->repository->findById($id);

        if (!$machine) {
            throw new NotFoundHttpException("Máquina no encontrada");
        }

        // Actualización parcial de campos
        if (isset($data['status'])) {
            $machine->updateStatus($data['status']);
        }

        if (isset($data['current_status'])) {
            $machine->updateStatus($data['current_status']);
        }

        if (isset($data['name'])) {
            // Asumiendo que añadiremos setName o similar si es necesario
            // Por ahora priorizamos status según requerimiento
        }

        $this->repository->save($machine);

        return $machine;
    }
}
