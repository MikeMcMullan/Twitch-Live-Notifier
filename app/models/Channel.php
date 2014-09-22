<?php

class Channel extends Eloquent {

    protected $fillable = ['user', 'name', 'is_live'];

    public function getIsLiveAttribute($value)
    {
        return (bool) $value;
    }

} 