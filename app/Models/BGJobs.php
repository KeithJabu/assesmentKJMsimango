<?php

namespace App\Models;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $class
 * @property string $method
 * @property string $status
 * @property int $retry_count
 */
class BGJobs extends Model implements AssessmentInterface
{
    use SoftDeletes;

    protected $table = 'bg_jobs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class',
        'method',
        'parameter',
        'status',
        'retry_count'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameter' => 'array',
    ];

    /*** RELATIONSHIP FUNCTIONS **/

    /*** GETTER FUNCTIONS **/

    /**
     * @return Collection
     */
    public function getAllRunningJobs(): Collection
    {
        return BGJobs::query()->where('status', '=', static::RUNNING)->get();
    }

    /**
     * @return Collection
     */
    public function getAllFailedJobs(): Collection
    {
        return BGJobs::query()->where('status', '=', static::RUNNING)->get();
    }

    /**
     * @return Collection
     */
    public function getAllCompletedJobs(): Collection
    {
        return BGJobs::query()->where('status', '=', static::RUNNING)->get();
    }

    /**
     * @return Collection
     */
    public function getAllJobs(): Collection
    {
        return BGJobs::all();
    }

}
