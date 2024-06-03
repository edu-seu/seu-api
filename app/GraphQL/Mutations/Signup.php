<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class Signup
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
        ]);
        assert($user instanceof User, 'Since we successfully logged in, this can no longer be `null`.');

        $token = $user->createToken('authToken')->plainTextToken;
        return ['token' => $token, 'user' => $user];
    }
}
