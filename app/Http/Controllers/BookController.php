<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Filters\BookFilter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class BookController extends Controller
{

    public function __construct(private BookService $books)
    {
        // Apply BookPolicy to all resource routes automatically
        $this->authorizeResource(Book::class, 'book');
    }

    /**
     * Display a listing of the books.
     */
    public function index(Request $request): JsonResponse
    {
        // Clamp perPage between 1 and 20
        $perPage = (int) $request->get('per_page', 10);
        $perPage = min(max($perPage, 1), 20);

        // Build filter from request
        $filter = new BookFilter($request);
        $books = $this->books->paginate($perPage, $filter);

        return response()->json([
            'status' => 'success',
            'data' => $books->getCollection()->map(fn($b) => (new BookResource($b))->toArray($request)),
            'pagination' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(BookStoreRequest $request): JsonResponse
    {
        $book = $this->books->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Book created successfully',
            'data' => (new BookResource($book))->toArray($request),
        ], 201);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => (new BookResource($book))->toArray(request())
        ]);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(BookUpdateRequest $request, Book $book): JsonResponse
    {
        $updated = $this->books->update($book->id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'data' => (new BookResource($updated))->toArray($request),
        ]);
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
                $deleted = $this->books->delete($book->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ]);
    }
}
