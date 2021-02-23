<?php

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Заполнение таблицы модулей
        $moduleOne = new Module();
        $moduleOne->name = 'модуль 1';
        $moduleOne->module_id = '100001';
        $moduleOne->save();

        $moduleTwo = new Module();
        $moduleTwo->name = 'модуль 2';
        $moduleTwo->module_id = '100002';
        $moduleTwo->save();

        $moduleThree = new Module();
        $moduleThree->name = 'модуль 3';
        $moduleThree->module_id = '100003';
        $moduleThree->save();

        $moduleFour = new Module();
        $moduleFour->name = 'модуль 4';
        $moduleFour->module_id = '100004';
        $moduleFour->save();

        $moduleFive = new Module();
        $moduleFive->name = 'модуль 5';
        $moduleFive->module_id = '100005';
        $moduleFive->save();
    }
}
