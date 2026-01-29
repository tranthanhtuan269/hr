<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;


class statusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update Job status daily';

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
	    //$affected = DB::table('personnel')->update(array('status' => 0));
	}
}