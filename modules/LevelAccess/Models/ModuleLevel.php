<?php

    namespace Modules\LevelAccess\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Module;
    use App\Models\Tenant\User;

    /**
     * Class ModuleLevel
     *
     * @package Modules\LevelAccess\Models
     * @mixin  ModelTenant
     */
    class ModuleLevel extends ModelTenant {
        protected $fillable = [
            'value',
            'description',
            'module_id',
            'route_name',
            'label_menu',
            'route_path',
        ];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
         */
        public function users() {
            return $this->belongsToMany(User::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function module() {
            return $this->belongsTo(Module::class, 'module_id');
        }

        /**
         * @return string
         */
        public function getValue()
        : string {
            return $this->value;
        }

        /**
         * @param string $value
         *
         * @return ModuleLevel
         */
        public function setValue(string $value)
        : ModuleLevel {
            $this->value = $value;
            return $this;
        }

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
         * @return ModuleLevel
         */
        public function setDescription(string $description)
        : ModuleLevel {
            $this->description = $description;
            return $this;
        }

        /**
         * @return int
         */
        public function getModuleId()
        : int {
            return $this->module_id;
        }

        /**
         * @param int $module_id
         *
         * @return ModuleLevel
         */
        public function setModuleId(int $module_id)
        : ModuleLevel {
            $this->module_id = $module_id;
            return $this;
        }

    }
