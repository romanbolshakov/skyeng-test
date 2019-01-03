<?php

abstract class DataProvider {
    abstract protected function get($input);
}

class RemoteDataProvider extends DataProvider{
    private $host;
    private $user;
    private $password;

    function __construct($host, $user, $password){
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function get($input){
        // ... getting data from remote data source
    }
}

class CacheDataProvider extends DataProvider{
    public function get($input){
        // ... getting data from cache
    }

    public function isHitInput($input){
        // check for cached data
    }

    public function cacheData($input, $data){
        // cache data for input key (update if exists)
    }
}

interface ILogger {
    public function logException(Exception $ex);
}

class Logger implements ILogger{
    public function logException(Exception $ex){
        // log exception data
    }
}

// decorator
class DataReciever {
    private $logger;
    private $cacheDataProvider;
    private $remoteDataProvider;

    function __construct($host, $user, $password){
        $this->logger = new Logger();
        $this->cacheDataProvider = new CacheDataProvider();
        $this->remoteDataProvider = new RemoteDataProvider($host, $user, $password);
    }

    public function get($input){
        $data = [];
        try{
            if ($this->cacheDataProvider->isHitInput($input)){
                $dataProvider = $this->cacheDataProvider;
            }
            else{
                $dataProvider = $this->remoteDataProvider;
            }
            $data = $dataProvider->get($input);
            $this->cacheDataProvider->cacheData($input, $data);
            return $data;
        }
        catch(Exception $ex){
            $this->logger->logException($ex);
            // optional
            throw $ex;
        }
    }
}

// usage (optional with try...catch block)
$dataReciever = new DataReciever('https://api.example.com', 'test', 'password');
$data = $dataReciever->get($input);