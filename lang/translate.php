<?php
// From https://www.mind-it.info/2010/02/22/a-simple-approach-to-localization-in-php/

// Define the url path for the resources
defined('INCLUDE_PATH') or define('INCLUDE_PATH', './');

// Define the language using language code based
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$acceptLang = ['es', 'en'];
$lang = in_array($lang, $acceptLang) ? $lang : 'en';
defined('LANGUAGE') or define('LANGUAGE', $lang);

/**
* Load the proper language file and return the translated phrase
*
* The language file is JSON encoded and returns an associative arrays
*
* @param string $phrase The phrase that needs to be translated
* @return string
*/
function lang($phrase) {
    /* Static keyword is used to ensure the file is loaded only once */
    static $translations = NULL;
    /* If no instance of $translations has occured load the language file */
    if (is_null($translations)) {
        $lang_file = INCLUDE_PATH . '//lang/' . LANGUAGE . '.json';
        if (!file_exists($lang_file)) {
            return  $phrase;
        }
        $lang_file_content = file_get_contents($lang_file);
        // Load the language file as a JSON object and transform it into an associative array
        $translations = json_decode($lang_file_content, true);
    }
    // Avoid errors on untranslated phrases
    if (!array_key_exists($phrase, $translations)) {
        return $phrase;
    }
    return $translations[$phrase];
}