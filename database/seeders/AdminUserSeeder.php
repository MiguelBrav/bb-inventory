<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = config('admin.name');
        $email = config('admin.email');
        $password = config('admin.password');

        if (
            ! is_string($name) || trim($name) === ''
            || ! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || ! is_string($password) || $password === ''
        ) {
            throw new RuntimeException(
                'Configure ADMIN_NAME, ADMIN_EMAIL and ADMIN_PASSWORD before seeding the admin user.'
            );
        }

        $user = User::withTrashed()->firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->name = $name;
            $user->password = $password;
        }

        if ($user->trashed()) {
            $user->restore();
        }

        $user->is_admin = true;
        $user->save();
    }
}
