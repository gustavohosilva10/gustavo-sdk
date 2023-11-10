<?php
namespace Zoop;

abstract class Zoop
{
    public $configurations;
    private $namespace;

    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
        $this->namespace = __NAMESPACE__ . '\\';
    }

    private function getBundles()
    {
        return [
            Marketplace\Transactions::class,
            MarketPlace\Sellers::class,
            WebHook\WebHook::class,
            MarketPlace\Buyers::class,
            Payment\Ticket::class,
            Transfers\Transfers::class
        ];
    }

    public function checkBundlesRepeat()
    {
        $bundles = $this->getAllBundle();
        unset($bundles['binary']);
        return $bundles;
    }

    private function getBundle(array $bundles, $function)
    {
        unset($bundles['binary']);

        foreach ($bundles as $bundleKey => $bundleMethods) {
            if(\in_array($function, $bundleMethods)){
                return $bundleKey;
            }
        }
        return false;
    }

    private function getAllBundle()
    {
        $bundlesArray = array('binary' => array());
        $bundles = $this->getBundles();
        foreach ($bundles as $bundle) {
            if(!isset($bundlesArray[$bundle])){
               $bundlesArray[$bundle] = array();
            }
        }
        foreach ($bundlesArray as $bundleKey => $bundle) {
            $bundleMethods = \get_class_methods($bundleKey);
            if(is_array($bundleMethods) && !empty($bundleMethods)){
                foreach ($bundleMethods as $method) {
                    if($method != '__construct' 
                    && $method != '__call' 
                    && $method != 'hookBundle' 
                    && $method != 'getAllBundle'
                    && $method != 'getBundle'
                    && $method != 'getBundles'
                    && $method != 'ResponseException'
                    ){
                        $bundlesArray[$bundleKey][] = $method;
                        $bundlesArray['binary'][] = $method;
                    }
                }
            }
        }
        return $bundlesArray;
    }

    private function hookBundle($class, $method, $params)
    {
        $metodos = \get_class_methods($class);
        if(in_array($method, $metodos)){
            return call_user_func_array(array(new $class($this->configurations), $method), $params);
        }
        return false;
    }

    public function __call($name, $arguments)
    {
        $bundles = $this->getAllBundle();
        if(!in_array($name, $bundles['binary'])){
            return false;
        }
        $bundle = $this->getBundle($bundles, $name);
        if(!$bundle){
            return false;
        }
        return $this->hookBundle($bundle, $name, $arguments);
    }

    public function ResponseException(\Exception $e)
    {
        if(!in_array('getResponse', \get_class_methods($e))){
            throw new \Exception($e->getMessage(), 1);
        }
        throw new \Exception(\json_encode(\json_decode($e->getResponse()->getBody()->getContents(), true)), 1);
    }
}