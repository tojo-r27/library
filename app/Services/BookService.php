<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Filters\BookFilter;

class BookService
{
    /**
     * Paginate books.
     */
    public function paginate(int $perPage, BookFilter $filter): LengthAwarePaginator
    {
        $query = $filter->apply(Book::query());
        return $query->paginate($perPage)->appends($filter->request()->query());
    }

    /**
     * Create a new book.
     */
    public function create(array $data): Book
    {
        return Book::create($data);
    }

    /**
     * Find a book by ID.
     */
    public function find(int $id): ?Book
    {
        return Book::find($id);
    }

    /**
     * Update a book by ID.
     */
    public function update(int $id, array $data): ?Book
    {
        $book = Book::find($id);
        if (!$book) {
            return null;
        }
        $book->update($data);
        return $book;
    }

    /**
     * Delete a book by ID.
     */
    public function delete(int $id): bool
    {
        $book = Book::find($id);
        if (!$book) {
            return false;
        }
        return (bool) $book->delete();
    }

    /**
     * Update if exists, otherwise create a new record.
     *
     * @return array{0: Book, 1: bool} [book, created]
     */
    public function upsert(int $id, array $data): array
    {
        $book = Book::find($id);
        if ($book) {
            $book->update($data);
            return [$book, false];
        }
        $created = Book::create($data);
        return [$created, true];
    }
}
