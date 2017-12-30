<?php

namespace App;


class Helper {
    /**
     * Convert property.sub to property[sub]
     *
     * @param $eloquentName
     * @return string
     */
    public static function eloquentToInputName($eloquentName): string {
        $separated = explode('.', $eloquentName);
        foreach ($separated as $key => $value) {
            if ($key > 0) {
                $separated[$key] = '[' . $value . ']';
            }
        }
        
        return implode('', $separated);
    }
}