<?php

namespace App\Models;

abstract class Model
{
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    public function __isset($name)
    {
        if (property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * Update db columns
     *
     * @param  array  $data
     */
    public function update(array $data)
    {
        foreach ($data as $column => $value) {
            $this->{$column} = $value;
        }
    }

    /**
     * Fill a new model with data that we need in order to save.
     *
     * @param  array  $data
     */
    public function fill(array $data)
    {
        $this->update($data);
    }
}