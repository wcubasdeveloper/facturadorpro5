<?php

namespace App\Models\Tenant;

/**
 * App\Models\Tenant\PersonType
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\Person[] $person
 * @property-read int|null $person_count
 * @method static \Illuminate\Database\Eloquent\Builder|PersonType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonType query()
 * @mixin \Eloquent
 */
class PersonType extends ModelTenant
{
    protected $fillable = [
        'description',

    ];

    /**
     * @return string
     */
    public function getDescription()
    : string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return PersonType
     */
    public function setDescription(string $description)
    : PersonType {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function person(){
        return $this->hasMany(Person::class);
    }

}
