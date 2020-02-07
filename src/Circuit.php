<?php
namespace Keepanitreel\CircuitBreaker;

use Carbon\Carbon;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

use Keepanitreel\CircuitBreaker\Models\CircuitBreaker;

class Circuit
{

   public function getCircuits() : array{

        $circuit_state = [];
        foreach (array_keys(config('circuit-breaker.circuits')) as $circuit){
            $circuit_state[$circuit] = Cache::get("circuit-breaker:$circuit",true);
        }
        return $circuit_state;
    }

    public function attempt($circuit_name, $success = true) :bool {

        $this->validate(CircuitBreaker::attempt($circuit_name, $success));

        return $this->check($circuit_name);
    }

    public function getTripRules($circuit_name){
        return Arr::get(config('circuit-breaker.circuits'),$circuit_name.'.trip', []);
    }

    public function getCooldownRules($circuit_name){
        return Arr::get(config('circuit-breaker.circuits'),$circuit_name.'.cooldown', []);
    }

    public function getLatestCircuit($circuit_name){
        return CircuitBreaker::latest()->where('name',$circuit_name)->first();
    }

    public function hasCooledDown($circuit_name){
        $rules = $this->getCooldownRules($circuit_name);

       if(empty($rules)){
           return false;
       }
        return (app(Pipeline::class))
            ->send($circuit_name)
            ->through(array_keys($rules))
            ->then(function($circuit_name){
                return !empty($circuit_name);
            });
    }

    public function validate($circuit){

       if(is_string($circuit)){
           $circuit = CircuitBreaker::latest()->where('name',$circuit)->first();
       }
       if(empty($circuit)){
           return true;
       }

        return (app(Pipeline::class))
            ->send($circuit)
            ->through(array_keys($this->getTripRules($circuit->name)))
            ->then(function($d){
                return $d;
            }) instanceof CircuitBreaker ? true : false;
    }


    /**
     * Enable the circuit for usage
     * @param string $circuit_name
     *
     * @return bool
     */
    public function enable(string $circuit_name):bool{
        $this->delete($circuit_name);
        return Cache::forever("circuit-breaker:$circuit_name",true);
    }

    /**
     * Disable the circuit for usage
     * @param string $circuit_name
     *
     * @return bool
     */
    public function disable(string $circuit_name) : bool{

        return Cache::forever("circuit-breaker:$circuit_name",false);
    }

    /**
     * Get the current state of a circuit
     * @param string $circuit_name
     *
     * @return bool
     */
    public function check(string $circuit_name) :bool
    {
        return Cache::get("circuit-breaker:$circuit_name", true);
    }

    /**
     * Force Delete all the data
     * @param \Carbon\Carbon $time
     * @param string|null    $circuit_name
     *
     * @return bool
     */
    public function truncate(Carbon $time, string $circuit_name = null) :bool{
        $query = CircuitBreaker::where('created_at','<=', $time->toDateTime());
            if(!is_null($circuit_name)){
                $query->where('name', $circuit_name);
            }
        return $query->forceDelete();
    }

    public function delete($circuit_name){
      return CircuitBreaker::where('name', $circuit_name)->delete();
    }

}
