<?php

namespace GijsG\SystemResources\Adapters;

class MacSystemResourcesAdapter implements SystemResourcesInterface
{
    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources()
    {
        $cpu = shell_exec("top -l 1 | grep 'CPU usage:'");

        return (int) str_replace('CPU usage: ', '', explode('%', $cpu)[0]);
    }

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources()
    {
        return (int) shell_exec("ps -caxm -orss= | awk '{ sum += $1 } END { print sum/1024 }'");
    }

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources()
    {
        $total_ram = shell_exec("hostinfo | grep 'Primary memory available:'");
        $total_ram = (int) str_replace(['Primary memory available: ', ' gigabytes'], '', $total_ram);

        return $total_ram * 1024;
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
}
