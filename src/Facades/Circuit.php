<?php

namespace Keepanitreel\CircuitBreaker\Facades;

use Illuminate\Support\Carbon;
use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;

/**
 * Class Circuit
 * @package Keepanitreel\CircuitBreaker\Facades
 * @method static attempt(string $name, bool $success = true)
 * @method static check(string $name)
 * @method static disable(string $name)
 * @method static getCircuits
 * @method static enable(string $name)
 * @method static truncate(Carbon $date, $name = null)
 * @method static validate(string|CircuitBreaker $circuit)
 * @method static hasCooledDown(string $circuit_name)
 */
class Circuit extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor() { return 'Circuit'; }
}
