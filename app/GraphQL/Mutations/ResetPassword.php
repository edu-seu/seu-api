<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class ResetPassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // Password Reset
        $user = User::where('email', $args['email'])->first();
        if ($user && Hash::check($args['token'], $user->password_reset_token)) {
            $user->password = Hash::make($args['password']);
            $user->password_reset_token = null;
            $user->save();
        }
    }
}
