<?php

namespace App\Containers;

use Illuminate\Support\Str;

/**
 * Azért incoming, mert eventen keresztül beérkező statementeket tárol.
 * Amit a companies service állít elő kimenetként, az a sima
 * FinancialStatementContainer és common -ban van
 */
class IncomingFinancialStatementContainer
{
    /**
     * ['2020' => [
     *  'snake_case_key' => 'value'
     * ]]
     */
    public array $data = [];

    public function __construct(array $data)
    {
        foreach ($data as $year => $item) {
            $this->data[$year] = [];

            foreach ($item as $key => $value) {
                $snakeKey = Str::snake($key, '_');
                $this->data[$year][$snakeKey] = $value;
            }
        }
    }
}
