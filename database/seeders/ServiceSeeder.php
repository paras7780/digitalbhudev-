<?php
namespace Database\Seeders;
use App\Models\Service;use Illuminate\Database\Seeder;use Illuminate\Support\Str;
class ServiceSeeder extends Seeder { public function run(): void { foreach(['Satyanarayan Katha','Griha Pravesh Pooja','Vivah Sanskar','Ganesh Pooja','Rudrabhishek','Navgraha Shanti','Vastu Shanti','Muhurat Consultation','Janma Kundali','Shradh & Tarpan'] as $name) Service::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'is_active'=>true]); } }
