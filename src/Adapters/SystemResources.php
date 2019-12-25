<?php

namespace GijsG\SystemResources\Adapters;

use Illuminate\Support\Facades\Cache;

class SystemResources
{
    /**
     * Contains all the eligible drivers and their implementations.
     *
     * @var array
     */
    private $eligible_drivers = [
        'mac'     => MacSystemResources::class,
        'linux'   => LinuxSystemResources::class,
        'windows' => WindowsSystemResources::class,
    ];

    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources()
    {
        $usage = $this->getDriver()->cpuResources();

        $this->cacheResources('cpu', $usage);

        return $usage;
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ramResources()
    {
        $usage = $this->getDriver()->ramUsedResourcesPercentage();

        $this->cacheResources('ram', $usage);

        return $usage;
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function diskResources()
    {
        $usage = $this->getDriver()->diskUsedResourcesPercentage();

        $this->cacheResources('disk', $usage);

        return $usage;
    }

    /**
     * Cache the resource results so on refresh we don't have to wait for data to come in.
     *
     * @param string $resource
     * @param int    $usage
     *
     * @return void
     */
    private function cacheResources(string $resource, int $usage)
    {
        $path = "{$resource}_resources";
        $cached_resources = Cache::get($path) ?: collect();

        if ($cached_resources->count() === 12) {
            $cached_resources->shift();
        }

        $cached_resources->push($usage);

        Cache::forever($path, $cached_resources);
    }

    /**
     * Return the driver instance of the server.
     *
     * @return mixed
     */
    private function getDriver()
    {
        $agent = PHP_OS;
        $driver = 'linux';

        if (strpos(strtolower($agent), 'win') !== false) {
            $driver = 'windows';
        }

        if ($agent === 'Darwin') {
            $driver = 'mac';
        }

        return new $this->eligible_drivers[$driver]();
    }
}
