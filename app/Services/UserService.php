<?php

namespace App\Services;

use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Str;
use App\Enums\Scope;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function create(array $fields): User
    {
        DB::beginTransaction();
        
        try {
            $user = User::create(
                [
                    'name'     => $fields['name'],
                    'email'    => $fields['email'],
                    'password' => $fields['password'],
                    'scopes'   => $fields['scopes'] ?? [Scope::USER]
                ]
            );
            
            $user->clients()->create(
                [
                    'name'                   => $fields['name'],
                    'secret'                 => Str::random(40),
                    'redirect'               => env('APP_URL'),
                    'personal_access_client' => false,
                    'password_client'        => true,
                    'revoked'                => false,
                ]
            );
            
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        
        DB::commit();
        
        return $user;
    }
    
    /**
     * @return User
     */
    public function getCurrentUser(): Authenticatable
    {
        return Auth::user();
    }
    
    public function getById(int $id): User
    {
        return User::findOrFail($id);
    }
    
    /**
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return User::paginate($perPage, '*', 'page', $page);
    }
}
