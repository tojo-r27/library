<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Authenticate a test user for all book routes
        Sanctum::actingAs(User::factory()->create());
    }

    /**
     * Test getting all books with pagination.
     */
    public function test_can_get_all_books(): void
    {
        // Create test books
        Book::factory()->count(5)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         '*' => ['id', 'title', 'author', 'published_year', 'summary', 'available', 'created_at', 'updated_at']
                     ],
                     'pagination' => ['current_page', 'per_page', 'total', 'last_page']
                 ]);
    }

    /**
     * Test creating a new book.
     */
    public function test_can_create_book(): void
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'published_year' => 2023,
            'summary' => 'This is a test book summary.',
            'available' => true
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Book created successfully'
                 ]);

        $this->assertDatabaseHas('books', $bookData);
    }

    /**
     * Test validation when creating a book.
     */
    public function test_book_creation_validation(): void
    {
        $response = $this->postJson('/api/books', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'author', 'published_year']);
    }

    /**
     * Test getting a specific book.
     */
    public function test_can_get_specific_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => $book->id,
                         'title' => $book->title,
                         'author' => $book->author
                     ]
                 ]);
    }

    /**
     * Test getting a non-existent book.
     */
    public function test_get_nonexistent_book_returns_404(): void
    {
        $response = $this->getJson('/api/books/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Book not found'
                 ]);
    }

    /**
     * Test updating a book.
     */
    public function test_can_update_book(): void
    {
        $book = Book::factory()->create();
        $updateData = [
            'title' => 'Updated Title',
            'author' => 'Updated Author'
        ];

        $response = $this->putJson("/api/books/{$book->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Book updated successfully'
                 ]);

        $this->assertDatabaseHas('books', array_merge(['id' => $book->id], $updateData));
    }

    /**
     * Test deleting a book.
     */
    public function test_can_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Book deleted successfully'
                 ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * Test deleting a non-existent book.
     */
    public function test_delete_nonexistent_book_returns_404(): void
    {
        $response = $this->deleteJson('/api/books/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Book not found'
                 ]);
    }
}
