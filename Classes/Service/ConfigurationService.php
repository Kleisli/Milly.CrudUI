<?php
namespace Milly\CrudForms\Service;

use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\ContentRepository\Domain\Factory\NodeFactory;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeTemplate;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Utility\Arrays;
use Sandstorm\CrudForms\Exception\MissingModelTypeException;

/**
 * @Flow\Scope("singleton")
 */
class ConfigurationService
{
    #[Flow\Inject]
    protected ConfigurationManager $configurationManager;


    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @param string|null $path
     * @param string|null $view
     * @return mixed
     * @throws \Neos\Flow\Exception
     */
    public function getCrudFormsConfiguration(object|string $model, string $path = null, string $view = null){

        $modelClassName = is_object($model) ? $model::class : $model;
        $className = ClassMappingService::cleanClassName($modelClassName);

        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            implode('.', ['Milly.CrudForms', str_replace("Domain.Model.", "", str_replace("\\", ".", $className))])
        );

        if(isset($configuration['views'][$view]['properties'])){
            $viewProperties = [];
            foreach ($configuration['views'][$view]['properties'] as $viewPropertyName){
                $viewProperties[$viewPropertyName] = $configuration['properties'][$viewPropertyName];
            }
            $configuration['properties'] = $viewProperties;
        }

        foreach ($configuration['properties'] as $propertyName => $propertyConfiguration){
            $propertyConfiguration['_propertyName'] = $propertyName;
            $configuration['properties'][$propertyName] = $propertyConfiguration;
        }

        return $path != null ? Arrays::getValueByPath($configuration, $path) : $configuration;
    }

}

