<?php
namespace Upadd\Bin;

use Upadd\Swoole\Lib\Help;

abstract class Client{


    /**
     * @var null
     */
    protected $host = null;

    /**
     * @var null
     */
    protected $type = null;

    /**
     * set in port
     * @var null
     */
    protected $port = null;


    /**
     * Client constructor.
     * @param $address
     * @param null $data
     */
    public function __construct($address,$data=null)
    {
        $parse  = Help::parseAddress($address);
    }

    /**
     * @param $address
     * @param null $data
     * @return static
     */
    public static function create($address,$data=null)
    {
        return new static($address,$data);
    }


    /**
     * @return mixed
     */
   abstract public function sync();

    /**
     * @return mixed
     */
   abstract public function async();

    /**
     * @return mixed
     */
   abstract public function close();




}