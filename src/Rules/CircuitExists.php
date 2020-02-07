<?php

namespace Keepanitreel\CircuitBreaker\Rules;


class CircuitExists
{
public function handle($circuit, \Closure $next){

    if($circuit){
        return $next($circuit);
    }
    return false;

}
}
