<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User By Command Line';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        while (true) {
            $name = $this->ask('What is the your name?');
            $email = $this->ask('What is your email?');
            $password = $this->secret('What is your password?');
            $password_confirmation = $this->secret('Type your password again');
            $user = new \App\Models\User();
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->password_confirmation = $password_confirmation;
            $this->info('Validating user...');
            $user->makeVisible('password');
            $validator = Validator::make($user->toArray(), \App\Models\User::rules());
            if ($validator->fails()) {
                $this->error('Validation failed');
                $this->error($validator->errors()->first());
                continue;
            }
            break;
        }
        $this->info('Creating user...');
        User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->password),
            'role' => 1
        ]);
        $this->info('User created successfully!');
        return 0;
    }
}
