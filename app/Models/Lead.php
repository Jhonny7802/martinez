<?php

namespace App\Models;

use App\Models\Contracts\Taggable;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * Class Lead
 *
 * @version April 20, 2020, 12:43 pm UTC
 *
 * @property int $id
 * @property int $status_id
 * @property int $source_id
 * @property int|null $assign_to
 * @property string $name
 * @property string|null $position
 * @property string|null $email
 * @property int|null $estimate_budget
 * @property string|null $website
 * @property string|null $phone
 * @property string|null $company
 * @property string|null $description
 * @property int|null $default_language
 * @property int|null $public
 * @property int|null $contacted_today
 * @property string|null $date_contacted
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $address
 * @property-read LeadSource $leadSource
 * @property-read LeadStatus $leadStatus
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 *
 * @method static Builder|Lead newModelQuery()
 * @method static Builder|Lead newQuery()
 * @method static Builder|Lead query()
 * @method static Builder|Lead whereAssignTo($value)
 * @method static Builder|Lead whereCompany($value)
 * @method static Builder|Lead whereContactedToday($value)
 * @method static Builder|Lead whereCreatedAt($value)
 * @method static Builder|Lead whereDateContacted($value)
 * @method static Builder|Lead whereDefaultLanguage($value)
 * @method static Builder|Lead whereDescription($value)
 * @method static Builder|Lead whereEmail($value)
 * @method static Builder|Lead whereId($value)
 * @method static Builder|Lead whereName($value)
 * @method static Builder|Lead wherePhone($value)
 * @method static Builder|Lead wherePosition($value)
 * @method static Builder|Lead wherePublic($value)
 * @method static Builder|Lead whereSourceId($value)
 * @method static Builder|Lead whereStatusId($value)
 * @method static Builder|Lead whereUpdatedAt($value)
 * @method static Builder|Lead whereWebsite($value)
 *  * @method static Builder|Lead whereEstimateBudget($value)
 * @mixin Eloquent
 *
 * @property-read User|null $assignedTo
 * @property string $company_name
 * @property-read Collection|Note[] $notes
 * @property-read int|null $notes_count
 *
 * @method static Builder|Lead whereCompanyName($value)
 *
 * @property int $lead_convert_customer
 * @property string|null $lead_convert_date
 *
 * @method static Builder|Lead whereLeadConvertCustomer($value)
 * @method static Builder|Lead whereLeadConvertDate($value)
 * @method static Builder|Lead whereCountry($value)
 */
class Lead extends Model implements Taggable
{
    /**
     * @var string
     */
    protected $table = 'leads';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status_id',
        'source_id',
        'company_name',
        'estimate_budget',
        'assign_to',
        'position',
        'website',
        'phone',
        'description',
        'default_language',
        'public',
        'contacted_today',
        'date_contacted',
        'lead_convert_date',
        'country',
    ];

    const LANGUAGES = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'ru' => 'Russian',
        'pt' => 'Portuguese',
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'tr' => 'Turkish',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status_id' => 'integer',
        'source_id' => 'integer',
        'company_name' => 'string',
        'estimate_budget' => 'double',
        'assign_to' => 'integer',
        'position' => 'string',
        'website' => 'string',
        'phone' => 'string',
        'description' => 'string',
        'default_language' => 'string',
        'public' => 'integer',
        'contacted_today' => 'integer',
        'date_contacted' => 'datetime',
        'lead_convert_date' => 'string',
        'country' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:leads,name',
        'status_id' => 'required',
        'source_id' => 'required',
        'company_name' => 'required',
        'phone' => 'nullable|unique:leads,phone',
        'website' => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
    ];

    /**
     * @var string[]
     */
    public static $editRules = [
        'status_id' => 'required',
        'source_id' => 'required',
        'company_name' => 'required',
        'website' => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
    ];

    /**
     * @return BelongsTo
     */
    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    /**
     * @return BelongsTo
     */
    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    /**
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_to');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerType()
    {
        return self::class;
    }

    /**
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'owner_id');
    }

    /**
     * @return BelongsTo
     */
    public function leadCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
