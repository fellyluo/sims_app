<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Classroom;
use App\Models\GameQuiz;

$classrooms = Classroom::all();
$count = 0;
foreach($classrooms as $classroom) {
    $existingQuiz = GameQuiz::where("classroom_id", $classroom->uuid)->first();
    if (!$existingQuiz) {
        GameQuiz::create([
            "classroom_id" => $classroom->uuid,
            "created_by" => $classroom->created_by,
            "title" => "Arena Belajar — " . ($classroom->pelajaran?->nama ?? "Umum"),
            "instructions" => "Selamat datang di Arena Belajar! Tambahkan soal interaktif di sini.",
            "mode" => "async",
            "scoring_mode" => "accuracy",
            "max_score" => 100,
            "instant_feedback" => true,
            "show_leaderboard" => true,
            "status" => "draft",
        ]);
        $count++;
    }
}
echo "Created $count Arena Belajar quizzes.\n";
