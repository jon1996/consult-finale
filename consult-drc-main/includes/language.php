<?php
session_start();

class Language {
    private $lang;
    private $translations;
    
    public function __construct() {
        // Check URL parameter first, then session
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en', 'zh'])) {
            $this->lang = $_GET['lang'];
            $_SESSION['lang'] = $this->lang;
        } else {
            $this->lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';
        }
        
        // Load language file
        $this->loadLanguage();
    }
    
    private function loadLanguage() {
        $file = __DIR__ . '/../languages/' . $this->lang . '.php';
        if (file_exists($file)) {
            $this->translations = include $file;
        } else {
            // Fallback to French
            $this->translations = include __DIR__ . '/../languages/fr.php';
        }
    }
    
    public function get($key) {
        $keys = explode('.', $key);
        $value = $this->translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                // Log missing key for debugging
                $logLine = date('c') . " | LANG={$this->lang} | MISSING:" . $key . "\n";
                @file_put_contents(__DIR__ . '/../language_debug.log', $logLine, FILE_APPEND);
                return $key; // Return key if translation not found
            }
        }
        
        return $value;
    }
    
    public function setLanguage($lang) {
        if (in_array($lang, ['fr', 'en', 'zh'])) {
            $_SESSION['lang'] = $lang;
            $this->lang = $lang;
            $this->loadLanguage();
        }
    }
    
    public function getCurrentLanguage() {
        return $this->lang;
    }
    
    public function getAvailableLanguages() {
        return [
            'fr' => ['flag' => '🇫🇷', 'name' => 'Français'],
            'en' => ['flag' => '🇺🇸', 'name' => 'English'],
            'zh' => ['flag' => '🇨🇳', 'name' => '中文']
        ];
    }
}

// Global function for easy translation
function __($key) {
    global $lang;
    return $lang->get($key);
}

// Initialize language
$lang = new Language();
