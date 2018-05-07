<?php declare(strict_types = 1);

namespace App\Behat;

use Behat\Mink\Driver\BrowserKitDriver;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use ReflectionProperty;
use Symfony\Component\BrowserKit\Client;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends RawMinkContext
{

    use KernelDictionary;

    /**
     * Add an header element in a request
     *
     * @When I add :name server header equal to :value
     *
     * @param string $name
     * @param string $value
     */
    public function iAddServerHeaderEqualTo(string $name, string $value): void
    {
        $this->getClient()->setServerParameter($name, $value);
    }

    /**
     * @Given I want to avoid kernel shutdown
     */
    public function disableKernelShutdown(): void
    {
        //set to false to avoid kernel shutdown which resets the kernel and so the client with mock plugin
        $hasPerformedRequest = new ReflectionProperty($this->getClient(), 'hasPerformedRequest');
        $hasPerformedRequest->setAccessible(true);
        $hasPerformedRequest->setValue($this->getClient(), false);
    }

    /**
     * @BeforeScenario
     */
    public function clearAppCache(): void
    {
        $this->getContainer()->get('cache.app_clearer')->clear('');
    }

    /**
     * @return Client
     *
     * @suppress PhanUndeclaredMethod
     */
    private function getClient(): Client
    {
        /** @var BrowserKitDriver $driver */
        $driver = $this->getMink()->getSession()->getDriver();

        return $driver->getClient();
    }
}
