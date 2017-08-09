<?php namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class DropTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all data in database (e.g. to run migrations again).';

    /**
     * Create a new command instance.
     *
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

        if (env('APP_ENV') != 'testing') {
            if (!$this->confirm('CONFIRM DROP AL TABLES IN THE CURRENT DATABASE? [y|N]')) {
                exit('Drop Tables command aborted');
            }
        } else {
            $this->comment(PHP_EOL . "Dropping quietly because env is 'testing'." . PHP_EOL);

        }


        $colname = 'Tables_in_' . env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');

        $dropList = [];
        foreach ($tables as $table) {

            $dropList[] = $table->$colname;

        }
        $dropList = implode(',', $dropList);

        if (empty($dropList))
        {
            exit('No tables found.');
        }
        DB::beginTransaction();
        //turn off referential integrity
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $dropList");
        //turn referential integrity back on
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        $this->comment(PHP_EOL . "If no errors showed up, all tables were dropped" . PHP_EOL);

    }
}
