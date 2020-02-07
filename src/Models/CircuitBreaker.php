<?php

namespace Keepanitreel\CircuitBreaker\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CircuitBreaker
 * @package Keepanitreel\CircuitBreaker\Models
 * @property string name
 */
class CircuitBreaker extends Model
{
use SoftDeletes;
    protected $fillable =['name','failed'];
    protected $casts =['failed'=>'bool'];

    public $table = 'circuit_breaker';

    public static function attempt($circuit_name, $success = true){

        return CircuitBreaker::create([
                'name'=>$circuit_name,
                'failed'=>!$success]);
    }



}
