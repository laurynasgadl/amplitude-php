<?php

namespace Luur\Amplitude;

use JsonSerializable;
use ErrorException;

/**
 * @property string $user_id
 * @property string $device_id
 * @property string $event_type
 * @property int $time
 * @property array $event_properties
 * @property array $user_properties
 * @property array $groups
 * @property string $app_version
 * @property string $platform
 * @property string $os_name
 * @property string $os_version
 * @property string $device_brand
 * @property string $device_manufacturer
 * @property string $device_model
 * @property string $carrier
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $dma
 * @property string $language
 * @property float $price
 * @property int $quantity
 * @property float $revenue
 * @property string $productId
 * @property string $revenueType
 * @property float $location_lat
 * @property float $location_lng
 * @property string $ip
 * @property string $idfa
 * @property string $idfv
 * @property string $adid
 * @property string $android_id
 */
class Event implements JsonSerializable
{
    /**
     * Available event keys
     *
     * https://help.amplitude.com/hc/en-us/articles/204771828-HTTP-API#keys-for-the-event-argument
     *
     * @var array
     */
    const AVAILABLE_KEYS = [
        'user_id'             => 'string',
        'device_id'           => 'string',
        'event_type'          => 'string',
        'time'                => 'int',
        'event_properties'    => 'array',
        'user_properties'     => 'array',
        'groups'              => 'array',
        'app_version'         => 'string',
        'platform'            => 'string',
        'os_name'             => 'string',
        'os_version'          => 'string',
        'device_brand'        => 'string',
        'device_manufacturer' => 'string',
        'device_model'        => 'string',
        'carrier'             => 'string',
        'country'             => 'string',
        'region'              => 'string',
        'city'                => 'string',
        'dma'                 => 'string',
        'language'            => 'string',
        'price'               => 'float',
        'quantity'            => 'int',
        'revenue'             => 'float',
        'productId'           => 'string',
        'revenueType'         => 'string',
        'location_lat'        => 'float',
        'location_lng'        => 'float',
        'ip'                  => 'string',
        'idfa'                => 'string',
        'idfv'                => 'string',
        'adid'                => 'string',
        'android_id'          => 'string',
    ];

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * Event constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    /**
     * @param $name
     * @param null $value
     * @return $this
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->set($key, $val);
            }
            return $this;
        }

        if (array_key_exists($name, self::AVAILABLE_KEYS)) {
            settype($value, self::AVAILABLE_KEYS[$name]);
            $this->keys[$name] = $value;
        }
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ErrorException
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param $name
     * @return mixed
     * @throws ErrorException
     */
    public function get($name)
    {
        if (isset($this->keys[$name])) {
            return $this->keys[$name];
        }
        throw new ErrorException("Undefined property: {$name}");
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->keys;
    }
}