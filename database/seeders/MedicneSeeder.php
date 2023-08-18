<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medican;

class MedicneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        Medican::create([
            'name' => 'kapidex',
            'scientific_name' => 'dexlansopra',
            'company_name' => 'farma',
            'category' => 'digestive',
            'active_ingredient' => 'dexlansopra',
            'img' => '',
            'uses_for' => 'digestive',
            'effects' => 'nausea
            diarrhea
            bone fracture',
            'quantity' => '10',
            'expiry_date' => '2023/12/12',
            'b_price' => '1000',
            'a_price' => '1500',
        ]);
        Medican::create([
            'name' => 'ASPIRIN',
            'scientific_name' => ' acetylsalicylic acid',
            'company_name' => 'Ibn Zahr',
            'category' => 'digestive',
            'active_ingredient' => 'acetylsalicylic acid',
            'img' => '',
            'uses_for' => 'digestive',
            'effects' => 'reduce fever or inflammation
            relieve pain',
            'quantity' => '5',
            'expiry_date' => '2023/12/12',
            'b_price' => '2000',
            'a_price' => '3000',
        ]);
        Medican::create([
            'name' => 'CEPROZ',
            'scientific_name' => ' ciprofloxacin(HCI)',
            'company_name' => 'digestive',
            'category' => 'heart',
            'active_ingredient' => 'ciprofloxacin(HCI)',
            'img' => '',
            'uses_for' => 'gastrointestinal infections
            *urinary tract infections (UTIs)
            *respiratory tract infection (RTI)
            *fever
            *bacteremia',
            'effects' => 'nausea
            *vomiting
            *diarrhea
            *headache
            *dizziness
            *feeling shaky
            *drowsiness',
            'quantity' => '7',
            'expiry_date' => '2023/12/12',
            'b_price' => '3000',
            'a_price' => '3300',
        ]);
        Medican::create([
            'name' => 'AERIUS',
            'scientific_name' => 'desloratidine',
            'company_name' => 'respiratory',
            'category' => 'heart',
            'active_ingredient' => 'desloratidine',
            'img' => '',
            'uses_for' => 'heart',
            'effects' => 'nausea
            *vomiting
            *abdominal pain
            *dry mouth and throat
            *unusual tiredness and stress',
            'quantity' => '3',
            'expiry_date' => '2023/12/12',
            'b_price' => '600',
            'a_price' => '1200',
        ]);
        Medican::create([
            'name' => 'Esostom',
            'scientific_name' => 'Esostom',
            'company_name' => 'unipharma',
            'category' => 'digestive',
            'active_ingredient' => 'Esomeprazole',
            'img' => '',
            'uses_for' => 'digestive',
            'effects' => '',
            'quantity' => '4',
            'expiry_date' => '2023/12/12',
            'b_price' => '2000',
            'a_price' => '2500',
        ]);
        Medican::create([
            'name' => 'unilasix',
            'scientific_name' => 'fursimide',
            'company_name' => 'unipharma',
            'category' => 'skan',
            'active_ingredient' => 'fursimide',
            'img' => '',
            'uses_for' => 'skan',
            'effects' => 'null',
            'quantity' => '12',
            'expiry_date' => '2023/12/12',
            'b_price' => '5000',
            'a_price' => '6000',
        ]);
        Medican::create([
            'name' => 'unadol',
            'scientific_name' => 'parasetamol',
            'company_name' => 'unipharma',
            'category' => 'calming',
            'active_ingredient' => 'parasetamol',
            'img' => '',
            'uses_for' => 'parasetamol',
            'effects' => 'null',
            'quantity' => '13',
            'expiry_date' => '2023/12/12',
            'b_price' => '2000',
            'a_price' => '3000',
        ]);
    }
}
