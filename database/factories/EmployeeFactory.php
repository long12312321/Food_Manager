<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $this->faker->name, // Tên ngẫu nhiên
        'code' => $this->faker->unique()->word, // Mã nhân viên ngẫu nhiên
        'class' => $this->faker->word, // Lớp ngẫu nhiên
        'enterprise' => $this->faker->company, // Tên doanh nghiệp ngẫu nhiên
        'phone' => generatePhoneNumber($faker), // Số điện thoại tùy chỉnh
    ];
});

function generatePhoneNumber($faker) {
    return $faker->numerify($faker->randomElement(['##########', '###########'])); // 10 hoặc 11 ký tự
}