<?php
/**
 * Contact Configuration File
 * DRC Business Consult - Contact System Configuration
 */

// Company Information
define('COMPANY_NAME', 'DRC Business Consult');
define('COMPANY_SLOGAN', 'Your gateway to business opportunities in DRC');

// Main Office Contact Information
define('MAIN_OFFICE_ADDRESS', '4260/A, Avenue de la Mission, Quartier Révolution, Gombe, Kinshasa, DR Congo');
define('MAIN_OFFICE_PHONE', '+243 81-868-0496');
define('MAIN_OFFICE_EMAIL', 'info@drcbusinessconsult.com');

// Secondary Office
define('SECONDARY_OFFICE_ADDRESS', 'Avenue Mobutu 45, Lubumbashi, Haut-Katanga, DRC');
define('SECONDARY_OFFICE_PHONE', '+243 819 567 890');

// Emergency Contact
define('EMERGENCY_PHONE', '+243 998 765 432');

// Business Hours
define('OFFICE_HOURS_WEEKDAY', 'Monday - Friday: 8:00 AM - 6:00 PM');
define('OFFICE_HOURS_SATURDAY', 'Saturday: 9:00 AM - 3:00 PM');
define('OFFICE_HOURS_SUNDAY', 'Sunday: Closed');

// Social Media
define('WHATSAPP_NUMBER', '+243817234567');
define('FACEBOOK_PAGE', 'https://facebook.com/drcbusinessconsult');
define('LINKEDIN_PAGE', 'https://linkedin.com/company/drc-business-consult');
define('TWITTER_HANDLE', '@DRCBusinessConsult');

// Business Registration
define('BUSINESS_LICENSE', 'RCCM: CD/KNG/RCCM/23-B-1234');
define('LICENSE_NUMBER', 'DRC-BC-2024-001');
define('TAX_ID', 'A1807543C');

// Certifications
define('ISO_CERTIFICATION', 'ISO 9001:2015');
define('CHAMBER_MEMBERSHIP', 'DRC Chamber of Commerce Member #CC-2024-156');

// Service Statistics (Updated as of July 2025)
define('TOTAL_CLIENTS', '847');
define('PROJECTS_COMPLETED', '1,247');
define('SECTORS_COVERED', '18');
define('YEARS_EXPERIENCE', '12');

// Response Times
define('EMAIL_RESPONSE_TIME', '4.2 hours average');
define('PHONE_RESPONSE_TIME', '2 hours average');
define('CONSULTATION_BOOKING', 'Same day scheduling');

// Success Metrics
define('CLIENT_SATISFACTION', '97.8%');
define('PROJECT_SUCCESS_RATE', '94.5%');
define('MINING_PROJECTS', '47 completed projects worth $2.3B');
define('ENERGY_PROJECTS', '23 renewable energy projects across 8 provinces');
define('TELECOM_PARTNERSHIPS', '15 international-local operator connections');
define('TRANSPORT_PROJECTS', '31 logistics & infrastructure initiatives');

// Languages Supported
$supported_languages = [
    'French' => 'Français',
    'English' => 'English', 
    'Lingala' => 'Lingala',
    'Swahili' => 'Kiswahili',
    'Chinese' => '中文',
    'Portuguese' => 'Português'
];

// Office Locations with Coordinates
$office_locations = [
    'main' => [
        'name' => 'Kinshasa Main Office',
        'address' => MAIN_OFFICE_ADDRESS,
        'phone' => MAIN_OFFICE_PHONE,
        'email' => MAIN_OFFICE_EMAIL,
        'lat' => -4.3317,
        'lng' => 15.3139,
        'hours' => [
            'weekday' => OFFICE_HOURS_WEEKDAY,
            'saturday' => OFFICE_HOURS_SATURDAY,
            'sunday' => OFFICE_HOURS_SUNDAY
        ]
    ],
    'secondary' => [
        'name' => 'Lubumbashi Branch',
        'address' => SECONDARY_OFFICE_ADDRESS,
        'phone' => SECONDARY_OFFICE_PHONE,
        'email' => 'lubumbashi@drcbusinessconsult.cd',
        'lat' => -11.6667,
        'lng' => 27.4667,
        'hours' => [
            'weekday' => 'Monday - Friday: 9:00 AM - 5:00 PM',
            'saturday' => 'Saturday: 10:00 AM - 2:00 PM',
            'sunday' => 'Sunday: Closed'
        ]
    ]
];

