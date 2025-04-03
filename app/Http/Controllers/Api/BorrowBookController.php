<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowBookRequest;
use App\Models\BookCopy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Validation\ValidationException;

class BorrowBookController extends Controller
{
    public function __invoke(BorrowBookRequest $request): JsonResponse
    {
        $validated = $request->validated(); 
        $user = Auth::user();       

        $bookCopy = BookCopy::where('barcode', $validated['barcode'])->first();
        
        if (!$bookCopy) {
            throw ValidationException::withMessages([
                'barcode' => 'Book copy not found with this barcode.',
            ]);
        }

        if (!$bookCopy->isAvailable()) {
            throw ValidationException::withMessages([
                'barcode' => 'Book copy is not available for borrowing.',
            ]);
        }
        
        $borrow = $bookCopy->borrows()->create([
            'user_id' => $user->id,
            'borrowed_from' => now(),
            'due_date' => $validated['due_date'],
        ]);

        $bookCopy->markAsBorrowed();

        return response()->json([
            'message' => 'Book borrowed successfully',
            'data' => [
                'borrow_id' => $borrow->id,
                'due_date' => $borrow->due_date,
                'book' => $bookCopy->book->name,
                'user' => $user->name,
            ]
        ], 201);
    }
}
