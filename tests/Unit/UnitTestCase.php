<?php declare(strict_types = 1);

namespace Tests\Unit;

use Tests\TestCase;
use Exception;

/**
 * Base Unit Test Class.
 *
 * @internal
 *
 * @small
 */
abstract class UnitTestCase extends TestCase
{
    /**
     * store the class mocks so they can be run by the init method.
     *
     * @var array
     */
    private static array $_mocks;

    /**
     * Define Mocks can be overridden by test files, used to specify
     * any mocks required for tests belonging to a test class.
     *
     * @return array
     */
    public static function defineMocks(): array
    {
        return [];
    }

    /**
     * Setup before class is loaded.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$_mocks = static::defineMocks();
    }

    /**
     * Used to run a test mock that was defined inside the defineMocks() abstract function.
     *
     * @param string $mockName the array key that specifies what mock to run.
     *
     * @return void
     */
    public static function setupMock(string $mockName): void
    {
        if (! array_key_exists($mockName, static::$_mocks)) {
            throw new Exception('setupMock was called by a unit test, the mock specified was not defined.');
        }

        static::$_mocks[$mockName]();
    }
}
