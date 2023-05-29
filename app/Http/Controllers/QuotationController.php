<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Quotation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;



class QuotationController extends Controller
{
    public function calculateQuotation(Request $request)
    {
        try {
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

        $quotation = Quotation::create([
            'total' => $total,
            'currency_id' => $request->currency_id,
        ]);

        $quotation = Quotation::create([
            'total' => $total,
            'currency_id' => $request->currency_id,
        ]);
        
        return response()->json([
            'Quotation Id' => $quotation->id,
            'total' => $quotation->total,
            'Currency Id' => $quotation->currency_id,
        ], 201);

    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
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

  

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:55',
                'email' => 'email|required|unique:users',
                'password' => 'required|confirmed'
            ]);
    
            $validatedData['password'] = bcrypt($request->password);
    
            $user = User::create($validatedData);
            
            $accessToken = JWTAuth::fromUser($user);

           
            return response()->json(['user' => $user, 'access_token' => $accessToken]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User registration failed.'], 500);
        }
    }
}
