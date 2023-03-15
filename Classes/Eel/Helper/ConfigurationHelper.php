<?php
namespace Milly\CrudForms\Eel\Helper;

use Milly\CrudForms\Service\ClassMappingService;
use Milly\CrudForms\Service\ConfigurationService;
use Milly\Flow\Persistence\Repository;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Error\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Utility\Arrays;
use PHPUnit\Framework\Error\Notice;

class ConfigurationHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     */
    protected ConfigurationService $configurationService;

    /**
     * @Flow\Inject
     */
    protected ObjectManagerInterface $objectManager;

    /**
     * @Flow\Inject
     */
    protected PersistenceManagerInterface $persistenceManager;

    /**
     * @param object $model
     * @param string|null $path
     * @param string|null $view
     * @return mixed
     * @throws Exception
     * @throws \Neos\Flow\Exception
     */
    public function getConfigurationByModel(object $model, string $path = null, string $view = null)
    {
        $configuration = $this->configurationService->getCrudFormsConfiguration($model::class, $path, $view);
        if($path == null && $configuration == null){
            throw new Exception("No crud configuration found for class ".$model::class);
        }
        return $configuration;
    }

    /**
     * @param string $modelClass a Model class name
     * @param string|null $path
     * @param string|null $view
     * @return mixed
     * @throws Exception
     * @throws \Neos\Flow\Exception
     */
    public function getConfigurationByClass(string $modelClass, string $path = null, string $view = null)
    {
        $configuration = $this->configurationService->getCrudFormsConfiguration($modelClass, $path, $view);
        if($path == null && $configuration == null){
            throw new Exception("No crud configuration found for class ".$modelClass);
        }
        return $configuration;
    }

    /**
     * @param array $optionsConfig
     * @return array
     * @throws \Neos\Flow\Exception
     */
    public function getFieldOptions(array $optionsConfig): array
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
            $repository = $this->objectManager->get($optionsConfig['repository']);
            $items = $repository->findAll();
            foreach ($items as $item){
                $value = $this->persistenceManager->getIdentifierByObject($item);
                $label = $value;
                if(method_exists($item, 'getLabel')){
                    $label = $item->getLabel();
                }else{
                    if(method_exists($item, 'getTitle')){
                        $label = $item->getTitle();
                    }else{
                        if(method_exists($item, 'getName')){
                            $label = $item->getName();
                        }
                    }
                }
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

        if(is_object($objectValue)){
            if(method_exists($objectValue, 'getLabel')){
                return $objectValue->getLabel();
            }
        }

        $options = $this->getFieldOptions($optionsConfig);
        if(is_object($objectValue)){
            $objectValue = $this->persistenceManager->getIdentifierByObject($objectValue);
        }
        return $options[$objectValue] ?? null;
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
