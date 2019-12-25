<?php

namespace GijsG\SystemResources\Adapters;

class LinuxSystemResources implements SystemResourcesInterface
{
    /**
     * Contains all the memory stats from 'free'.
     *
     * @var array
     */
    private $ram_resources;

    /**
     * Retrieves all memory statistics from 'free' that we need.
     *
     * @return void
     */
    public function __construct()
    {
        $free = shell_exec('free');
        $free = (string) trim($free);

        $free_arr = explode("\n", $free);

        $mem = explode(' ', $free_arr[1]);
        $mem = array_filter($mem);

        $this->ram_resources = array_merge($mem);
    }

    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources()
    {
        return floatval(shell_exec('echo "`LC_ALL=C top -bn2 | grep "Cpu(s)" | tail -n1 | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk \'{print 100 - $1}\'`"'));
    }

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources()
    {
        return (int) $this->ram_resources[2];
    }

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources()
    {
        return (int) $this->ram_resources[1];
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ramUsedResourcesPercentage()
    {
        return $this->ramResources() / $this->ramTotalResources() * 100;
    }

    /**
     * Retrieve the disk usage.
     *
     * @return int
     */
    public function diskUsedResourcesPercentage()
    {
        $totalDiskSpace = disk_total_space('/');
        $freeDiskSpace = disk_free_space('/');

        $usedDiskSpace = $totalDiskSpace - $freeDiskSpace;

        return round(($usedDiskSpace / $totalDiskSpace) * 100);
    }
}
