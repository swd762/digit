<?php

use App\Models\Patient;
use App\Models\Product;
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
        $productOne->date = '20.01.2020';
        $productOne->save();

        $productTwo = new Product();
        $productTwo->name = 'изделие 2';
        $productTwo->date = '21.03.2020';
        $productTwo->save();

        $productThree = new Product();
        $productThree->name = 'изделие 3';
        $productThree->date = '23.01.2020';
        $productThree->save();

        $productFive = new Product();
        $productFive->name = 'изделие 8';
        $productFive->date = '25.01.2020';
        $productFive->save();

        $productTen = new Product();
        $productTen->name = 'изделие 10';
        $productTen->date = '30.02.2020';
        $productTen->save();


        // create Patients
        $patientFirst = new Patient();
        $patientFirst->name = 'Иванов Иван Иванович';
        $patientFirst->birth_date = '01.02.1950';
        $patientFirst->save();
        $patientFirst->products()->attach($productTwo);
        $patientFirst->products()->attach($productOne);
        $patientFirst->products()->attach($productThree);

        $patientSecond = new Patient();
        $patientSecond->name = 'Алексеев Иван Иванович';
        $patientSecond->birth_date = '01.02.1960';
        $patientSecond->save();
        $patientSecond->products()->attach($productTen);


        $patientThird = new Patient();
        $patientThird->name = 'Больнов Алексей Иванович';
        $patientThird->birth_date = '01.02.1963';
        $patientThird->save();
        $patientThird->products()->attach($productFive);



    }
}


