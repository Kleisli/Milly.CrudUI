<?php declare(strict_types=1);
namespace Milly\CrudUI\Domain\Model;

trait LabelledModelTrait
{
    public function getLabel(): string {
        return $this->label ?? $this->title ?? $this->name ?? 'no label';
    }

    public function __toString(): string {
        return $this->getLabel();
    }
}
