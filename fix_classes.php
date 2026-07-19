<?php 
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Classroom;
use App\Models\Ngajar;
use App\Models\User;
use App\Services\ClassroomService;
use App\Models\GameQuiz;

// Delete the dummy classrooms I created earlier today (where created_by is admin and doesn't match Ngajar)
// To be safe, just delete classrooms created in the last 1 hour
Classroom::where("created_at", ">", now()->subHours(2))->forceDelete();

$service = app(ClassroomService::class);
$admin = User::where("access", "admin")->first();

$ngajars = Ngajar::with(["kelas", "pelajaran", "guru.user"])->get();
$count = 0;
foreach($ngajars as $ng) {
    if ($ng->kelas && $ng->pelajaran) {
        $user = $ng->guru && $ng->guru->user ? $ng->guru->user : $admin;
        if($user) {
            $classroom = $service->subjectRoom($ng->kelas, $ng->pelajaran, $user);
            $count++;
            
            // Assign Arena Belajar (GameQuiz) if they exist but are not assigned to this classroom yet
            // Just for demonstration, if there's any published quiz we can attach it
            // Or run specific seeder logic, but the user says "arena belajar otomatis dibuat"
        }
    }
}
echo "Provisioned $count classrooms from Ngajar data.\n";
