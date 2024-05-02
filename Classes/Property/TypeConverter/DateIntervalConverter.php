<?php
namespace Milly\CrudUI\Property\TypeConverter;

/*
 * This file is part of the Neos.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\Exception\InvalidPropertyMappingConfigurationException;
use Neos\Flow\Property\Exception\TypeConverterException;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;
use Neos\Flow\Validation\Error;


class DateIntervalConverter extends AbstractTypeConverter
{

    /**
     * @var array<string>
     */
    protected $sourceTypes = ['string', 'array'];

    /**
     * @var string
     */
    protected $targetType = \DateInterval::class;

    /**
     * @var integer
     */
    protected $priority = 100;

    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        if (is_string($source)) {
            return new \DateInterval($source);
        }

        if (is_array($source)) {
            $specString = 'P';
            // Adding each part to the spec-string.
            foreach ($source as $key => $value) {
                $specString .= $value . $key;
            }
            return new \DateInterval($specString);
        }

    }
}
