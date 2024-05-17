<?php
namespace Milly\CrudUI\Eel\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Milly\CrudUI\Service\ConfigurationService;
use Milly\CrudUI\Service\ObjectService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Error\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\ObjectAccess;

class CrudUIHelper implements ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected ConfigurationService $configurationService;

    #[Flow\Inject]
    protected ObjectManagerInterface $objectManager;

    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected EntityManagerInterface $entityManager;

    #[Flow\Inject]
    protected ConfigurationManager $configurationManager;

    #[Flow\Inject]
    protected ObjectService $objectService;

    /**
     * @param object|string $model
     * @param string|null $path
     * @param string|null $view
     * @return mixed
     * @throws Exception
     * @throws \Neos\Flow\Exception
     */
    public function getConfigurationByModel(object|string $model, string $path = null, string $view = null): mixed
    {
        $configuration = $this->configurationService->getCrudUIConfiguration($model, $path, $view);
        if($path == null && $configuration == null){
            throw new Exception("No crud configuration found for class ".$model::class);
        }
        return $configuration;
    }

    /**
     * @param array $optionsConfig
     * @return array
     * @throws \Neos\Flow\Exception
     */
    public function getFieldOptions(array $optionsConfig, object $object = null): array
    {
        $options = [];
        if(isset($optionsConfig['dataSource'])){
            $dataSource = $this->objectManager->get($optionsConfig['dataSource']);
            $data = $dataSource->getData();

            foreach ($data as $value => $dataItem){
                $options[$value] = $dataItem['label'];
            }
            return $options;
        }
        if(isset($optionsConfig['repository'])){
            if(strpos($optionsConfig['repository'], '->')) {
                list($repository, $method) = explode('->', $optionsConfig['repository']);
                $repository = $this->objectManager->get($repository);
                $items = $repository->$method($object);
            }else{
                $repository = $this->objectManager->get($optionsConfig['repository']);
                $items = $repository->findAll();
            }

            foreach ($items as $item){
                $value = $this->persistenceManager->getIdentifierByObject($item);
                $label = $this->objectService->getLabel($item) ?? $value;
                $options[$value] = $label;
            }

            return $options;
        }

        return $optionsConfig;

    }

    /**
     * @param array $optionsConfig
     * @param mixed $objectValue
     * @return ?string
     * @throws \Neos\Flow\Exception
     */
    public function getFieldOptionsObjectLabel(array $optionsConfig, mixed $objectValue): ?string
    {
        if($objectValue == null){
            return null;
        }

        if(is_object($objectValue) && $this->objectService->getLabel($objectValue)){
            return $this->objectService->getLabel($objectValue);
        }

        $options = $this->getFieldOptions($optionsConfig);
        if(is_object($objectValue)){
            $objectValue = $this->persistenceManager->getIdentifierByObject($objectValue);
        }
        return $options[$objectValue] ?? null;
    }

    public function renderCssClassFromSet(string $classPath, string $theme): string
    {
        return $this->configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            'Milly.CrudUI.themes.' . $theme . '.' . $classPath);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
