<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Error;
use Illuminate\Support\Facades\Hash;

final readonly class Signin
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // SignIn
        $user = User::where('email', $args['email'])->first();
        if (!$user || !Hash::check($args['password'], $user->password)) {
            throw new Error('Invalid credentials.');
        }
        assert($user instanceof User, 'Since we successfully logged in, this can no longer be `null`.');

        $token = $user->createToken('authToken')->plainTextToken;
        return ['token' => $token, 'user' => $user];
    }
}
