<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeUserToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update the token of every user after 3 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('users')->get();
        foreach($users as $user)
        {
            $token = Str::random(16);
            DB::update('update users set token = ? where id = ?', [$token, $user->id]);
        }
    }
}
