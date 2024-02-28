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

use Invertus\AcademyERPIntegration\Test\LangRepository;
use Invertus\AcademyERPIntegration\Test\LanguageNameProvider;
use Invertus\AcademyERPIntegration\Test\Translator;
use Invertus\AcademyERPIntegration\Test\TranslatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * To run tests run following command in PS root directory
 * vendor/bin/phpunit --configuration modules/academyerpintegration/phpunit.xml
 */
class LanguageNameProviderTest extends TestCase
{
    /**
     * This can be used to setup things that will be used in all of tests, sort of like construct in usual classes.
     * Be warned that setUp is run before every test.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /** Unit test where tested function doesn't use any external functions */
    public function testExtractLanguageNames(): void
    {
        /** Create mocks for dependencies. This function doesn't use those dependencies so mocks are enough */
        $langRepositoryMock = $this->createMock(LangRepository::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $languageNameProvider = new LanguageNameProvider($langRepositoryMock, $translatorMock);

        /** Reflection class is needed in order to access otherwise inaccessible(private or protected) method */
        $reflectionClass = new ReflectionClass(LanguageNameProvider::class);

        $reflectionMethod = $reflectionClass->getMethod('extractLanguageNames');
        $reflectionMethod->setAccessible(true);
        $languages = [
            0 => ['name' => 'English'],
            1 => ['name' => 'Lithuanian'],
        ];
        $names = $reflectionMethod->invoke($languageNameProvider, $languages);
        static::assertEquals(
            ['English', 'Lithuanian'],
            $names
        );
    }

    /**
     * When you add a provider, test will be run again for each set of arguments
     *
     * @dataProvider languageProvider
     */
    public function testGetLanguageNames(string $lang1, string $lang2): void
    {
        $translatorMock = $this->getTranslatorMock();
        /** Here simple willReturn is good enough as we don't need it to be conditional based on arguments */
        $langRepositoryMock = $this->getMockBuilder(LangRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll'])
            ->getMock();

        $languages = [
            0 => ['name' => $lang1],
            1 => ['name' => $lang2],
        ];
        $langRepositoryMock->method('findAll')->willReturn($languages);
        $languageNameProvider = new LanguageNameProvider($langRepositoryMock, $translatorMock);

        static::assertEquals(
            sprintf(
                'Languages: %s,%s',
                $lang1,
                $lang2
            ),
            $languageNameProvider->getLanguageNames()
        );
    }

    public function languageProvider(): array
    {
        return [
            [
                'English',
                'Lithuanian',
            ],
            [
                'Elf',
                'Dwarf',
            ],
        ];
    }

    /** Retrieves translator mock */
    private function getTranslatorMock(): TranslatorInterface
    {
        $translatorMock = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['trans'])
            ->getMock();

        /**
         * Using willReturn for trans function would render test useless because, it would always return the same
         * thing regardless of what happened in the rest of the function.
         * So here we need to make sure arguments are correct.
         *
         * In array for the map you first need to define all arguments for that function(even if they are optional).
         * Final value of array is return value.
         *
         */
        $translatorMock->method('trans')->willReturnMap(
            [
                [
                    'Languages: %s',
                    [
                        'English,Lithuanian',
                    ],
                    'Module.AcademyERPIntegration.Test',
                    null,
                    'Languages: English,Lithuanian',
                ],
                [
                    'Languages: %s',
                    [
                        'Elf,Dwarf',
                    ],
                    'Module.AcademyERPIntegration.Test',
                    null,
                    'Languages: Elf,Dwarf',
                ],
            ]
        );

        return $translatorMock;
    }
}
