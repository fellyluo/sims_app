<?php

use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\Mission;
use App\Models\MissionAssignment;
use App\Models\User;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$classroom = Classroom::where('class_code', '2N3-ICS0')->first()
    ?? Classroom::where('status', 'published')->first();

if (! $classroom) {
    echo "No classroom found\n";
    exit(1);
}

$member = ClassroomMember::where('classroom_id', $classroom->uuid)
    ->where('role_in_class', 'siswa')
    ->with('user')
    ->first();

$missions = MissionAssignment::where('classroom_id', $classroom->uuid)->with('mission')->get();

echo "CLASSROOM: {$classroom->title} ({$classroom->class_code})\n";
echo "URL: /ruang-kelas/{$classroom->class_code}/jagat-misi\n";
if ($member?->user) {
    echo "SISWA: {$member->user->username}\n";
}
echo "MISSIONS:\n";
foreach ($missions as $a) {
    echo " - {$a->mission?->title} slug={$a->mission?->slug}\n";
}
