<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use ProVision\Administration\Facades\AdministrationFacade as Administration;
use ProVision\Administration\Models\Administrator;

class AdministratorCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Management of admin users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * command signature fix
         */
        $this->signature = config('administration.command_prefix') . ':create';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name = $this->ask('What is your name?');
        $email = $this->ask('What is your email address?');
        $password = $this->askPassword();

        /** @var Administrator $adminUser */
        $adminUser = Administrator::where('email', $email)->first();

        if (!$adminUser) {
            /*
             * Create admin user
             */
            /** @var Administrator $adminUser */
            $adminUser = Administrator::create([
                'name' => 'Administrator',
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $adminUser->assignRole(config('administration.administrators_role_name'));

            $this->showSuccess($email);
            return;
        }


        if ($this->confirm('User already exists. Do you want to change it?')) {
            $adminUser->password = Hash::make($password);
            $adminUser->name = $name;
            $adminUser->save();

            $adminUser->assignRole(config('administration.administrators_role_name'));

            $this->showSuccess($email);
            return;
        }


        $this->error('User is not updated!');
    }

    /**
     * Ask passwords and check match
     * @return string
     */
    private function askPassword(): string
    {
        $password = $this->secret('What is your password?');
        $passwordConfirm = $this->secret('Please enter confirm password');

        if ($password !== $passwordConfirm) {
            $this->error('The passwords do not match! Please re-enter them.');
            $this->askPassword();
        }

        return $password;
    }

    /**
     * Show success output
     * @param string $email
     * @return void
     */
    private function showSuccess(string $email): void
    {
        $this->info('Administration url: ' . Administration::route('auth.login'));
        $this->info('Your email address: ' . $email);
    }
}
