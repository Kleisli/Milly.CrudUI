<?php
namespace Milly\CrudForms\Domain\Model;

trait LabelledModelTrait
{
    /**
     * @return string
     */
    public function getLabel(){
        return $this->label ?? $this->title ?? $this->name ?? 'no label';
    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->getLabel();
    }
}
