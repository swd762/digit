<?php

use App\Models\Diagnos;
use App\Models\Patient;
use App\Models\Products\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PatientAndProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create products for test
        $productOne = new Product();
        $productOne->name = 'изделие 1';
        $productOne->save();

        $productTwo = new Product();
        $productTwo->name = 'изделие 2';
        $productTwo->save();

        $productThree = new Product();
        $productThree->name = 'изделие 3';
        $productThree->save();

        $productFive = new Product();
        $productFive->name = 'изделие 8';
        $productFive->save();

        $productTen = new Product();
        $productTen->name = 'изделие 10';
        $productTen->save();

        $diagnos1 = Diagnos::create([
            'title' => 'Диагноз 1',
            'code' => 113205
        ]);

        Diagnos::create([
            'title' => 'Диагноз 2',
            'code' => 113206
        ]);

        Diagnos::create([
            'title' => 'Диагноз 4',
            'code' => 113208
        ]);

        Diagnos::create([
            'title' => 'Диагноз 3',
            'code' => 113207
        ]);

        // create Patients
        $patientFirst = Patient::create([
            'name' => 'Иванов Иван Иванович',
            'birth_date' => Carbon::createFromDate(1950, 02, 01)->toDateString()
        ]);

        $patientFirst->diagnoses()->attach($diagnos1, [
            'comment' => "Комментарий врача",
            'issue_date' => Carbon::now()->toDateString(),
            'product_id' => $productOne->id
        ]);

        // $patientFirst->products()->attach($productTwo);
        // $patientFirst->products()->attach($productOne);
        // $patientFirst->products()->attach($productThree);

        $patientSecond = new Patient();
        $patientSecond->name = 'Алексеев Иван Иванович';
        $patientSecond->birth_date = Carbon::createFromDate(1960, 02, 01)->toDateString();
        $patientSecond->save();


        $patientThird = new Patient();
        $patientThird->name = 'Больнов Алексей Иванович';
        $patientThird->birth_date = Carbon::createFromDate(1963, 02, 01)->toDateString();;
        $patientThird->save();

        for ($i = 0; $i < 10; $i++) {
            $newPatient = Patient::create([
                'name' => "Пациент$i",
                'birth_date' => Carbon::createFromDate(1963 + $i, 02, 01)->toDateString(),
            ]);
        }
    }
}
