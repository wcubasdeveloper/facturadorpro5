<?php

    namespace Modules\Digemid\Models;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ItemUnitType;
    use App\Models\Tenant\ModelTenant;
    use Illuminate\Database\Eloquent\Builder;

    /**
 * Modules\Digemid\Models\CatDigemid
 *
 * @property-read Item $item
 * @method static Builder|CatDigemid newModelQuery()
 * @method static Builder|CatDigemid newQuery()
 * @method static Builder|CatDigemid query()
 * @mixin \Eloquent
 * @method static Builder|CatDigemid whereItem(\App\Models\Tenant\Item $item)
 */
    class CatDigemid extends ModelTenant {

        protected $table = 'cat_digemid';

        protected $fillable = [
            'item_id',
            'cod_digemid',
            'nom_prod',
            'concent',
            'nom_form_farm',
            'nom_form_farm_simplif',
            'presentac',
            'fracciones',
            'fec_vcto_reg_sanitario',
            'num_reg_san',
            'nom_titular',
            'active',
            'last_update',
            'prices',
            'max_prices',

        ];

        /**
         * @param array $prices
         *
         * @return $this
         */
        public function setArrayPrices($prices = []){
            $this->prices = implode('D', $prices);
            return $this;
        }

        /**
         * @return false|string[]
         */
        public function getArrayPrices(){
            return explode('D',$this->prices);
        }
        /**
         * @return string
         */
        public function getPrices()
        : string {
            return $this->prices;
        }

        /**
         * @param string $prices
         *
         * @return CatDigemid
         */
        public function setPrices(string $prices)
        : CatDigemid {
            $this->prices = $prices;
            return $this;
        }

        /**
         * @return int
         */
        public function getMaxPrices()
        : int {
            return $this->max_prices;
        }

        /**
         * @param int $max_prices
         *
         * @return CatDigemid
         */
        public function setMaxPrices(int $max_prices)
        : CatDigemid {
            $this->max_prices = $max_prices;
            return $this;
        }


        /**
         * @return int
         */
        public function getItemId()
        : int {
            return $this->item_id;
        }

        /**
         * @param int $item_id
         *
         * @return CatDigemid
         */
        public function setItemId(int $item_id)
        : CatDigemid {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getCodDigemid()
        : string {
            return $this->cod_digemid;
        }

        /**
         * @param string $cod_digemid
         *
         * @return CatDigemid
         */
        public function setCodDigemid(string $cod_digemid)
        : CatDigemid {
            $this->cod_digemid = $cod_digemid;
            return $this;
        }

        /**
         * @return string
         */
        public function getNomProd()
        : string {
            return $this->nom_prod;
        }

        /**
         * @param string $nom_prod
         *
         * @return CatDigemid
         */
        public function setNomProd(string $nom_prod)
        : CatDigemid {
            $this->nom_prod = $nom_prod;
            return $this;
        }

        /**
         * @return string
         */
        public function getConcent()
        : string {
            return $this->concent;
        }

        /**
         * @param string $concent
         *
         * @return CatDigemid
         */
        public function setConcent(string $concent)
        : CatDigemid {
            $this->concent = $concent;
            return $this;
        }

        /**
         * @return string
         */
        public function getNomFormFarm()
        : string {
            return $this->nom_form_farm;
        }

        /**
         * @param string $nom_form_farm
         *
         * @return CatDigemid
         */
        public function setNomFormFarm(string $nom_form_farm)
        : CatDigemid {
            $this->nom_form_farm = $nom_form_farm;
            return $this;
        }

        /**
         * @return string
         */
        public function getNomFormFarmSimplif()
        : string {
            return $this->nom_form_farm_simplif;
        }

        /**
         * @param string $nom_form_farm_simplif
         *
         * @return CatDigemid
         */
        public function setNomFormFarmSimplif(string $nom_form_farm_simplif)
        : CatDigemid {
            $this->nom_form_farm_simplif = $nom_form_farm_simplif;
            return $this;
        }

        /**
         * @return string
         */
        public function getPresentac()
        : string {
            return $this->presentac;
        }

        /**
         * @param string $presentac
         *
         * @return CatDigemid
         */
        public function setPresentac(string $presentac)
        : CatDigemid {
            $this->presentac = $presentac;
            return $this;
        }

        /**
         * @return string
         */
        public function getFracciones()
        : string {
            return $this->fracciones;
        }

        /**
         * @param string $fracciones
         *
         * @return CatDigemid
         */
        public function setFracciones(string $fracciones)
        : CatDigemid {
            $this->fracciones = $fracciones;
            return $this;
        }

        /**
         * @return string
         */
        public function getFecVctoRegSanitario()
        : string {
            return $this->fec_vcto_reg_sanitario;
        }

        /**
         * @param string $fec_vcto_reg_sanitario
         *
         * @return CatDigemid
         */
        public function setFecVctoRegSanitario(string $fec_vcto_reg_sanitario)
        : CatDigemid {
            $this->fec_vcto_reg_sanitario = $fec_vcto_reg_sanitario;
            return $this;
        }

        /**
         * @return string
         */
        public function getNumRegSan()
        : string {
            return $this->num_reg_san;
        }

        /**
         * @param string $num_reg_san
         *
         * @return CatDigemid
         */
        public function setNumRegSan(string $num_reg_san)
        : CatDigemid {
            $this->num_reg_san = $num_reg_san;
            return $this;
        }

        /**
         * @return string
         */
        public function getNomTitular()
        : string {
            return $this->nom_titular;
        }

        /**
         * @param string $nom_titular
         *
         * @return CatDigemid
         */
        public function setNomTitular(string $nom_titular)
        : CatDigemid {
            $this->nom_titular = $nom_titular;
            return $this;
        }

        /**
         * @return bool
         */
        public function isActive()
        : bool {
            return $this->active;
        }

        /**
         * @param bool $active
         *
         * @return CatDigemid
         */
        public function setActive(bool $active)
        : CatDigemid {
            $this->active = $active;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getLastUpdate() {
            return $this->last_update;
        }

        /**
         * @param mixed $last_update
         *
         * @return CatDigemid
         */
        public function setLastUpdate($last_update) {
            $this->last_update = $last_update;
            return $this;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function item() {
            return $this->belongsTo(Item::class);
        }


        /**
         * @return \App\Models\Tenant\ItemUnitType|\Illuminate\Database\Query\Builder
         */
        public function item_unit_types()
        {
            return ItemUnitType::where('item_id',$this->item_id);
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param \App\Models\Tenant\Item               $item
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereItem(Builder $query, Item $item){
            return $query->where('item_id',$item->id);
        }

        /**
         * @return int
         */
        public static function setInactiveMassive(){
            $model = new self();
            $table = $model->getTable();
            return \DB::connection('tenant')->table($table)->where('active',1)->update(['active'=>0]);

        }

        /**
         * @return $this
         */
        public function updatePrices(){
            $all_prices = [];
            $prices = $this->item_unit_types()->select(
                'price1',
                'price2',
                'price3',
                'price_default'
            )->get();
            /** @var ItemUnitType $price */
            foreach($prices as $price){
                $def = $price->price_default;
                $t = "price".$def;
                $all_prices[] = number_format((float)$price->{$t},2,',','');
            }
            $this->setMaxPrices(count($all_prices))->setArrayPrices($all_prices);
            return $this;

        }
        public function toggleActive(){
            if($this->active == 1) {
                $this->active = 0;
            }else{
                $this->active = 1;
                $this->updatePrices();
            }
            return $this;
        }
    }
