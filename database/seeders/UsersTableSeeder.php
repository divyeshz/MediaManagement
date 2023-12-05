<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = [];
    $faker = Factory::create();

    for ($i = 0; $i < 30; $i++) {
      $users[] = [
        'id' => Str::uuid(),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->numerify('##########'),
        'profile' => $faker->sentence,
        'gender' => $faker->randomElement(['male', 'female']),
        'social_id' => $faker->unique()->numerify,
        'social_type' => 'facebook',
        'is_active' => '1',
        'email_verified_at' => now(),
        'created_by' => Str::uuid(),
        'updated_by' => Str::uuid(),
        'deleted_by' => null,
        'created_at' => now(),
        'updated_at' => now(),
      ];
    }

    User::insert($users);
  }
}
