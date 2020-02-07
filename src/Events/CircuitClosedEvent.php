<?php

namespace Keepanitreel\CircuitBreaker\Events;


use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;

class CircuitClosedEvent
{
    /**
     * @var \Keepanitreel\CircuitBreaker\Models\CircuitBreaker
     */
    public $circuit_breaker;

    public function __construct(CircuitBreaker $circuit_breaker)
    {
        $this->circuit_breaker = $circuit_breaker;
    }
}
