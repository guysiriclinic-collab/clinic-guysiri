<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CoursePurchase;

echo "Course Purchases:\n";
echo "================\n\n";

$courses = CoursePurchase::take(5)->get(['id', 'course_number', 'patient_id']);

foreach($courses as $course) {
    echo "ID: {$course->id}\n";
    echo "Course Number: {$course->course_number}\n";
    echo "Patient ID: {$course->patient_id}\n";
    echo "---\n";
}
