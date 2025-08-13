<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any books.
     */
    public function viewAny(?User $user): bool
    {
        // Any authenticated user can list books
        return (bool) $user;
    }

    /**
     * Determine whether the user can view the book.
     */
    public function view(?User $user, Book $book): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can create books.
     */
    public function create(?User $user): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can update the book.
     */
    public function update(?User $user, Book $book): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can delete the book.
     */
    public function delete(?User $user, Book $book): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can restore the book.
     */
    public function restore(?User $user, Book $book): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the book.
     */
    public function forceDelete(?User $user, Book $book): bool
    {
        return false;
    }
}
