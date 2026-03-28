<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'expenses',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'amount' => $this->amount,
                'date' => $this->date,
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
            'relationships' => [
                'payer' => [
                    'data' => $this->whenLoaded(
                        'payer',
                        function () {
                            return [
                                'type' => 'memberships',
                                'id' =>  $this->payer->id,
                                'attributes' => [
                                    'user_id' => $this->payer->user_id,
                                    'name' => $this->when(
                                        $this->payer->relationLoaded('user'),
                                        fn () => $this->payer->user?->name
                                    ),
                                    'avatar' => $this->when(
                                        $this->payer->relationLoaded('user'),
                                        fn () => $this->payer->user?->avatar
                                    ),
                                ],
                            ];
                        },
                        $this->payer_id ? [
                            'type' => 'memberships',
                            'id' => (string) $this->payer_id,
                        ] : null
                    ),
                ],
                'category' => [
                    'data' => $this->whenLoaded(
                        'category',
                        fn () => $this->category ? [
                            'type' => 'categories',
                            'id' => (string) $this->category->id,
                            'attributes' => [
                                'name' => $this->category->name,
                            ],
                        ] : null,
                        $this->category_id ? [
                            'type' => 'categories',
                            'id' => (string) $this->category_id,
                        ] : null
                    ),
                ],
                'attachments' => [
                    'data' => $this->whenLoaded(
                        'attachments',
                        fn () => $this->attachments
                            ->map(fn ($file) => [
                                'type' => 'attachments',
                                'id' => (string) $file->id,
                                'attributes' => [
                                    'file_name' => $file->file_name,
                                    'file_type' => $file->file_type,
                                    'path' => $file->path,
                                ],
                            ])
                            ->values()
                            ->all(),
                        []
                    ),
                ],
            ],
        ];
    }
}
