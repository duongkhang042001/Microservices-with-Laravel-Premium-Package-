<?php

namespace App\Rules;

use App\Containers\MetricContainer;
use App\Enums\Rules;
use InvalidArgumentException;

final class RuleFactory
{
    public function create(MetricContainer $metric): Rule
    {
        return match($metric->scoreRule) {
            Rules::UPWARD => new UpwardRule(),
            Rules::DOWNWARD => new DownwardRule(),
            default => throw new InvalidArgumentException(
                'No rule found for: ' . $metric->scoreRule
            )
        };
    }
}
