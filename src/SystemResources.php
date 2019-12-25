<?php

namespace GijsG\SystemResources;

use GijsG\SystemResources\Adapters\SystemResources as Adapter;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Card;

class SystemResources extends Card
{
    /**
     * Contains the system resources.
     *
     * @var Adapter
     */
    private $adapter;

    /**
     * Will be either 'ram', 'cpu' or 'disk'.
     *
     * @var string
     */
    private $resource;

    /**
     * Contains the cached usage.
     *
     * @var string
     */
    private $usage;

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
     * @param null|string $width
     * @param null|string $component
     */
    public function __construct(?string $resource = null, string $width = '1/2', ?string $component = null)
    {
        parent::__construct($component);

        $this->resource = $resource;

        $this->width = $width;

        $this->usage = Cache::get("{$resource}_resources");

        $this->adapter = new Adapter();
    }

    /**
     * Retrieve the used resources from the total resources as a percentage.
     *
     * @return int
     */
    public function ram()
    {
        return $this->adapter->ramResources();
    }

    /**
     * Retrieve the cpu usage.
     *
     * @return int
     */
    public function cpu()
    {
        return $this->adapter->cpuResources();
    }

    /**
     * Retrieve the disk usage.
     *
     * @return int
     */
    public function disk()
    {
        return $this->adapter->diskResources();
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return $this->withMeta([
            'usage'     => $this->usage,
            'resource'  => $this->resource,
            'locale'    => app()->getLocale(),
            'component' => 'systemAnalytics',
        ]);
    }
}
