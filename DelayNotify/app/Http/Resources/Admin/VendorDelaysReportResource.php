<?php

namespace App\Http\Resources\Admin;

use App\Models\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class VendorDelaysReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'total_delay_time' => $this->total_delay_time,
            'type' => Vendor::typeReadable($this->type),
            'status' => Vendor::statusReadable($this->status),
        ];
    }
}
