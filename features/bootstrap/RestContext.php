<?php declare(strict_types = 1);

namespace App\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext as BaseContext;
use Behatch\HttpCall\Request;

/**
 * @author Alexander Miehe <alexander.miehe@tourstream.eu>
 */
class RestContext extends BaseContext
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param Request $request
     * @param string  $apiKey
     */
    public function __construct(Request $request, string $apiKey)
    {
        parent::__construct($request);
        $this->apiKey = $apiKey;
    }

    /**
     * Sends a HTTP request
     *
     * @Given I send a :method request with api key to :url
     *
     * @param string            $method
     * @param string            $url
     * @param PyStringNode|null $body
     * @param string[]          $files
     */
    public function iSendARequestWithApiKeyTo(
        string $method,
        string $url,
        ?PyStringNode $body = null,
        array $files = []
    ): void {
        $this->iSendARequestTo($method, \sprintf('%s&api_key=%s', $url, $this->apiKey), $body, $files);
    }
}
