<?php

namespace GijsG\SystemResources\Adapters;

class LinuxSystemResourcesAdapter implements SystemResourcesInterface
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
        $free = (string)trim($free);

        $free_arr = explode("\n", $free);

        $mem = explode(" ", $free_arr[1]);
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
        return shell_exec("grep 'cpu ' /proc/stat | awk '{print ($2+$4)*100/($2+$4+$5)}'");
    }

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources()
    {
        return (int)$this->ram_resources[2];
    }

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources()
    {
        return (int)$this->ram_resources[1];
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
