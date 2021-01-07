<?php

namespace Marshmallow\ZohoDesk\Models\Traits;

trait ZohoMakeable
{
    public static function make($attributes)
    {
        $model = new self();
        foreach ($attributes as $key => $value) {
            $model->{$key} = $value;
        }

        return $model;
    }
}
