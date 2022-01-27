<?php
    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/logging/Logger.php';

    class MemcachedCacher
    {
        private static $memcached = null;
        private static $connectionFailed = false;

        private static function initConnection() : void
        {
            if(is_null(MemcachedCacher::$memcached))
            {
                $logEntry = new \LogEntry();
                $logEntry->setText('Memcached Connection');

                $connectionStart = microtime(true);

                try
                {
                    MemcachedCacher::$memcached = new \Memcached();
                    MemcachedCacher::$memcached->addServer(MEMCACHED_HOST, MEMCACHED_PORT);
                }
                catch(\Exception $e)
                {
                    MemcachedCacher::$connectionFailed = true;
                }

                $connectionFinish = microtime(true);

                $connectionTime = $connectionFinish - $connectionStart;

                $logEntry->setTimeTaken($connectionTime);

                \Logger::addLog('memcached', $logEntry);

                $logEntry = new \LogEntry();
                $logEntry->setText('Memcached Connection ' . (!MemcachedCacher::$connectionFailed ? 'Successful' : 'Failed'));
                $logEntry->setTimeTaken(0);

                \Logger::addLog('memcached', $logEntry);
            }
        }

        public static function setCache(string $key, string $value, int $timeout, string $collection = '') : void
        {
            if(!MEMCACHED_ENABLED ||  MemcachedCacher::$connectionFailed)
            {
                return;
            }

            MemcachedCacher::initConnection();

            $logEntry = new \LogEntry();
            $logEntry->setText('SET - ' . $key);

            $start = microtime(true);

            MemcachedCacher::$memcached->set(md5($key), $value, $timeout);
            
            if(strlen($collection) > 0)
            {
                $currentValue = MemcachedCacher::getCache($collection);

                if(is_null($currentValue))
                {
                    $currentValue = '';
                }
                else
                {
                    $currentValue = str_replace('[' . $key . ']', '', $currentValue);
                }

                $currentValue .= '[' . $key . ']';

                MemcachedCacher::$memcached->set(md5($collection), $currentValue, strtotime('tomorrow') - time());
            }
            

            $finish = microtime(true);

            $time = $finish - $start;

            $logEntry->setTimeTaken($time);

            \Logger::addLog('memcached', $logEntry);
        }


        public static function clearCollection(string $collection) : void
        {
            $currentValue = MemcachedCacher::getCache($collection);

            if(!is_null($currentValue))
            {     
                //echo $currentValue . '<br />';
                
                $currentValue = substr($currentValue, 1);
                $currentValue = substr($currentValue, 0, -1);

                $currentValues = explode('][', $currentValue);

                for($i = 0; $i < count($currentValues); $i++)
                {
                    //echo 'Clearing ' . $currentValues[$i] . '<br />';

                    MemcachedCacher::$memcached->delete(md5($currentValues[$i]));
                }

                MemcachedCacher::$memcached->delete(md5($collection));
            }
        }



        public static function getCache(string $key) : ?string
        {
            if(!MEMCACHED_ENABLED ||  MemcachedCacher::$connectionFailed)
            {
                return null;
            }

            MemcachedCacher::initConnection();

            $logEntry = new \LogEntry();
            $logEntry->setText('GET - ' . $key);

            $start = microtime(true);

            $retrievedValue = MemcachedCacher::$memcached->get(md5($key)); 

            if($retrievedValue === false)
            {
                $returnValue = null;
            }
            else
            {
                $returnValue = (string)$retrievedValue;
            }

            $finish = microtime(true);

            $time = $finish - $start;

            $logEntry->setTimeTaken($time);

            \Logger::addLog('memcached', $logEntry);

            return $returnValue;
        }


        public static function flush() : void
        {
            \MemcachedCacher::$memcached->flush();
        }
    }