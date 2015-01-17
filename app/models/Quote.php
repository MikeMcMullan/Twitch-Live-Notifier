<?php

class Quote extends Eloquent {

    protected $fillable = ['text'];

    public function channel()
    {
        return $this->belongsTo('QuoteChannels', 'id');
    }

}