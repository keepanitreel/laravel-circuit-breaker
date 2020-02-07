<?php

namespace Keepanitreel\CircuitBreaker\Rules;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Keepanitreel\CircuitBreaker\Facades\Circuit;
use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;
use Closure;
class TripOnAverageFailurePercentage extends AbstractedRule
{

    public function handle($circuit, Closure $next)
    {
        $data = Arr::get(config('circuit-breaker.circuits'),$circuit->name.'.trip.'.get_class($this));
        $percentage = Arr::get($data, 'percentage', 20);
        $range_in_seconds = Arr::get($data, 'range_in_seconds', 300);

        $query =$circuit->where('name',$circuit->name)
                      ->whereBetween('created_at',[
                          Carbon::now()->subSeconds($range_in_seconds + 60),
                          Carbon::now()
                      ]);

        $total = $query->count();
    if($total <= 0){
         return true;
    }
    $oldest = $query->first();
    $diff_in_seconds = now()->diffInSeconds($oldest->created_at);

    if($oldest && ($diff_in_seconds < now()->diffInSeconds($oldest->created_at))){
        return true;
    }

    $failed = $query->where('failed', true)->count();

    if(($failed/$total)*100 <= $percentage){
        return $next($circuit);
    }
    return null;

    }

}
