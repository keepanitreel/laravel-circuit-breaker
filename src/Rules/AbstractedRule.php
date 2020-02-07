<?php

namespace Keepanitreel\CircuitBreaker\Rules;


use Keepanitreel\CircuitBreaker\Contracts\TripContract;
use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;
use Closure;

class AbstractedRule implements TripContract
{

    protected $circuit;

//    public function handle($circuit, Closure $next) :bool
//    {
//       return true;
//    }

    public function setCircuit($value)
    {
        $this->circuit = $value;
        return $this;
    }

    public function getCircuit(): CircuitBreaker
    {
        return $this->circuit;
    }
}
