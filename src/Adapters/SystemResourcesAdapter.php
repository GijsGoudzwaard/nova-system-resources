<?php

namespace GijsG\SystemResources\Adapters;

class SystemResourcesAdapter implements SystemResourcesInterface
{
    /**
     * Contains all the eligible drivers and their implementations.
     *
     * @var array
     */
    private $eligible_drivers = [
        'mac' => MacSystemResourcesAdapter::class,
        'linux' => LinuxSystemResourcesAdapter::class
    ];

    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources()
    {
        return $this->getDriver()->cpuResources();
    }

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources()
    {
        return $this->getDriver()->getUsage();
    }

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources()
    {
        return $this->getDriver()->getTotal();
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ramUsedResourcesPercentage()
    {
        return $this->getDriver()->ramUsedResourcesPercentage();
    }

    /**
     * Return the driver instance of the server.
     *
     * @return mixed
     */
    private function getDriver()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/Linux/', $agent))
            $driver = 'linux';

        if (preg_match('/Win/', $agent))
            $driver = 'windows';

        if (preg_match('/Mac/', $agent))
            $driver = 'mac';

        return new $this->eligible_drivers[$driver];
    }
}
