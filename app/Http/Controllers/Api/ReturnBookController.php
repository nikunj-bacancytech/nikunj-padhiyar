<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnBookRequest;
use App\Models\BookCopy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReturnBookController extends Controller
{
    public function __invoke(ReturnBookRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $bookCopy = BookCopy::where('barcode', $validated['barcode'])->first();
        
        if (!$bookCopy) {
            throw ValidationException::withMessages([
                'barcode' => 'Book copy not found with this barcode.',
            ]);
        }

        $currentBorrow = $bookCopy->borrows()->whereNull('returned_at')->first();
        
        if (!$currentBorrow) {
            throw ValidationException::withMessages([
                'barcode' => 'This book is not currently borrowed or is already returned.',
            ]);
        }

        $currentBorrow->update([
            'returned_at' => now(),
        ]);

        $bookCopy->markAsAvailable();

        return response()->json([
            'message' => 'Book returned successfully',
            'data' => [
                'borrow_id' => $currentBorrow->id,
                'returned_at' => $currentBorrow->returned_at,
                'book' => $bookCopy->book->name,
                'user' => $currentBorrow->user->name,
            ]
        ]);
    }
}
