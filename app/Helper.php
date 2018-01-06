<?php

namespace App;


class Helper {
    /**
     * Convert property.sub to property[sub]
     *
     * @param $eloquentName
     * @return string
     */
    public static function eloquentToInputName($eloquentName) {
        $separated = explode('.', $eloquentName);
        foreach ($separated as $key => $value) {
            if ($key > 0) {
                $separated[$key] = '[' . $value . ']';
            }
        }
        
        return implode('', $separated);
    }
    
    public static function materialColor(int $number) {
        $availableColors = ['pink', 'purple', 'deep-purple', 'indigo', 'blue', 'cyan', 'teal', 'lime', 'amber', 'orange', 'deep-orange', 'brown'];
        $colorCode = abs($number % count($availableColors));
        return $availableColors[$colorCode];
    }
}