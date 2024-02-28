<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   MIT
 *
 * @see       /LICENSE
 *
 *  International Registered Trademark & Property of INVERTUS, UAB
 */

declare(strict_types=1);

namespace Invertus\AcademyERPIntegration\Test;

/** @todo Service used to showcase how to write PHPUnit tests. Delete in real project */
class Translator implements TranslatorInterface
{
    /**
     * Function which would use database to retrieve translation
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string
    {
        return 'translated string';
    }
}
