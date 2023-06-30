<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function ordersInDelay(): Collection
    {
        return $this->hasMany(DelayReport::class, 'agent_id')
            ->where('status', DelayReport::STATUS_IN_PROGRESS)
            ->with(['order' => function($query) {
                $query->select('id');
            }])
            ->get();
    }

    public function pendingOrdersCount(): int
    {
        $count = $this->ordersInDelay()->count();
        return  $count ?? 0;
    }


    public static function createAgentIfNotExisted($id)
    {
        $agent = self::where('id', $id)->first();
        if ($agent) {
            return $agent;
        } else {
            DB::table('agents')->insert([
                [
                    'id' => $id,
                    'name' => "agent {$id}" ,
                    'email' => "test{$id}@example.com",
                ]
            ]);
            return self::where('id', $id)->first();
        }
    }
}
