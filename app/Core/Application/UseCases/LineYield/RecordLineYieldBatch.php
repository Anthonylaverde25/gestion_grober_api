<?php

namespace App\Core\Application\UseCases\LineYield;

use App\Core\Application\DTOs\LineYield\RecordLineYieldBatchDTO;
use Illuminate\Support\Facades\DB;

class RecordLineYieldBatch
{
    public function __construct(
        private RecordLineYield $recordLineYield
    ) {}

    /**
     * @param RecordLineYieldBatchDTO $dto
     * @return array
     */
    public function execute(RecordLineYieldBatchDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $results = [];
            foreach ($dto->items as $itemDto) {
                $results[] = $this->recordLineYield->execute($itemDto);
            }
            return $results;
        });
    }
}
