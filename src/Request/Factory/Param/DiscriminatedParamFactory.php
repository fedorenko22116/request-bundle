<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\Discriminator;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

class DiscriminatedParamFactory implements ParamAwareFactoryInterface
{
    /**
     * @var CompositeFactory
     */
    private $compositeFactory;

    /**
     * @param CompositeFactory $compositeFactory
     */
    public function __construct(CompositeFactory $compositeFactory)
    {
        $this->compositeFactory = $compositeFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Extraction $data)
    {
        return (bool) $data->getDiscriminator();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        $data = clone $data;
        $discriminator = $data->getDiscriminator();

        if ($discriminator) {
            $data->getConfiguration()->setType('array');
            $data->setDiscriminator(null);
        }

        $result = $this->compositeFactory->create($data, $request);

        if ($discriminator && is_array($result)) {
            $result = $this->compositeFactory->create(
                $this->applyDiscriminator($data, $result, $discriminator),
                $request
            );
        }

        return $result;
    }

    /**
     * @param Extraction           $data
     * @param array<string, mixed> $result
     * @param Discriminator        $discriminator
     *
     * @return Extraction
     */
    private function applyDiscriminator(Extraction $data, array $result, Discriminator $discriminator)
    {
        if (!isset($result[$discriminator->getField()])) {
            throw new ConfigurationException('Discriminator field is not found');
        }

        if (!isset($discriminator->getMapping()[$result[$discriminator->getField()]])) {
            throw new ConfigurationException(
                sprintf(
                    'Discriminator mapping not found. Expect one of %s, "%s" given',
                    print_r(array_keys($discriminator->getMapping()), true),
                    $result[$discriminator->getField()]
                )
            );
        }

        $type = $discriminator->getMapping()[$result[$discriminator->getField()]];

        if ($type instanceof PropConverter) {
            $data->setConfiguration($type);
        } else {
            $data->getConfiguration()->setType($type);
        }

        return $data;
    }
}
