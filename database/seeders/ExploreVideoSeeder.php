<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExploreVideo;

class ExploreVideoSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'prompt' => 'A neon cyberpunk city street at night, rain reflections',
                'thumbnail_url' => 'https://picsum.photos/800/450?random=1',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'duration' => 5,
                'resolution' => '720p',
                'is_public' => true,
                'views_count' => 2450,
                'likes_count' => 312,
                'tags' => ['cyberpunk','city','night'],
                'description' => 'Moody neon atmosphere with rain and reflections.'
            ],
            [
                'prompt' => 'Flying through a colorful abstract particle field',
                'thumbnail_url' => 'https://picsum.photos/800/450?random=2',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                'duration' => 5,
                'resolution' => '1080p',
                'is_public' => true,
                'views_count' => 1280,
                'likes_count' => 210,
                'tags' => ['abstract','particles'],
                'description' => 'Vibrant motion graphics with particle simulation.'
            ],
            [
                'prompt' => 'A serene waterfall in a lush forest, cinematic',
                'thumbnail_url' => 'https://picsum.photos/800/450?random=3',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'duration' => 5,
                'resolution' => '720p',
                'is_public' => true,
                'views_count' => 980,
                'likes_count' => 150,
                'tags' => ['nature','waterfall','forest'],
                'description' => 'Relaxing nature ambience with soft lighting.'
            ],
            [
                'prompt' => 'Low-poly mountains at sunrise, stylized art',
                'thumbnail_url' => 'https://picsum.photos/800/450?random=4',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'duration' => 5,
                'resolution' => '1080p',
                'is_public' => true,
                'views_count' => 1530,
                'likes_count' => 230,
                'tags' => ['stylized','mountains','sunrise'],
                'description' => 'Minimal low-poly aesthetic with warm tones.'
            ],
        ];

        // Get a user ID for seeding (create a demo user if none exists)
        $userId = \App\Models\User::first()?->id ?? \App\Models\User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ])->id;

        // Seed base samples if table is empty
        if (ExploreVideo::count() === 0) {
            foreach ($samples as $data) {
                ExploreVideo::create(array_merge($data, ['user_id' => $userId]));
            }
        }

        // Ensure at least 20 explore videos exist
        $resolutions = ['720p', '1080p'];
        $extraThumbs = [
            'https://picsum.photos/800/450?random=5',
            'https://picsum.photos/800/450?random=6',
            'https://picsum.photos/800/450?random=7',
            'https://picsum.photos/800/450?random=8',
            'https://picsum.photos/800/450?random=9',
            'https://picsum.photos/800/450?random=10',
            'https://picsum.photos/800/450?random=11',
            'https://picsum.photos/800/450?random=12',
        ];
        $extraPrompts = [
            'Cinematic drone shot over misty mountains',
            'Vibrant jellyfish swimming in deep ocean',
            'City timelapse with light trails at night',
            'Abstract neon waves and pulsing lines',
            'Fantasy forest with floating lights',
            'Macro shot of raindrops on leaves',
            'Retro arcade vibes with pixel art',
            'Calm beach waves under pink sunset',
        ];

        $videoUrls = [
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4',
        ];

        $categories = [
            ['cinematic'], ['realistic'], ['anime'], ['cartoon'], ['artistic'], ['abstract'],
        ];

        $current = ExploreVideo::count();
        for ($i = $current; $i < 20; $i++) {
            $thumb = $extraThumbs[$i % count($extraThumbs)];
            $prompt = $extraPrompts[$i % count($extraPrompts)] . ' #' . ($i + 1);
            $tags = $categories[$i % count($categories)];
            $resolution = $resolutions[$i % count($resolutions)];
            $videoUrl = $videoUrls[$i % count($videoUrls)];

            ExploreVideo::create([
                'user_id' => $userId,
                'prompt' => $prompt,
                'thumbnail_url' => $thumb,
                'video_url' => $videoUrl,
                'duration' => 5,
                'resolution' => $resolution,
                'is_public' => true,
                'views_count' => rand(200, 5000),
                'likes_count' => rand(50, 800),
                'tags' => $tags,
                'description' => 'Auto-generated showcase clip',
            ]);
        }
    }
}


