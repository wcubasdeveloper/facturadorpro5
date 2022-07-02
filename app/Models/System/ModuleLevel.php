<?php

    namespace App\Models\System;

    use Hyn\Tenancy\Abstracts\SystemModel;

    /**
     * Class ModuleLevel
     *
     * @package App\Models\System
     * @mixin SystemModel
     */
    class ModuleLevel extends SystemModel {

        protected $table = 'module_levels';

        protected $fillable = [
            'value',
            'description',
            'module_id',

        ];

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
