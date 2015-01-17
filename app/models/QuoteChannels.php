<?php

class QuoteChannels extends Eloquent {

    public $timestamps = false;

    protected $fillable = ['key', 'name', 'display_name'];

    public function quotes()
    {
        return $this->hasMany('quote');
    }
}