// Service Categories
$service_categories = [
    'visit_drc' => [
        'name' => 'Visit DRC Services',
        'description' => 'Complete travel assistance and business trip coordination',
        'response_time' => '2 hours',
        'pricing_from' => '$299 USD'
    ],
    'doing_business' => [
        'name' => 'Doing Business in DRC',
        'description' => 'Business establishment and investment guidance',
        'response_time' => '4 hours',
        'pricing_from' => '$799 USD'
    ],
    'sector_consulting' => [
        'name' => 'Sector-Specific Consulting',
        'description' => 'Mining, Energy, Telecom, Transport specialized consulting',
        'response_time' => '6 hours',
        'pricing_from' => '$1,299 USD'
    ],
    'legal_compliance' => [
        'name' => 'Legal & Compliance',
        'description' => 'Regulatory compliance and legal framework navigation',
        'response_time' => '24 hours',
        'pricing_from' => '$599 USD'
    ]
];

// Contact Form Settings
define('MAX_MESSAGE_LENGTH', 2000);
define('CONTACT_FORM_EMAILS', [
    'general' => 'info@drcbusinessconsult.com',
    'business' => 'business@drcbusinessconsult.cd',
    'technical' => 'support@drcbusinessconsult.cd',
    'partnerships' => 'partnerships@drcbusinessconsult.cd'
]);

// Google Maps API Settings
define('GOOGLE_MAPS_API_KEY', 'your-google-maps-api-key-here');
define('MAP_ZOOM_LEVEL', 15);
define('MAP_CENTER_LAT', -4.3317);
define('MAP_CENTER_LNG', 15.3139);

// Rate Limiting
define('CONTACT_RATE_LIMIT', 5); // Max 5 submissions per hour per IP
define('RATE_LIMIT_WINDOW', 3600); // 1 hour in seconds

// Auto-reply Messages
$auto_reply_messages = [
    'en' => [
        'subject' => 'Thank you for contacting DRC Business Consult',
        'body' => 'We have received your inquiry and will respond within 24 hours. Our team is committed to helping you succeed in DRC.'
    ],
    'fr' => [
        'subject' => 'Merci de nous avoir contactés - DRC Business Consult',
        'body' => 'Nous avons reçu votre demande et vous répondrons dans les 24 heures. Notre équipe s\'engage à vous aider à réussir en RDC.'
    ],
    'zh' => [
        'subject' => '感谢联系刚果商业咨询',
        'body' => '我们已收到您的咨询，将在24小时内回复。我们的团队致力于帮助您在刚果民主共和国取得成功。'
    ]
];

// Error Messages
$error_messages = [
    'rate_limit' => 'Too many submissions. Please wait before sending another message.',
    'invalid_email' => 'Please provide a valid email address.',
    'message_too_long' => 'Message exceeds maximum length of ' . MAX_MESSAGE_LENGTH . ' characters.',
    'required_fields' => 'Please fill in all required fields.',
    'server_error' => 'Server error occurred. Please try again later.',
    'spam_detected' => 'Suspicious activity detected. Your message has been flagged for review.'
];

// Success Messages
$success_messages = [
    'message_sent' => 'Your message has been sent successfully! We will get back to you soon.',
    'subscription_confirmed' => 'Newsletter subscription confirmed.',
    'consultation_booked' => 'Consultation successfully booked.'
];

?>
