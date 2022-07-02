<?php

    namespace App\Http\Controllers;

    use App\Models\Tenant\Person;
    use Illuminate\Http\Request;
    use Modules\FullSuscription\Models\Tenant\FullSuscriptionUserDatum;

    /**
     * Tener en cuenta como base modules/Document/Traits/SearchTrait.php
     * Class SearchItemController
     *
     * @package App\Http\Controllers
     * @mixin Controller
     */
    class SearchCustomerController extends Controller
    {


        public static function getDocumentCustomers()
        {

            // Extraido de app/Http/Controllers/Tenant/DocumentController.php
            // @todo usar el archivo como busqueda general
            $customers = Person::with('addresses')
                ->whereType('customers')
                ->whereIsEnabled()
                ->orderBy('name')
                ->take(20)
                ->get()->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                });
            return $customers;
        }

        public static function searchCustomerByIdToDocument($id)
        {

            // Extraido de app/Http/Controllers/Tenant/DocumentController.php
            // @todo usar el archivo como busqueda general

            $customers = Person::with('addresses')
                ->whereType('customers')
                ->where('id', $id)
                ->get()
                ->transform(function ($row) {
                    /** @var  Person $row */
                    return $row->getCollectionData();

                });

            return compact('customers');
        }

        public static function getIdentityDocumentTypeIdToDocument($document_type_id, $operation_type_id)
        {

            // Extraido de app/Http/Controllers/Tenant/DocumentController.php
            // @todo usar el archivo como busqueda general

            // if($operation_type_id === '0101' || $operation_type_id === '1001') {

            if (in_array($operation_type_id, ['0101', '1001', '1004'])) {

                if ($document_type_id == '01') {
                    $identity_document_type_id = [6];
                } else {
                    if (config('tenant.document_type_03_filter')) {
                        $identity_document_type_id = [1];
                    } else {
                        $identity_document_type_id = [1, 4, 6, 7, 0];
                    }
                }
            } else {
                $identity_document_type_id = [1, 4, 6, 7, 0];
            }

            return $identity_document_type_id;
        }


        public static function searchCustomersToDocument(Request $request)
        {

            // Extraido de app/Http/Controllers/Tenant/DocumentController.php
            // @todo usar el archivo como busqueda general

            //tru de boletas en env esta en true filtra a los con dni   , false a todos
//        $operation_type_id_id = $this->getIdentityDocumentTypeId($request->operation_type_id);

            $customers = Person::
            where('number', 'like', "%{$request->input}%")
                ->orWhere('name', 'like', "%{$request->input}%")
                ->whereType('customers')->orderBy('name')
                ->whereIn('identity_document_type_id', $identity_document_type_id)
                ->whereIsEnabled()
                ->get()->transform(function ($row) {
                    /** @var  Person $row */
                    return $row->getCollectionData();
                });

            return compact('customers');
        }

        /**
         * @param \Illuminate\Http\Request|null $request
         * @param int|null                      $id
         *
         * @return \App\Http\Controllers\SearchCustomerController[]|\Illuminate\Database\Eloquent\Collection|static
         */
        public static function getSuscriptionCustomers(Request $request = null, ?int $id = 0)
        {
            $identity_document_type_id = null;
            $person = Person::query();
            $person->with('addresses');

            if( $request !== null){
                if (
                    $request->has('document_type_id') &&
                    $request->has('operation_type_id')
                ) {
                    $identity_document_type_id = self::getIdentityDocumentTypeId($request->document_type_id, $request->operation_type_id);
                }

                if (
                    $request->has('type') &&
                    !empty($request->has('type') )
                ) {
                    $typeCustomer = $request->type;
                    $person->where(function( \Illuminate\Database\Eloquent\Builder  $q) use ($typeCustomer){
                         if($typeCustomer == 'children'){
                             //Busca solo hijos, que tienen padre diferente a 0
                             $q->where('parent_id', '>', "0");
                         }elseif ($typeCustomer == 'parent'){
                             //Busca solo padres, que tienen padre igual a 0
                             $q->where('parent_id',  "0");
                         }
                    });

                }

                if (
                    $request->has('input')&&
                    !empty($request->has('input') )
                ) {
                    $input = $request->input;
                    $person->where(function( \Illuminate\Database\Eloquent\Builder  $q) use ($input) {
                        $q->where('number', 'like', "%$input%")
                            ->orWhere('name', 'like', "%$input%");
                    });

                    if ($identity_document_type_id != null) {
                        $person->whereIn('identity_document_type_id', $identity_document_type_id);
                    }
                }
            }else{
                // Buscará solo padres por defecto
                $person->where('parent_id',  "0");

            }
            /*
            dd([
                $person->toSql(),
            ]);
            */

            if ($id != 0) {
                $person->where('id', $id);
            }
                ;
            /** @var \Illuminate\Database\Eloquent\Collection|static[] $data */
            $data = $person->take(20)
            ->whereType('customers')
            ->whereIsEnabled()
            ->orderBy('name')
            ->get()
            ->transform(function ($row) {
                /** @var Person $row */
                return $row->getCollectionData(true,true);
            });

            return $data;
        }

        /**
         * @param \Illuminate\Http\Request|null $request
         * @param int|null                      $id
         * @param bool                      $onlyParent
         *
         * @return \App\Models\Tenant\Person|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
         */
        public static function getCustomersToSuscriptionList(Request $request = null, ?int $id = 0, $onlyParent = false){
            $person = Person::query();
            $person->with('addresses');
            $orderColum = 'name';
            $children = false;
            if($request->has('column') && !empty($request->column) && $request->column == 'discord_channel'){
                $value = $request->value;
                $request->request->remove('column');
                $orderColum = 'id';
                $temp = FullSuscriptionUserDatum::where('discord_channel','like',"%$value%")->select('person_id')->distinct()->get()->pluck('person_id');
                $person->whereIn('id',$temp);
            }
            if($request!= null){
                if($request->has('column') && !empty($request->column)){
                    $orderColum = $request->column;
                }
                if($orderColum == 'childrens'){
                    $children = true;
                    $orderColum = 'name';
                }
                if($request->has('column') && !empty($request->column)){
                    $search = $request->value;
                    $person->where($orderColum, 'like', "%$search%");
                }
            }
            if($id != 0){
                $person->where('id',$id);
            }
            if($children == true){
                $person = Person::whereIn('id',$person->get()->pluck('id'));
            }

            if($onlyParent == true && $id == 0){
                $person->where('parent_id',0);
            }
            return $person
                ->whereType('customers')
                ->whereIsEnabled()
                ->orderBy($orderColum);

        }

    }
