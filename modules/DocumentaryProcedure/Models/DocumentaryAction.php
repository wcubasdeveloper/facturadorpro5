<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryAction
     *
     * @property-read mixed $active
     * @method static Builder|DocumentaryAction newModelQuery()
     * @method static Builder|DocumentaryAction newQuery()
     * @method static Builder|DocumentaryAction query()
     * @mixin \Eloquent
     */
    class DocumentaryAction extends ModelTenant {
        use UsesTenantConnection;
        protected $table = 'documentary_actions';

        protected $fillable = ['description', 'active', 'name'];

        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        /**
         * @return string
         */
        public function getDescription()
         {
            return $this->description;
        }

        /**
         * @param string $description
         *
         * @return DocumentaryAction
         */
        public function setDescription( $description)
        : DocumentaryAction {
            $this->description = $description;
            return $this;
        }

        /**
         * @return string
         */
        public function getName()
         {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return DocumentaryAction
         */
        public function setName( $name)
        : DocumentaryAction {
            $this->name = $name;
            return $this;
        }

        public function getCollectionData() {
            //$data = $this->toArray();
            $data = [
                'id'=> $this->id,
                'description'=> $this->getDescription(),
                'active'=>(bool)$this->active,
                'name'=>$this->getName()
            ];
            return $data;
        }
    }
