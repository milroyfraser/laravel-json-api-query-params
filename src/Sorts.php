<?php

declare(strict_types=1);

namespace ApiChef\RequestQueryHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Sorts
{
    /** @var Collection $fields */
    private $fields;

    public function __construct(Request $request)
    {
        $paramName = config('request-query-helper.sort');

        $params = $request->filled($paramName) ? explode(',', $request->get($paramName)) : [];

        $this->fields = collect($params)->map(function ($field) {
            $direction = SortField::DIRECTION_ASCENDING;

            if (Str::startsWith($field, '-')) {
                $direction = SortField::DIRECTION_DESCENDING;
                $field = Str::after($field, '-');
            }

            return new SortField($field, $direction);
        });
    }

    public function filled(): bool
    {
        return $this->fields->isNotEmpty();
    }

    public function each(callable $callback)
    {
        $this->fields->each($callback);
    }
}
