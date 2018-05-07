<?php declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Alexander Miehe <alexander.miehe@gmail.com>
 */
class NullFilterItemNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    /**
     * @var NormalizerInterface
     */
    private $decorated;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param NormalizerInterface $decorated
     */
    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @inheritdoc
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        if (!($this->decorated instanceof SerializerAwareInterface)) {
            return;
        }

        $this->decorated->setSerializer($this->serializer);
    }

    /**
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $normalizedData = $this->decorated->normalize($object, $format, $context);

        if (\is_array($normalizedData)) {
            return $this->removeNullValues($normalizedData);
        }

        return $normalizedData;
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    private function removeNullValues(array $data): array
    {
        foreach ($data as &$value) {
            if (!\is_array($value)) {
                continue;
            }

            $value = $this->removeNullValues($value);
        }

        return \array_filter($data, function ($value) {

            if (\is_array($value)) {
                return \count($value) > 0;
            }

            return $value !== null;
        });
    }
}
