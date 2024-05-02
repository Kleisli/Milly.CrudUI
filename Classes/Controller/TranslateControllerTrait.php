<?php
namespace Milly\CrudUI\Controller;

use Gedmo\Translatable\TranslatableListener;
use Neos\Flow\Annotations as Flow;

trait TranslateControllerTrait
{

    #[Flow\InjectConfiguration(path: "defaultLocale", package: "Sandstorm.GedmoTranslatableConnector")]
    protected $defaultLocale;

    #[Flow\Inject]
    protected TranslatableListener $translatableListener;

    protected function initializeTranslateAction()
    {
        $this->registerObjectArgument();
    }

    public function translateAction(?string $locale = null)
    {
        $object = $this->arguments['object']->getValue();
        if($locale) {
            $object->reloadInLocale($locale);
        }
        $this->view->assign('object', $object);

        $this->view->assign('locale', $locale);
    }

    protected function initializeUpdateTranslationAction()
    {
        $this->registerObjectArgument();
    }

    public function updateTranslationAction(string $locale)
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
