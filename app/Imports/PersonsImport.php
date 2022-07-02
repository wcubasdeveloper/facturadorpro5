<?php

    namespace App\Imports;

    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonType;
    use Exception;
    use Illuminate\Support\Collection;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;

    class PersonsImport implements ToCollection
    {
        use Importable;

        protected $data;

        public function collection(Collection $rows)
        {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row) {
                $type = request()->input('type');
                $identity_document_type_id = $row[0];
                $number = $row[1];
                $name = $row[2];
                $trade_name = $row[3];
                $country_id = ($row[4]) ?: 'PE';
                $location_id = $row[5];
                $department_id = null;
                $province_id = null;
                if ($location_id) {
                    $department_id = substr($location_id, 0, 2);
                    $province_id = substr($location_id, 0, 4);
                }
                $address = $row[6];
                $email = $row[7];
                $telephone = $row[8];
                $person_type = isset($row[9]) ? $row[9] : null;
                $person_type_model = null;
                $person_type_id = null;
                if (!empty($person_type)) {
                    $person_type_model = PersonType::where('description', 'like', '%' . $person_type . '%')->first();
                    if ($person_type_model !== null) {
                        $person_type_id = $person_type_model->id;
                    }
                }
                $person = Person::where('type', $type)
                    ->where('identity_document_type_id', $identity_document_type_id)
                    ->where('number', $number)
                    ->first();
                if (!$person) {
                    Person::create([
                        'type' => $type,
                        'identity_document_type_id' => $identity_document_type_id,
                        'number' => $number,
                        'name' => $name,
                        'person_type_id' => $person_type_id,
                        'trade_name' => $trade_name,
                        'country_id' => $country_id,
                        'department_id' => $department_id,
                        'province_id' => $province_id,
                        'district_id' => $location_id,
                        'address' => $address,
                        'email' => $email,
                        'telephone' => $telephone,
                    ]);
                    $registered += 1;
                }
            }
            $this->data = compact('total', 'registered');

        }

        public function getData()
        {
            return $this->data;
        }
    }
