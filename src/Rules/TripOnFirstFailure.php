<?php

namespace Keepanitreel\CircuitBreaker\Rules;
use Closure;
use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;

class TripOnFirstFailure extends AbstractedRule
{

    public function handle(CircuitBreaker $circuit , Closure $next)
    {
        if(!$circuit->failed){
            return  $next($circuit);
        }
        return null;
    }
}
