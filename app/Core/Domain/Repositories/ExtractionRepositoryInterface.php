<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\Extraction;

interface ExtractionRepositoryInterface
{
    public function findById(string $id): ?Extraction;
    
    /**
     * Recupera el historial de extracciones de una máquina, 
     * ordenado cronológicamente de forma descendente.
     */
    public function findByMachineHistory(string $machineId, int $limit = 50): array;
    
    /**
     * Recupera la última extracción registrada para una máquina.
     */
    public function findLatestByMachine(string $machineId): ?Extraction;
    
    public function save(Extraction $extraction): void;
    
    public function delete(string $id): void;
}
