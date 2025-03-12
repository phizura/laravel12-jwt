<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait AttributeCaseConverter
{
    public function convertToCamelCase($data)
    {
        if (isset($data) && is_object($data)) {
            $convertedData = [];
            foreach ($data->toArray() as $key => $value) {
                $camelKey = Str::camel($key); // Mengubah snake_case menjadi camelCase

                // Konversi nilai menjadi string jika bukan array atau objek
                if (!is_array($value) && !is_object($value)) {
                    $convertedData[$camelKey] = is_string($value) ? $value : strval($value);
                } else {
                    $convertedData[$camelKey] = $value; // Biarkan array dan objek tetap seperti itu
                }
            }
            return $convertedData;
        }
        return $data;
    }

    public function camelCaseToSnakeCase($data)
    {
        if (isset($data)) {
            $dataToCreated = [];
            foreach ($data as $key => $value) {
                $snakeCaseKey = Str::snake($key); // Mengubah camelCase menjadi snake_case
                $dataToCreated[$snakeCaseKey] = $value;
            }
            return $dataToCreated;
        }

        return $data;
    }
}
