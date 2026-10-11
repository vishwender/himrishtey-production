<?php

namespace App\Services;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;

class MemberActivityHistory
{
    public function record(Connection $db, string $source, int $memberId, ?int $targetId, string $event, ?int $sourceId = null, array $metadata = []): void
    {
        if (! $db->getSchemaBuilder()->hasTable('member_activity_history')) {
            return;
        }
        $db->table('member_activity_history')->insert([
            'member_id' => $memberId, 'target_member_id' => $targetId,
            'source' => $source, 'source_id' => $sourceId, 'event' => $event,
            'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR), 'created_at' => now(),
        ]);
    }

    public function modelEvent(Model $model, string $event): void
    {
        $memberId = $model->getAttribute('member_id');
        if (! is_numeric($memberId)) {
            return;
        }
        $metadata = [];
        foreach (['status', 'amount', 'payment_id'] as $field) {
            if ($model->getAttribute($field) !== null) {
                $metadata[$field] = $model->getAttribute($field);
            }
        }
        if ($event === 'updated' && $model->wasChanged('status')) {
            $metadata['previous_status'] = $model->getRawOriginal('status');
        }
        $target = $model->getAttribute('profile_id') ?? $model->getAttribute('viewed_profile_id');
        $this->record($model->getConnection(), $model->getTable(), (int) $memberId,
            is_numeric($target) ? (int) $target : null, $event, (int) $model->getKey(), $metadata);
    }
}
