<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'is_banned' => (bool) $this->ban,
                'email_verified_at' => $this->email_verified_at?->toISOString(),
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
            'relationships' => [
                'avatar' => [
                    'data' => $this->whenLoaded('avatar', function () {
                        $avatar = $this->resource->getRelation('avatar');

                        if (! $avatar) {
                            return null;
                        }

                        return [
                            'type' => 'attachments',
                            'id' => (string) $avatar->id,
                            'attributes' => [
                                'file_name' => $avatar->file_name,
                                'file_type' => $avatar->file_type,
                                'path' => $avatar->path,
                            ],
                        ];
                    }, null),
                ],
                'groups' => [
                    'data' => $this->whenLoaded(
                        'groups',
                        fn () => $this->groups
                            ->map(fn ($group) => [
                                'type' => 'groups',
                                'id' => (string) $group->id,
                                'attributes' => [
                                    'name' => $group->name,
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
