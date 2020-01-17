<?php
namespace Nora\Framework\Adapter\Misc\GoogleClient;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Integration\Google\GoogleClientInterface;

class GoogleClientConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind(GoogleClientInterface::class)
             ->toProvider(GoogleClientProvider::class);
        $this->bind()
             ->annotatedWith('google_application_name')
            ->toInstance(getenv('GOOGLE_APPLICATION_NAME'));
        $this->bind()
             ->annotatedWith('google_developer_key')
            ->toInstance(getenv('GOOGLE_DEVELOPER_KEY'));
    }
}
