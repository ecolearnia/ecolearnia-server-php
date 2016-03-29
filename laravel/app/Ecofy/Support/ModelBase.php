<?php
namespace App\Ecofy\Support;

use Illuminate\Database\Eloquent\Model;
use Validator;

abstract class ModelBase extends Model
{

    /**
     * String that represents this model instance.
     * For human reading, no need to be unique.
     */
    public function _name()
    {
        return $this->uuid;
    }
}
