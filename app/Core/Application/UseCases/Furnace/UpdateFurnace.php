<?php

namespace App\Core\Application\UseCases\Furnace;

use App\Core\Domain\Repositories\FurnaceRepositoryInterface;
use App\Core\Domain\Entities\Furnace;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateFurnace
{
    public function __construct(
        private FurnaceRepositoryInterface $repository
    ) {}

    public function execute(string $id, array $data): Furnace
    {
        $furnace = $this->repository->findById($id);

        if (!$furnace) {
            throw new NotFoundHttpException("Horno no encontrado");
        }

        // Actualización parcial de campos
        if (isset($data['status'])) {
            $furnace->updateStatus($data['status']);
        }

        // Si en el futuro necesitas actualizar otros campos, 
        // asegúrate de que la entidad Furnace tenga los métodos correspondientes.
        // Por ahora, el requerimiento principal es el status.


        $this->repository->save($furnace);

        return $furnace;
    }
}
