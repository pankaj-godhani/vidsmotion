<?php

namespace App\Services;

class SeoService
{
    public static function getPageSeo($page, $additionalData = [])
    {
        $baseUrl = config('app.url');
        $defaultImage = $baseUrl . '/images/og-image.jpg';

        $seoData = [
            'home' => [
                'title' => 'VidsMotion - AI Video Generator | Create Stunning Videos with AI',
                'description' => 'Transform your ideas into professional videos with VidsMotion AI video generator. Create stunning, high-quality videos in minutes using advanced artificial intelligence technology.',
                'keywords' => 'AI video generator, artificial intelligence, video creation, AI videos, video maker, automated video, AI content creation, video editing, AI technology',
                'og_title' => 'VidsMotion - AI Video Generator',
                'og_description' => 'Create stunning AI-generated videos in minutes. Transform your ideas into professional videos with advanced AI technology.',
                'og_image' => $defaultImage,
                'canonical' => $baseUrl,
                'structured_data' => self::getWebsiteStructuredData()
            ],
            'features' => [
                'title' => 'Powerful AI Video Features | VidsMotion Video Generator',
                'description' => 'Discover the powerful features of VidsMotion AI video generator. Advanced AI technology, multiple video styles, high-quality output, and easy-to-use interface.',
                'keywords' => 'AI video features, video generator features, AI video technology, video creation tools, AI video capabilities',
                'og_title' => 'Powerful AI Video Features - VidsMotion',
                'og_description' => 'Explore the advanced features of our AI video generator. Create professional videos with cutting-edge artificial intelligence.',
                'og_image' => $baseUrl . '/images/features-og.jpg',
                'canonical' => $baseUrl . '/features',
                'structured_data' => self::getFeaturesStructuredData()
            ],
            'pricing' => [
                'title' => 'AI Video Generator Pricing | Affordable Plans - VidsMotion',
                'description' => 'Choose the perfect plan for your AI video creation needs. Affordable pricing for individuals, professionals, and businesses. Start creating videos today!',
                'keywords' => 'AI video pricing, video generator cost, AI video plans, video creation pricing, affordable video maker',
                'og_title' => 'AI Video Generator Pricing - VidsMotion',
                'og_description' => 'Affordable pricing plans for AI video generation. Choose the perfect plan for your video creation needs.',
                'og_image' => $baseUrl . '/images/pricing-og.jpg',
                'canonical' => $baseUrl . '/pricing',
                'structured_data' => self::getPricingStructuredData()
            ],
            'explore' => [
                'title' => 'Explore AI Videos | VidsMotion Video Gallery',
                'description' => 'Explore amazing AI-generated videos created with VidsMotion. Get inspired by our video gallery and discover the possibilities of AI video creation.',
                'keywords' => 'AI video gallery, video examples, AI video inspiration, video showcase, AI video samples',
                'og_title' => 'Explore AI Videos - VidsMotion Gallery',
                'og_description' => 'Discover amazing AI-generated videos and get inspired for your next video creation project.',
                'og_image' => $baseUrl . '/images/explore-og.jpg',
                'canonical' => $baseUrl . '/explore',
                'structured_data' => self::getGalleryStructuredData()
            ],
            'contact' => [
                'title' => 'Contact Us | VidsMotion AI Video Generator Support',
                'description' => 'Get in touch with VidsMotion support team. We\'re here to help you with AI video generation, technical support, and any questions you may have.',
                'keywords' => 'contact VidsMotion, AI video support, video generator help, customer support',
                'og_title' => 'Contact VidsMotion Support',
                'og_description' => 'Get help with AI video generation. Contact our support team for assistance.',
                'og_image' => $defaultImage,
                'canonical' => $baseUrl . '/contact',
                'structured_data' => self::getContactStructuredData()
            ],
            'faq' => [
                'title' => 'FAQ | Frequently Asked Questions - VidsMotion',
                'description' => 'Find answers to frequently asked questions about VidsMotion AI video generator. Learn about features, pricing, and how to create amazing videos.',
                'keywords' => 'VidsMotion FAQ, AI video questions, video generator help, frequently asked questions',
                'og_title' => 'VidsMotion FAQ - Frequently Asked Questions',
                'og_description' => 'Get answers to common questions about AI video generation with VidsMotion.',
                'og_image' => $defaultImage,
                'canonical' => $baseUrl . '/faq',
                'structured_data' => self::getFaqStructuredData()
            ],
            'privacy' => [
                'title' => 'Privacy Policy | VidsMotion AI Video Generator',
                'description' => 'Read VidsMotion privacy policy. Learn how we protect your data and privacy when using our AI video generation service.',
                'keywords' => 'VidsMotion privacy policy, data protection, privacy, AI video privacy',
                'og_title' => 'VidsMotion Privacy Policy',
                'og_description' => 'Learn how VidsMotion protects your privacy and data.',
                'og_image' => $defaultImage,
                'canonical' => $baseUrl . '/privacy',
                'structured_data' => null
            ],
            'terms' => [
                'title' => 'Terms of Service | VidsMotion AI Video Generator',
                'description' => 'Read VidsMotion terms of service. Understand the terms and conditions for using our AI video generation platform.',
                'keywords' => 'VidsMotion terms, terms of service, AI video terms, user agreement',
                'og_title' => 'VidsMotion Terms of Service',
                'og_description' => 'Read the terms and conditions for using VidsMotion AI video generator.',
                'og_image' => $defaultImage,
                'canonical' => $baseUrl . '/terms',
                'structured_data' => null
            ]
        ];

        $pageData = $seoData[$page] ?? $seoData['home'];

        // Merge additional data if provided
        if (!empty($additionalData)) {
            $pageData = array_merge($pageData, $additionalData);
        }

        return $pageData;
    }

