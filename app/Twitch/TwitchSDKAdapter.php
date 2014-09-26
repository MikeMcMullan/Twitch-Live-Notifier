<?php

namespace Twitch;

use Illuminate\Support\Collection;
use ritero\SDK\TwitchTV\TwitchSDK;

class TwitchSDKAdapter {

    /**
     * @var TwitchSDK
     */
    private $sdk;

    /**
     * @param TwitchSDK $sdk
     */
    public function __construct(TwitchSDK $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @param $object
     * @return Collection
     */
    private function convertToCollection($object)
    {
        return new Collection(json_decode(json_encode($object), true));
    }

    /**
     * @param $name
     * @param $args
     * @return Collection
     * @throws BadMethodCallException
     */
    public function __call($name, $args)
    {
        if (method_exists($this->sdk, $name))
        {
            $response = call_user_func_array([$this->sdk, $name], $args);

            return $this->convertToCollection($response);
        }

        throw new BadMethodCallException;
    }

} 