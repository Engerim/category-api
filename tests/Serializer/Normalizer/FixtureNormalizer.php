<?php declare(strict_types = 1);

namespace App\Tests\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

/**
 * @author Alexander Miehe <alexander.miehe@gmail.com>
 */
interface FixtureNormalizer extends NormalizerInterface, SerializerAwareInterface
{

}
