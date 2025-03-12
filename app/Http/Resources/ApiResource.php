<?php

namespace App\Http\Resources;

use App\Traits\AttributeCaseConverter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{

    use AttributeCaseConverter;
    // Define properties
    protected $status;
    protected $message;
    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @return void
     */
    public function __construct($resource, $status, $message)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'meta' => [
                'status' => $this->status,
                'message' => $this->message,
            ],
            'data' => $this->convertToCamelCase($this->resource),
        ];
    }
}
