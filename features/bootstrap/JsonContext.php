<?php declare(strict_types = 1);

namespace App\Behat;

use Behat\Mink\Exception\ExpectationException;
use Behatch\Context\JsonContext as BaseJsonContext;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Alexander Miehe <alexander.miehe@tourstream.eu>
 */
class JsonContext extends BaseJsonContext
{

    /**
     * @Then should have a violation for :path with :message
     *
     * @param string $path
     * @param string $message
     *
     * @throws ExpectationException
     */
    public function shouldHaveAViolationForWith(string $path, string $message): void
    {
        $json = $this->getJson();

        $propertyPathNotFound = false;

        foreach ($json->read('violations', PropertyAccess::createPropertyAccessor()) as $violation) {
            if ($violation->propertyPath === $path) {
                $propertyPathNotFound = true;
                $this->assertEquals($message, $violation->message);
                break;
            }
        }

        if (!$propertyPathNotFound) {
            throw new ExpectationException(
                \sprintf('Violation with path "%s" was not found.', $path),
                $this->getSession()
            );
        }
    }
}
