<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => optional($this->due_date)->toDateString(),
            'completed_at' => optional($this->complete_at)->toDateString(),
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updateted_at' => $this->updated_at->toDateTimeString()
        ];
    }
}