<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = Report::all();
        $users = User::all();
        
        $comments = [
            'Terima kasih sudah melaporkan, akan segera kami tindak lanjuti.',
            'Mohon infokan lebih detail lokasi kerusakannya.',
            'Tim teknisi sedang dalam perjalanan.',
            'Perbaikan sedang dilakukan, mohon bersabar.',
            'Sudah diperbaiki, mohon konfirmasi kembali.',
            'Apakah masih ada kendala setelah perbaikan?',
            'Mohon maaf atas ketidaknyamanannya.',
            'Terima kasih atas laporannya, sangat membantu.',
            'Sedang menunggu spare parts, estimasi 2 hari lagi.',
            'Perbaikan selesai, fasilitas sudah dapat digunakan kembali.',
        ];
        
        foreach ($reports as $report) {
            // Add 1-3 comments per report
            $numComments = rand(0, 3);
            
            for ($i = 0; $i < $numComments; $i++) {
                $user = $users->random();
                
                Comment::create([
                    'report_id' => $report->id,
                    'user_id' => $user->id,
                    'comment' => $comments[array_rand($comments)],
                    'created_at' => $report->created_at->addHours(rand(1, 48)),
                ]);
            }
        }
    }
}