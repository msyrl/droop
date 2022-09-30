<?php

namespace App\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class PaginatorBuilder
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var QueryBuilder|EloquentBuilder $query
     */
    private $query;

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param QueryBuilder|EloquentBuilder $query
     */
    public function __construct(Request $request, $query)
    {
        $this->request = $request;
        $this->query = $query;
    }

    public static function make(Request $request, $query): self
    {
        return new self($request, $query);
    }

    public function enableSearchable(array $searchables): self
    {
        if ($this->request->filled('search')) {
            $this->query->where(function ($query) use ($searchables) {
                foreach ($searchables as $searchable) {
                    $query->orWhere($searchable, 'LIKE', "%{$this->request->get('search')}%");
                }
            });
        }

        return $this;
    }

    /**
     * @param array $sortables
     * @param string $sort default: created_at
     * @param string $direction default: desc, options: asc|desc
     * @return self
     */
    public function enableSortable(
        array $sortables,
        string $sort = 'created_at',
        string $direction = 'desc'
    ): self {
        if ($this->request->filled('sort') && in_array($this->request->get('sort'), $sortables)) {
            $sort = $this->request->get('sort');
        }

        if ($this->request->filled('direction') && in_array($this->request->get('direction'), ['asc', 'desc'])) {
            $direction = $this->request->get('direction');
        }

        $this->query->orderBy($sort, $direction);

        return $this;
    }

    public function build(): LengthAwarePaginator
    {
        return $this->query->paginate();
    }
}
