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
                'thumbnail_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800',
                'video_url' => 'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
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
                'thumbnail_url' => 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=800',
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
                'thumbnail_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=800',
                'video_url' => 'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
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
                'thumbnail_url' => 'https://images.unsplash.com/photo-1521335629791-ce4aec67dd53?q=80&w=800',
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
            'https://images.unsplash.com/photo-1517814763157-9cffbec72538?q=80&w=800',
            'https://images.unsplash.com/photo-1511765224389-37f0e77cf0eb?q=80&w=800',
            'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=800',
            'https://images.unsplash.com/photo-1520975657283-c1dfc0f1f9f1?q=80&w=800',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=800',
            'https://images.unsplash.com/photo-1495567720989-cebdbdd97913?q=80&w=800',
            'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=800',
            'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=800',
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
            'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
            'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
            'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
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


