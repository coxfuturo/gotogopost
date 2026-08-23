<?php

namespace App\ListBuilders;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Str;
use Yajra\DataTables\EloquentDataTable;

class ListBuilderColumn
{
    const TYPE_TEXT = 1;
    const TYPE_DATE_RANGE = 2;
    const TYPE_SELECT = 3;
    const TYPE_NUMBER_RANGE = 4;

    const PERMISSION_VIEW = 1;
    const PERMISSION_CREATE = 2;
    const PERMISSION_EDIT = 3;
    const PERMISSION_EXPORT = 4;
    const PERMISSION_TYPES = [
        self::PERMISSION_VIEW => 'view',
        self::PERMISSION_CREATE => 'create',
        self::PERMISSION_EDIT => 'edit',
        self::PERMISSION_EXPORT => 'export'
    ];

    public string $name;

    public string $property;

    public string|null $dbColumn;

    public int|null $filterType;

    public bool $canCopy;

    public bool $isCurrency;

    public string|null $view;

    public array|null $options;

    public bool $shouldExport;

    public Closure|null $exportCallback;

    public Closure|null $viewCallback;

    public int|null $permissionType;

    public function __construct(
        string   $name, string $property, ?string $dbColumn = null, ?int $filterType = null, bool $canCopy = false,
        bool     $isCurrency = false, ?string $view = null, ?array $options = null, bool $shouldExport = true,
        ?Closure $exportCallback = null, ?Closure $viewCallback = null,?int $permissionType = null,
    )
    {

        $this->name = $name;
        $this->property = $property;
        $this->dbColumn = $dbColumn;
        $this->filterType = $filterType;
        $this->canCopy = $canCopy;
        $this->isCurrency = $isCurrency;
        $this->view = $view;
        $this->options = $options;
        $this->shouldExport = $shouldExport;
        $this->exportCallback = $exportCallback;
        $this->viewCallback = $viewCallback;
        $this->permissionType = $permissionType;
    }

    public function processDataTableColumn(EloquentDataTable &$dataTable, array &$rawColumns)
    {

        if ($this->view) {
            $dataTable->addColumn($this->property, $this->view);

            $rawColumns[] = $this->property;
        } else {
            $dataTable->editColumn($this->property, function ($model) {
                return $this->getValue($model);
            });
        }
    }

    public function processQueryBuilderColumn(&$eagerLoads, &$allowedFilters)
    {
        if ($this->filterType == ListBuilderColumn::TYPE_DATE_RANGE) {
            $allowedFilters[] = AllowedFilter::callback("from_$this->property", function (Builder $query, $value) {
                return $query->where($this->property, '>=', Carbon::parse($value)->startOfDay());
            });

            $allowedFilters[] = AllowedFilter::callback("to_$this->property", function (Builder $query, $value) {
                return $query->where($this->property, '<=', Carbon::parse($value)->endOfDay());
            });
        }

        if ($this->filterType == ListBuilderColumn::TYPE_NUMBER_RANGE) {
            $allowedFilters[] = AllowedFilter::callback("min_$this->property", function (Builder $query, $value) {
                if (Str::contains($this->property, '.')) {
                    $relation = Str::beforeLast($this->property, '.');
                    $column = Str::afterLast($this->property, '.');

                    return $query->whereHas($relation, function (Builder $query) use ($value, $column) {
                        return $query->where($column, '>=', $value);
                    });
                }

                return $query->where($this->property, '>=', $value);
            });

            $allowedFilters[] = AllowedFilter::callback("max_$this->property", function (Builder $query, $value) {
                if (Str::contains($this->property, '.')) {
                    $relation = Str::beforeLast($this->property, '.');
                    $column = Str::afterLast($this->property, '.');

                    return $query->whereHas($relation, function (Builder $query) use ($value, $column) {
                        return $query->where($column, '<=', $value);
                    });
                }

                return $query->where($this->property, '<=', $value);
            });
        }

        if ($this->filterType == ListBuilderColumn::TYPE_TEXT) {
            $allowedFilters[] = $this->property;
        }

        if ($this->filterType == ListBuilderColumn::TYPE_SELECT) {
            $allowedFilters[] = AllowedFilter::exact($this->dbColumn ?: $this->property);
        }

        $relations = explode('.', $this->property);

        if (count($relations) >= 2) {
            array_pop($relations);

            $eagerLoads[] = implode('.', $relations);
        }
    }

    public function title(): string
    {
        // if ($this->isCurrency) {
        //     return sprintf(
        //         "%s (%s)",
        //         $this->name,
        //         env('APP_CURRENCY')
        //     );
        // }

        return $this->name;
    }

    // public function hasPermission(string $permissionPrefix): bool
    // {
    //     return !$this->permissionType || (Auth::guard('admin')->user()->can($permissionPrefix . '-' . self::PERMISSION_TYPES[$this->permissionType]));
    // }

    public function render(): Renderable
    {
        $view = match ($this->filterType) {
            self::TYPE_DATE_RANGE => view(ListBuilder::$viewPrefix . '.list-builder.date-range'),
            self::TYPE_SELECT => view(ListBuilder::$viewPrefix . '.list-builder.select'),
            self::TYPE_NUMBER_RANGE => view(ListBuilder::$viewPrefix . '.list-builder.number-range'),
            default => view(ListBuilder::$viewPrefix . '.list-builder.text'),
        };

        return $view->with([
            'column' => $this
        ]);
    }

    public function getValue($model, bool $forExport = false)
    {
        if ($forExport && $this->exportCallback) {
            $value = ($this->exportCallback)($model);
        } elseif (!$forExport && $this->viewCallback) {
            $value = ($this->viewCallback)($model);
        } else {
            $value = deepAccess($model, $this->property);
        }

        if ($this->filterType == self::TYPE_DATE_RANGE) {
            $value = $value?->dateTimeFormat();
        }

        // if ($value && $this->filterType == self::TYPE_TEXT) {
        //     if ($this->canCopy && !$forExport) {
        //         return view('copy-text', [
        //             'text' => $value
        //         ]);
        //     }
        // }

        // if ($value && $this->filterType == self::TYPE_NUMBER_RANGE) {
        //     if ($this->isCurrency && !$forExport) {
        //         return env('APP_CURRENCY') . $value;
        //     }
        // }

        if ($value && $this->filterType == self::TYPE_SELECT) {
            if (array_key_exists($value, $this->options)) {
                return $this->options[$value];
            }
        }

        return $value ?: 'N/A';
    }
}
