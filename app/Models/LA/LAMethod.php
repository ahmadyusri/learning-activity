<?php

namespace App\Models\LA;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAMethod extends Model
{
    use SoftDeletes;

    public $connection = 'pgsql';

    protected $table = 'la_method';

    public $incrementing = true;

    public $fillable = ['name', 'order', 'created_by', 'updated_by'];

    protected $db_rules = [
        'name'  => 'required|min:2|max:100|unique:la_method,name',
        'order' => 'required|numeric|min:1|max:255|unique:la_method,order',
    ];

    public function getDBRules($id = null)
    {
        $db_rules = $this->db_rules;
        if ($id != null) {
            $db_rules['name'] = "required|min:2|max:100|unique:la_method,name,$id";
            $db_rules['order'] = "required|numeric|min:1|max:255|unique:la_method,order,$id";
        }

        return $db_rules;
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function activity()
    {
        return $this->hasMany(\App\Models\LA\LAActivity::class, 'method_id');
    }
}
