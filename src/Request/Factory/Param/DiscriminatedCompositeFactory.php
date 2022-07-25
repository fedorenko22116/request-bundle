<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

class DiscriminatedCompositeFactory implements ParamAwareFactoryInterface
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
    public function supports(PropConfigurationInterface $configuration)
    {
        return $this->compositeFactory->supports($configuration);
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        $discriminator = $data->getDiscriminator();

        if ($discriminator) {
            $data->getConfiguration()->setType('array');
        }

        $result = $this->compositeFactory->create($data, $request);

        if ($discriminator) {
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

            $result = $this->compositeFactory->create($data, $request);
        }

        return $result;
    }
}
