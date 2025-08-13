<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookFilter
{
    public function __construct(private Request $request)
    {
    }

    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Apply filters to the query.
     */
    public function apply(Builder $query): Builder
    {
        $this->search($query)
            ->available($query)
            ->order($query);

        return $query;
    }

    private function search(Builder $query): self
    {
        if ($search = $this->request->get('q')) {
            $terms = array_filter(explode(' ', $search));
            $query->where(function ($outer) use ($terms) {
                foreach ($terms as $term) {
                    $outer->where(function ($q) use ($term) {
                        $q->where('title', 'like', "%{$term}%")
                          ->orWhere('author', 'like', "%{$term}%")
                          ->orWhere('summary', 'like', "%{$term}%");
                    });
                }
            });
        }
        return $this;
    }

    private function available(Builder $query): self
    {
        if ($this->request->has('available')) {
            $available = filter_var($this->request->get('available'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($available !== null) {
                $query->where('available', $available);
            }
        }
        return $this;
    }

    private function order(Builder $query): self
    {
        $orderDir = strtolower($this->request->get('order', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderField = in_array($this->request->get('order_field'), ['created_at', 'published_year'])
            ? $this->request->get('order_field')
            : 'created_at';

        $query->orderBy($orderField, $orderDir);
        return $this;
    }
}
