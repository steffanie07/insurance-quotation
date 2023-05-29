<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function calculateQuotation(Request $request)
    {
    
        // Validate the request data
        $validatedData = $request->validate([
            'age' => 'required',
            'currency_id' => 'required|in:EUR,GBP,USD',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        
        $ages = explode(',', $validatedData['age']);
        $currencyId = $validatedData['currency_id'];
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];

      
        $total = 0;
        $fixedRate = 3;

        foreach ($ages as $age) {
            $ageLoad = $this->getAgeLoad($age);
            $tripLength = $this->calculateTripLength($startDate, $endDate);
            $total += $fixedRate * $ageLoad * $tripLength;
        }

        $response = [
            'total' => number_format($total, 2),
            'currency_id' => $currencyId,
            'quotation_id' => $quotationId = time() . mt_rand(100, 999),
            
        ];

       return response()->json($response);
    }

    private function getAgeLoad($age)
    {
        $ageLoadTable = [
            '18-30' => 0.6,
            '31-40' => 0.7,
            '41-50' => 0.8,
            '51-60' => 0.9,
            '61-70' => 1,
        ];

        foreach ($ageLoadTable as $ageRange => $ageLoad) {
            [$min, $max] = explode('-', $ageRange);
            if ($age >= $min && $age <= $max) {
                return $ageLoad;
            }
        }

        return 1; 
    }

    private function calculateTripLength($startDate, $endDate)
    {
        $start = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);
        return $start->diffInDays($end) + 1; // Add 1 to include both start and end dates
    }
}
