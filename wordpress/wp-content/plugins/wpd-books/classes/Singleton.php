<?php
namespace BookPlugin;

abstract class Singleton
{
    protected static $instance;

    // private constructor
    abstract protected function __construct();

    // prevent cloning (PHP Specific)
    private function __clone(){}

    // method that will create or return the existing instance
    public static function getInstance(){
        if(static::$instance == null){
            //self::$instance = new AuthorBlock();
            static::$instance = new static();
        }

        return static::$instance;
    }
}