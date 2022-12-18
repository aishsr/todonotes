<?php declare(strict_types = 1);

namespace App\Helpers;

use Ramsey\Uuid\Uuid;
use App\Exceptions\ScrawlrException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Exception;

class TestHelper
{
    /**
     * Generate a random UUID
     *
     * @return string
     */
    public static function generateRandomUUID()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * mock index results for a model that has a factory
     *
     * @param string $forModel
     *
     * @return collection
     */
    public static function generateFactoryModelCollection(string $forModel)
    {
        // build paginated results
        $mockData = collect();
        $model = self::generateFactoryModel($forModel);

        for ($x = 0; $x < config('scrawlr/testing.mock_results_query_size'); ++$x) {
            $mockData->add($model);
        }

        return $mockData;
    }

    /**
     * mock single result for a model that has a factory
     *
     * @param string $forModel
     *
     * @return Model
     */
    public static function generateFactoryModel(string $forModel)
    {
        $factoryDefinition = $forModel::factory()->definition();

        return new $forModel($factoryDefinition);
    }

    /**
     * Create a DB mock
     *
     * @param mixed $mockData
     * @param string $shouldReceive
     * @param array[string] $withValues
     *
     * @return void
     */
    public static function createDBMock($mockData, string $shouldReceive = 'select', array $withValues = [])
    {
        $mock = DB::shouldReceive($shouldReceive)
            ->once();

        foreach ($withValues as $value) {
            $mock->with($value);
        }

        $mock->andReturn($mockData);
    }

    /**
     * Generate a request object from array
     *
     * @param array $requestValues
     *
     * @return \Illuminate\Http\Request
     */
    public static function generateRequestFromArray(array $requestValues = [])
    {
        return new \Illuminate\Http\Request($requestValues);
    }

    /**
     * Perform asserts on a Scrawlr exception against expected values.
     *
     * @param TestCase $testClass
     * @param ScrawlrException $exception
     * @param integer $httpCode
     * @param integer $errorCode
     * @param string|null $message
     *
     * @return void
     */
    public static function assertExceptionMatches(TestCase $testClass, ScrawlrException $exception, int $httpCode, int $errorCode, ?string $message = null): void
    {
        $testClass->assertNotNull($exception, 'Expecting exception but none were thrown.');

        if (null === $exception) {
            return;
        }

        $testClass->assertEquals(
            $exception->getCode(),
            $httpCode,
            'The HTTP code that was expected did not match the one that was returned.'
        );

        $testClass->assertEquals(
            $exception->getResponseStatusCode(),
            $errorCode,
            'The internal error code that was expected did not match the one that was returned.'
        );

        if ($message) {
            $testClass->assertEquals(
                $exception->getMessage(),
                $message,
                'The error message that was expected did not match the one that was returned.'
            );
        }
    }

    /**
     * Run mock that is defined by a test class.
     *
     * @param string $testClass
     * @param string $mockName
     *
     * @return void
     */
    public static function runTestClassMock(string $testClass, string $mockName)
    {
        $mocks = $testClass::defineMocks();

        if (! array_key_exists($mockName, $mocks)) {
            throw new Exception('Attempting to run a test class mock that does not exist.');
        }

        $mocks[$mockName]();
    }

    /**
     * Check for a mock definition, if it is not found throw exception.
     *
     * @param boolean $shouldEnforce
     * @param string $mockName
     * @param array $mocks
     *
     * @return void
     */
    public static function enforceMockExists(bool $shouldEnforce, string $mockName, array $mocks)
    {
        if ($shouldEnforce && ! array_key_exists($mockName, $mocks)) {
            throw new Exception("The '{$mockName}' mock is required to be implemented by this test case and was not found.");
        }
    }
}
