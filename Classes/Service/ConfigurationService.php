<?php
namespace Milly\CrudUI\Service;

use Milly\Tools\Service\ClassMappingService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Utility\Arrays;

/**
 * @Flow\Scope("singleton")
 */
class ConfigurationService
{
    #[Flow\Inject]
    protected ConfigurationManager $configurationManager;

    #[Flow\Inject]
    protected ClassMappingService $classMappingService;


    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @param string|null $path
     * @param string|null $view
     * @return mixed
     * @throws \Neos\Flow\Exception
     */
    public function getCrudUIConfiguration(object|string $model, string $path = null, string $view = null){

        $modelClassName = is_object($model) ? $model::class : $model;
        $className = $this->classMappingService->cleanClassName($modelClassName);

        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            'Milly.CrudUI.entities' . '.' . $className
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

