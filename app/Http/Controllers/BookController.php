<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function __construct(private BookService $books)
    {
    }

    /**
     * Display a listing of the books.
     */
    public function index(Request $request): JsonResponse
    {
        // Pagination support
        $perPage = (int) $request->get('per_page', 10);
        $perPage = min(max($perPage, 1), 20); // clamp between 1 and 20
        $books = $this->books->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => collect($books->items())->map(fn($b) => (new BookResource($b))->toArray($request)),
            'pagination' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'last_page' => $books->lastPage(),
            ]
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
    public function show(string $id): JsonResponse
    {
        $book = $this->books->find((int) $id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => (new BookResource($book))->toArray(request())
        ]);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(BookUpdateRequest $request, string $id): JsonResponse
    {
        $existing = $this->books->find((int) $id);

        if ($existing) {
            $updated = $this->books->update((int) $id, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Book updated successfully',
                'data' => (new BookResource($updated))->toArray($request),
            ]);
        }

        // Upsert behavior: if not found, validate as store and create
        $validator = Validator::make($request->all(), (new BookStoreRequest())->rules());
        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        [$book, $created] = $this->books->upsert((int) $id, $data);

        return response()->json([
            'status' => 'success',
            'message' => $created ? 'Book created successfully' : 'Book updated successfully',
            'data' => (new BookResource($book))->toArray($request),
        ], $created ? 201 : 200);
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->books->delete((int) $id);

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ]);
    }
}
