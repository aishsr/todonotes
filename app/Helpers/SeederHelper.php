<?php declare(strict_types = 1);

namespace App\Helpers;

class SeederHelper
{
    /**
     * Default dataset size for factory based inserts
     */
    public const DEFAULT_DATASET_SIZE_FACTORY = 50;

    /**
     * Default dataset size for sql based inserts
     */
    public const DEFAULT_DATASET_SIZE_SQL = 500;

    /**
     * Default bulk size for sql based inserts
     */
    public const DEFAULT_BULK_SET_SIZE_SQL = 50000;

    public const DATASET_SIZE_RAW = 'SEEDER_DATASET_SIZE_RAW';

    /**
     * Environment variable name that stores the dataset size for
     */
    public const DATASET_SIZE = 'SEEDER_DATASET_SIZE';

    /**
     * Returns the following in this order: Raw size (.env), Normal dataset size (.env), default value (provided).
     *
     * @param int $defaultValue The value to return if no environment varaibles are set.
     * @param string $overrideEnvVar A specific ENV varaible that supercedes all other options.
     *
     * @return int The first value that is set from: Raw, Normal, Default
     */
    public static function getEnvDatasetSize(int $defaultValue, string $overrideEnvVar = ''): int
    {
        $val = \env(SeederHelper::DATASET_SIZE_RAW, \env(SeederHelper::DATASET_SIZE, $defaultValue));

        // Override the value with soimething specific
        if ('' !== $overrideEnvVar) {
            $val = \env($overrideEnvVar, $val);
        }

        return \intVal($val);
    }

    /**
     * Calculates the num
     *
     * @param int $numEnteries Number of entries to create
     * @param int $cycleSize Maximum number to create per cycle
     *
     * @return array<mixed> Returns the structure: [
     *                      'numEntries' => $numEnteries,
     *                      'cycleCount' => \count($cycles),
     *                      'cycleMax' => $cycleSize,
     *                      'cycles' => $cycles,
     *                      ]
     */
    public static function calcNumberCycles(int $numEnteries, int $cycleSize = 100): array
    {
        // Filter any negative $numEnteries parameter values.
        $numEnteries = \abs($numEnteries);

        // Calculate full cycle count.
        $cycleCountFull = \intVal(\floor($numEnteries / $cycleSize));

        // Fill cycle data for full cycles.
        $cycles = \array_fill(0, $cycleCountFull, $cycleSize);

        // Calculate remainder.
        $cycleCountRemainder = ($numEnteries % $cycleSize);

        // Handle remainder values.
        if ($cycleCountRemainder > 0) {
            $cycles[] = $cycleCountRemainder;
        }

        // Response data.
        return [
            'numEntries' => $numEnteries,
            'cycleCount' => \count($cycles),
            'cycleMax' => $cycleSize,
            'cycles' => $cycles,
        ];
    }
}
