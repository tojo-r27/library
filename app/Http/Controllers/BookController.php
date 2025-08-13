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
        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $perPage = min(max($perPage, 1), 20);

        $query = Book::query();

        // Search across title, author & summary (space-separated terms)
        if ($search = $request->get('q')) {
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

        // Availability filter (?available=true|false)
        if ($request->has('available')) {
            $available = filter_var($request->get('available'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($available !== null) {
                $query->where('available', $available);
            }
        }

        // Order by date (created_at or published_year) (?order=asc|desc&order_field=published_year)
        $orderDir = strtolower($request->get('order', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderField = in_array($request->get('order_field'), ['created_at', 'published_year'])
            ? $request->get('order_field')
            : 'created_at';
        $query->orderBy($orderField, $orderDir);

        $books = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'status' => 'success',
            'pagination' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'last_page' => $books->lastPage(),
            ],
            'data' => $books->getCollection()->map(fn($b) => (new BookResource($b))->toArray($request))
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
