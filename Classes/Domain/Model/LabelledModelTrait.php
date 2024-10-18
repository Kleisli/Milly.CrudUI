<?php
namespace Milly\CrudUI\Domain\Model;

trait LabelledModelTrait
{
    /**
     * @return string
     */
    public function getLabel(): string {
        return $this->label ?? $this->title ?? $this->name ?? 'no label';
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return $this->getLabel();
    }
}
