<?php

namespace Milly\CrudUI\View;

use Neos\Fusion\View\FusionView;

class FallbackFusionView extends FusionView
{
    /**
     * This contains the supported options, their default values, descriptions and types.
     *
     * @var array
     */
    protected $supportedOptions = [
        'fusionPathPatterns' => [['resource://@package/Private/Views', 'resource://Milly.CrudUI/Private/Views'], 'Fusion files that will be loaded if directories are given the Root.fusion is used.', 'array'],
        'fusionPath' => [null, 'The Fusion path which should be rendered; derived from the controller and action names or set by the user.', 'string'],
        'fallbackFusionPath' => [null, 'If the option fusionPath and the derived fusion path both cant be rendered, the fallback fusion path will be rendered.', 'string'],
        'packageKey' => [null, 'The package key where the Fusion should be loaded from. If not given, is automatically derived from the current request.', 'string'],
        'debugMode' => [false, 'Flag to enable debug mode of the Fusion runtime explicitly (overriding the global setting).', 'boolean'],
        'enableContentCache' => [false, 'Flag to enable content caching inside Fusion (overriding the global setting).', 'boolean']
    ];

    /**
     * Determines the Fusion path depending on the current controller and action
     *
     * @return string
     */
    protected function getFusionPathForCurrentRequest()
    {
        $this->fusionPath = parent::getFusionPathForCurrentRequest();
        $fallbackFusionPath = $this->getOption('fallbackFusionPath');

        if($fallbackFusionPath !== null && !$this->fusionRuntime->canRender($this->fusionPath)){
            $this->fusionPath = $fallbackFusionPath;
        }

        return $this->fusionPath;
    }

    public function getControllerActionName(): string
    {
        return $this->controllerContext->getRequest()->getControllerActionName();
    }
}
