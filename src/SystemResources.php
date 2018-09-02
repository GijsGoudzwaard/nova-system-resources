<?php

namespace GijsG\SystemResources;

use Laravel\Nova\Card;
use GijsG\SystemResources\Adapters\SystemResourcesAdapter;

class SystemResources extends Card
{
    /**
     * Contains the system resources.
     *
     * @var SystemResourcesAdapter
     */
    private $adapter;

    /**
     * Will be either 'ram' or 'cpu';
     *
     * @var string
     */
    private $resource;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/2';

    /**
     * Create a new instance and set the system resources.
     *
     * @param null|string $resource
     * @param null|string $component
     */
    public function __construct(?string $resource = null, ?string $component = null)
    {
        parent::__construct($component);

        $this->resource = $resource;

        $this->adapter = new SystemResourcesAdapter;
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ram()
    {
        return $this->adapter->ramUsedResourcesPercentage();
    }

    /**
     * Retrieve the cpu usage
     *
     * @return int
     */
    public function cpu()
    {
        return $this->adapter->cpuResources();
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return $this->withMeta([
            'resource' => $this->resource,
            'component' => 'systemAnalytics'
        ]);
    }
}
