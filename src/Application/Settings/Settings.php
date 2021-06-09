<?php
declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    /**
     * @var array
     */
    private array $settings;

    /**
     * Settings constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = ''): mixed
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}
