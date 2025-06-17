<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\LoanBookRequest;
use App\Http\Requests\Book\TakeBookBackRequest;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService)
    {
    }

    public function index()
    {
        $loans = Loan::with(['book', 'user'])->get();

        return LoanResource::collection($loans);
    }

    public function store(LoanBookRequest $request)
    {
        $result = $this->loanService->loanBook($request->validated());
        return response()->json(['message' => $result['message']], $result['code']);
    }

    public function destroy(string $id)
    {
        $this->loanService->returnBook($id);
        return response()->noContent(204);
    }
}
