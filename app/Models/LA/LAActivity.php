<?php

namespace App\Models\LA;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAActivity extends Model
{
    use SoftDeletes;

    public $connection = 'pgsql';

    protected $table = 'la_activity';

    public $incrementing = true;

    public $fillable = ['name', 'method_id', 'start_date', 'end_date', 'created_by', 'updated_by'];

    protected $db_rules = [
        'name' => 'required|min:2|max:100',
        'method_id' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function getDBRules($id = null, $method_id = null)
    {
        $db_rules = $this->db_rules;
        if ($id != null) {
            $db_rules['name'] = "required|min:2|max:100|unique:la_activity,name,$id";
        }
        if ($method_id != null) {
            $db_rules['method_id'] = "required|exists:la_method,id";
        }

        return $db_rules;
    }

    public function method()
    {
        return $this->belongsTo(\App\Models\LA\LAMethod::class, 'method_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
