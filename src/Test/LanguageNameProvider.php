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

/**
 * @todo Service used to showcase how to write PHPUnit tests. Delete in real project
 */
class LanguageNameProvider
{
    /**
     * @var LangRepository
     */
    private $langRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(LangRepository $langRepository, TranslatorInterface $translator)
    {
        $this->langRepository = $langRepository;
        $this->translator = $translator;
    }

    public function getLanguageNames(): string
    {
        $languages = $this->langRepository->findAll();
        $names = $this->extractLanguageNames($languages);
        $names = implode(',', $names);

        return $this->translator->trans(
            'Languages: %s',
            [
                $names,
            ],
            'Module.AcademyERPIntegration.Test'
        );
    }

    private function extractLanguageNames(array $languages): array
    {
        $names = [];
        foreach ($languages as $language) {
            $names[] = $language['name'];
        }

        return $names;
    }
}
