<?php

namespace GijsG\SystemAnalytics\Adapters;

interface SystemResourcesInterface
{
    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpuResources();

    /**
     * Retrieve the ram usage.
     *
     * @return int
     */
    public function ramResources();

    /**
     * Retrieve the total of amount of ram available.
     *
     * @return int
     */
    public function ramTotalResources();

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ramUsedResourcesPercentage();
}
