<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function setAttribute($key, $value)
    {
        if (property_exists($this, 'encrypted') 
            && in_array($key, $this->encrypted) 
            && !is_null($value)) {
            
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (property_exists($this, 'encrypted') 
            && in_array($key, $this->encrypted) 
            && !is_null($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
