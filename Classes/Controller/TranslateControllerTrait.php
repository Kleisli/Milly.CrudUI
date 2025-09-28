<?php
namespace Milly\CrudUI\Controller;

use Gedmo\Translatable\TranslatableListener;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Exception\NoSuchArgumentException;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;

trait TranslateControllerTrait
{

    #[Flow\InjectConfiguration(path: "defaultLocale", package: "Sandstorm.GedmoTranslatableConnector")]
    protected $defaultLocale;

    #[Flow\Inject]
    protected TranslatableListener $translatableListener;

    /**
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws Exception
     * @throws InvalidArgumentForHashGenerationException
     * @throws NoSuchArgumentException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeTranslateAction(): void
    {
        $this->registerObjectArgument();
    }

    public function translateAction(?string $locale = null): void
    {
        $object = $this->arguments['object']->getValue();
        if($locale) {
            $object->reloadInLocale($locale);
        }
        $this->view->assign('object', $object);

        $this->view->assign('locale', $locale);
    }

    /**
     * @throws InvalidValidationConfigurationException
     * @throws Exception
     * @throws InvalidValidationOptionsException
     * @throws NoSuchArgumentException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeUpdateTranslationAction(): void
    {
        $this->registerObjectArgument();
    }

    /**
     * @throws StopActionException
     * @throws Exception
     */
    public function updateTranslationAction(string $locale): void
    {
        $this->translatableListener->setTranslatableLocale($locale);

        $object = $this->arguments['object']->getValue();

        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateTranslationAction')) {
            $this->afterUpdateTranslationAction($object);
        }

        $this->redirect('translate', null, null, ['object' => $object, 'locale' => $locale]);

    }

}