    private static function getWebsiteStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "VidsMotion",
            "description" => "AI-powered video generator that creates stunning videos from text prompts",
            "url" => config('app.url'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => config('app.url') . "/explore?q={search_term_string}",
                "query-input" => "required name=search_term_string"
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "VidsMotion",
                "url" => config('app.url'),
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => config('app.url') . "/images/logo.png"
                ]
            ]
        ];
    }

    private static function getFeaturesStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "SoftwareApplication",
            "name" => "VidsMotion AI Video Generator",
            "description" => "Advanced AI video generator with powerful features for creating professional videos",
            "url" => config('app.url') . "/features",
            "applicationCategory" => "MultimediaApplication",
            "operatingSystem" => "Web Browser",
            "offers" => [
                "@type" => "Offer",
                "price" => "0",
                "priceCurrency" => "USD",
                "description" => "Free trial available"
            ],
            "featureList" => [
                "AI-powered video generation",
                "Multiple video styles",
                "High-quality output",
                "Easy-to-use interface",
                "Fast processing",
                "Custom video creation"
            ]
        ];
    }

    private static function getPricingStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => "VidsMotion AI Video Generator",
            "description" => "AI-powered video generation service with flexible pricing plans",
            "url" => config('app.url') . "/pricing",
            "offers" => [
                [
                    "@type" => "Offer",
                    "name" => "Standard Plan",
                    "price" => "99",
                    "priceCurrency" => "INR",
                    "description" => "7 days access with basic features"
                ],
                [
                    "@type" => "Offer",
                    "name" => "Pro Monthly",
                    "price" => "299",
                    "priceCurrency" => "INR",
                    "description" => "1 month access with premium features"
                ],
                [
                    "@type" => "Offer",
                    "name" => "Premier Yearly",
                    "price" => "3999",
                    "priceCurrency" => "INR",
                    "description" => "1 year access with all features"
                ]
            ]
        ];
    }

    private static function getGalleryStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "ImageGallery",
            "name" => "VidsMotion AI Video Gallery",
            "description" => "Explore amazing AI-generated videos created with VidsMotion",
            "url" => config('app.url') . "/explore"
        ];
    }

    private static function getContactStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "ContactPage",
            "name" => "Contact VidsMotion",
            "description" => "Get in touch with VidsMotion support team",
            "url" => config('app.url') . "/contact"
        ];
    }

    private static function getFaqStructuredData()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "name" => "VidsMotion FAQ",
            "description" => "Frequently asked questions about VidsMotion AI video generator",
            "url" => config('app.url') . "/faq",
            "mainEntity" => [
                [
                    "@type" => "Question",
                    "name" => "What is VidsMotion?",
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => "VidsMotion is an AI-powered video generator that creates stunning videos from text prompts using advanced artificial intelligence technology."
                    ]
                ],
                [
                    "@type" => "Question",
                    "name" => "How does AI video generation work?",
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => "Our AI analyzes your text prompt and generates a video that matches your description using advanced machine learning algorithms."
                    ]
                ],
                [
                    "@type" => "Question",
                    "name" => "What video formats are supported?",
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => "VidsMotion supports MP4 video format with high-quality output suitable for various platforms and use cases."
                    ]
                ]
            ]
        ];
    }
}
