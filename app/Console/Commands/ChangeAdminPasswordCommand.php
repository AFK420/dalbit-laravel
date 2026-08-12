<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangeAdminPasswordCommand extends Command
{
    protected $signature = 'admin:password';

    protected $description = 'Change an administrator password';

    public function handle(): int
    {
        $email = $this->ask('Admin email');

        $admin = Admin::where('email', $email)->first();

        if (! $admin) {
            $this->error('Administrator account not found.');
            return self::FAILURE;
        }

        $password = $this->secret('New password');
        $confirmation = $this->secret('Confirm password');

        $validator = Validator::make(
            [
                'password' => $password,
                'confirmation' => $confirmation,
            ],
            [
                'password' => ['required', 'min:12', 'same:confirmation'],
                'confirmation' => ['required'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $admin->password = Hash::make($password);
        $admin->save();

        $this->info('Administrator password updated successfully.');

        return self::SUCCESS;
    }
}
