<?php

use Illuminate\Database\Seeder;

class BalanceEntry extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\BalanceEntry::class, 150)->create();
    }
}
