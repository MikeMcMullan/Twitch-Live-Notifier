<?php

class Channel extends Eloquent {

    protected $fillable = ['user', 'name', 'is_live', 'display_name', 'game'];

    public function getIsLiveAttribute($value)
    {
        return (bool) $value;
    }

} 