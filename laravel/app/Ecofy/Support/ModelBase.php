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
            //die($value);
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
       parent::setAttribute($key, $value);
       // If an attribute is listed as a "jsons", we'll convert it from a
       // string to json.
       if (isset($value) && (in_array($key, $this->jsons))) {
           $value = json_encode($value);
           $this->attributes[$key] = $value;
       }

       return $this;
   }

   /**
    * @overrides
    * Convert the model's attributes to an array.
    *
    * @return array
    */
   public function attributesToArray()
   {
       $attributes = parent::attributesToArray();
       foreach ($this->jsons as $key) {
           if (! isset($attributes[$key])) {
               continue;
           }

           $attributes[$key] = json_decode($attributes[$key]);
       }
       return $attributes;
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
