<?php
namespace App\Ecofy\Support;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Validator;

abstract class ModelBase extends Model
{
    public $timestamps = false;

    /**
     * The attributes that should be mutated to json.
     *
     * @var array
     */
    protected $jsons = [];

    /**
     * Overriding to serialize into ISO date
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTime  $date
     * @return string
     */
    protected function serializeDate(DateTime $date)
    {
        return $date->format(DateTime::ATOM);
    }

    /**
     * Override to extend json
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);
        // If the attribute is listed as a jsons, we will convert it to array.
        if (in_array($key, $this->jsons) && ! is_null($value)) {
            return json_decode($value);
        }
        return $value;
    }

    /**
    * Override to extend json
    * Set a given attribute on the model.
    *
    * @param  string  $key
    * @param  mixed  $value
    * @return $this
    */
   public function setAttribute($key, $value)
   {
       parent::getAttributeValue($key, $value);
       // If an attribute is listed as a "date", we'll convert it from a DateTime
       // instance into a form proper for storage on the database tables using
       // the connection grammar's date format. We will auto set the values.
       if ($value && (in_array($key, $this->jsons))) {
           $value = json_encode($value);
       }
       $this->attributes[$key] = $value;
       return $this;
   }

    /**
     * String that represents this model instance.
     * For human reading, no need to be unique.
     */
    public function _name()
    {
        return $this->uuid;
    }
}
