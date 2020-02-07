<?php

namespace Keepanitreel\CircuitBreaker\Rules;


use Illuminate\Support\Arr;
use Keepanitreel\CircuitBreaker\Facades\Circuit;

class EnableAfterTime
{

    public function handle($circuit_name, \Closure $next)
    {
        $rules = Circuit::getCooldownRules($circuit_name);
        $data = Arr::get($rules,get_class($this));
        $time = Arr::get($data, 'time', 300);

        $circuit = Circuit::getLatestCircuit($circuit_name);

        if(empty($circuit) || $circuit->created_at <= now()->addSeconds($time)){
            return $next($circuit_name);
        }
        return false;
    }
}
