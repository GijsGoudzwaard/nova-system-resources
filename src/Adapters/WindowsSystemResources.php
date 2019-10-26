<?php

namespace GijsG\SystemResources\Adapters;

class WindowsSystemResources implements SystemResourcesInterface
{
    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources()
    {
        $cpu = shell_exec("wmic cpu get loadpercentage");

        return (int) str_replace('LoadPercentage', '', $cpu);
    }

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources()
    {
        $ram = shell_exec("wmic OS get FreePhysicalMemory /Value");
        $ram = (int) str_replace('FreePhysicalMemory=', '', $ram);

        return (int) ($ram / 1024);
    }

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources()
    {
        $ram = shell_exec("wmic OS get TotalVisibleMemorySize /Value");
        $ram = (int) str_replace('TotalVisibleMemorySize=', '', $ram);

        return (int) ($ram / 1024);
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ramUsedResourcesPercentage()
    {
        return 100 - ($this->ramResources() / $this->ramTotalResources() * 100);
    }
}
