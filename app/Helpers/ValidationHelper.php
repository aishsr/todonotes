<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Rules\SortRule;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ValidationHelper
{
    /**
     * The regex validation rule for public Identification strings (ids)
     *
     * @var string
     */
    public static $publicIdentifierRuleV1 = '[a-zA-Z0-9\_]{25}';

    public static $paginationRequestRules = [
        'page' => 'sometimes|required|integer|min:1',
        'perPage' => 'sometimes|required|integer|min:1|max:50',
    ];

    public static $filterRequestRules = [
        'stringFilter' => '((!)?(equals|startswith|endswith|contains)\|)?[a-zA-Z0-9\_-]+',
        'booleanFilter' => '(true|false)?',
        'integerFilter' => '((eq|neq|gt|gte|lt|lte)\|)?[0-9]+',
    ];

    /**
     * Mapping between `append` => Validation rules for response
     *
     * @var array
     */
    public static $rules = [
        'author' => [
            'relationships.author' => 'sometimes|required|array',
            'relationships.author.id' => 'required_with:relationships.author|scrawlr_id',
            'relationships.author.display_name' => 'required_with:relationships.author|string',
            'relationships.author.handle' => 'required_with:relationships.author|string',
        ],
        'dates' => [
            'dates' => 'sometimes|required|array',
            'dates.type' => 'required_with:dates|in:date_time,time_stamp',
            'dates.created_at' => 'required_with:dates|date',
            'dates.updated_at' => 'required_with:dates|date',
        ],
        'links' => [
            'links' => 'sometimes|required|array',
            'links.url' => 'required_with:links|url',
            'links.path' => 'required_with:links|string',
        ],
        'votes' => [
            'votes' => 'sometimes|required|array',
            'votes.boolean' => 'required_with:votes|array:total,positive,negative,computed',
            'votes.boolean.*' => 'required_with:votes|integer|min:0',
            'votes.numeric' => 'required_with:votes|array:average,median,min,max,external,overall',
            'votes.numeric.*' => 'required_with:votes|integer|min:0',
        ],
    ];

    /**
     * Uses the appends section from $validatedResults to retrive the correct validation rules
     *
     * @param array $ruleKeys
     * @param array $validatedRequest
     *
     * @return array
     */
    public static function getValidationRules($ruleKeys, $validatedRequest)
    {
        $rules = [];
        $appends = array_key_exists('append', $validatedRequest) ? $validatedRequest['append'] : [];

        // Quit early if no appends
        if (0 == count($appends)) {
            return $rules;
        }

        foreach ($ruleKeys as $key) {
            if (! array_key_exists($key, static::$rules)) {
                continue;
            }

            if (! in_array($key, $appends)) {
                continue;
            }
            $rules += static::$rules[$key];
        }

        return $rules;
    }

    /**
     * Creates the Validation rule for a comma separated list of appends
     *
     * @param string $appends comma separated (no spaces required)
     *
     * @return array
     */
    public static function getAppendValidationRule($appends)
    {
        return [
            'append' => 'sometimes|required|array|in:' . $appends,
        ];
    }

    /**
     * Creates the Validation rule for a list of appends
     *
     * @param array<string> $appends allowable sorts
     * @param mixed $sorts
     *
     * @return array
     */
    public static function getSortValidationRule($sorts)
    {
        $sortRegex = '/((asc|desc)\\|)?(';

        foreach ($sorts as $idx => $sort) {
            if (0 != $idx) {
                $sortRegex .= '|';
            }
            $sortRegex .= preg_quote($sort, '/');
        }
        $sortRegex .= ')/';

        return [
            'sort' => [
                'sometimes',
                'required',
                'array',
                ['regex_array', $sortRegex],
            ],
        ];
    }

    /**
     * Creates the Validation rule for a list of appends
     *
     * @param array<string> $appends allowable sorts
     * @param mixed $filters
     *
     * @return array
     */
    public static function getFilterValidationRules($filters)
    {
        $rules = [
            'filter' => 'sometimes|required|array',
        ];

        foreach ($filters as $filter => $validation) {
            $rules['filter.' . $filter] = [
                'sometimes',
                'required',
                'regex:/' . (('idFilter' == $validation) ? static::$publicIdentifierRuleV1 : static::$filterRequestRules[$validation]) . '/',
            ];
        }

        return $rules;
    }

    /**
     * Validate the response spec, filter out anything not in it
     *
     * @param array $data
     * @param array $responseSpec
     * @param mixed $formattedResponse
     *
     * @return array
     */
    public static function filterValidateData($formattedResponse, $responseSpec)
    {
        $validator = Validator::make($formattedResponse, $responseSpec);

        if ($validator->fails()) {
            $errors = $validator->errors();
            dd($errors);
            $message = 'The response spec failed to validate.';

            throw new Exception($message);
        }

        return $validator->validated();
    }
}
