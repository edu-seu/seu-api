<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthMutations
{
    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                // 'type' => Type::nonNull(Type::string()),
            ],
            'password' => [
                'name' => 'password',
                // 'type' => Type::nonNull(Type::string()),
            ],
            'name' => [
                'name' => 'name',
                // 'type' => Type::string(),
            ],
            'token' => [
                'name' => 'token',
                // 'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['name'])) {
            // Registration
            $user = User::create([
                'name' => $args['name'],
                'email' => $args['email'],
                'password' => Hash::make($args['password']),
            ]);
        } elseif (isset($args['token'])) {
            // Password Reset
            $user = User::where('email', $args['email'])->first();
            if ($user && Hash::check($args['token'], $user->password_reset_token)) {
                $user->password = Hash::make($args['password']);
                $user->password_reset_token = null;
                $user->save();
            }
        } else {
            // Login
            $user = User::where('email', $args['email'])->first();
            if (!$user || !Hash::check($args['password'], $user->password)) {
                throw new \Exception(_('auth.failed'));
            }
        }
        $token = $user->createToken('authToken')->plainTextToken;
        return ['token' => $token, 'user' => $user];
    }
}
