<?php

namespace App\Http\Controllers;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;



abstract class Controller
{
    public function getUserID(Request $request)
    {
        // Get the token from the Authorization header
        $token = $request->bearerToken();

        if ($token) {
            // Find the token in the database
            $personalAccessToken = PersonalAccessToken::findToken($token);

            if ($personalAccessToken) {
                // Retrieve the user ID associated with the token
                $userId = $personalAccessToken->tokenable_id;

                return $userId;
            }
        }

        return null;
    }
}