<?php declare(strict_types = 1);

namespace App\Tests\Serializer\Normalizer;

use App\Serializer\Normalizer\NullFilterItemNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Alexander Miehe <alexander.miehe@gmail.com>
 *
 * @covers \App\Serializer\Normalizer\NullFilterItemNormalizer
 */
class NullFilterItemNormalizerTest extends TestCase
{

    private $decorated;

    /**
     * @var NullFilterItemNormalizer
     */
    private $normalizer;

    /**
     * @test
     */
    public function setSerializer(): void
    {
        $serializer = $this->prophesize(SerializerInterface::class);

        $decorated = $this->prophesize(NormalizerInterface::class);

        (new NullFilterItemNormalizer($decorated->reveal()))->setSerializer($serializer->reveal());

        $this->decorated->setSerializer($serializer->reveal())->shouldBeCalled();

        $this->normalizer->setSerializer($serializer->reveal());
    }

    /**
     * @test
     *
     * @dataProvider getNormalizeValues
     *
     * @param mixed $returnValue
     * @param mixed $expectedValue
     */
    public function normalize($returnValue, $expectedValue): void
    {
        $object = new \stdClass();

        $this->decorated->normalize($object, null, [])->willReturn($returnValue);

        self::assertEquals($expectedValue, $this->normalizer->normalize($object));
    }

    /**
     * @return mixed[]
     */
    public function getNormalizeValues(): array
    {
        return [
            [[], []],
            ['test', 'test'],
            [['test' => 'foo', 'bar' => null], ['test' => 'foo']],
            [
                ['test' => 'foo', 'bar' => ['test' => 'foo', 'bar' => null]],
                ['test' => 'foo', 'bar' => ['test' => 'foo']],
            ],
        ];
    }

    /**
     * @test
     */
    public function supportsNormalization(): void
    {
        $this->decorated->supportsNormalization([], null)->willReturn(true);

        self::assertTrue($this->normalizer->supportsNormalization([]));
    }

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(FixtureNormalizer::class);

        $this->normalizer = new NullFilterItemNormalizer($this->decorated->reveal());
    }
}
