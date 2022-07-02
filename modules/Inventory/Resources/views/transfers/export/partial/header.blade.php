<?php

    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\User;
    use Modules\Inventory\Models\Inventory;
    use Modules\Inventory\Models\Warehouse;
    use Illuminate\Support\Carbon;
    use Illuminate\Database\Eloquent\Collection;

    $motivo = !empty($data['motivo']) ? $data['motivo'] : null;
    /** @var Carbon $created_at */
    /** @var Warehouse $warehouse_to */
    /** @var Warehouse $warehouse_from */
    /** @var User $user */
    /** @var Collection|Inventory[] $inventories */

    $created_at = !empty($data['created_at']) ? $data['created_at'] : Carbon::now();
    $serie = !empty($data['serie']) ? $data['serie'] : 'NT';
    $number = !empty($data['number']) ? $data['number'] : 0;
    $document_type = !empty($data['document_type']) ? $data['document_type'] : 'NOTA DE TRASLADO';


    $warehouse_from = !empty($data['warehouse_from']) ? $data['warehouse_from'] : new Warehouse();
    $warehouse_to = !empty($data['warehouse_to']) ? $data['warehouse_to'] : new Warehouse();
    $user = !empty($data['user']) ? $data['user'] : new User();
    $company = !empty($data['company']) ? $data['company'] : new Company();

    $pdf = $pdf ?? false;
?>
<div>
    <table class="no-border">
        <tr>
            <td
                colspan="4"
                align="center"
                style="max-width: 300px; height: auto;"
            >
            @if($pdf == true)
                <!-- Logo aqui -->
                    @if(!empty($company->logo))
                        <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}"
                             alt="{{$company->name}}"
                             class="company_logo_ticket contain"
                             style="max-width: 300px; height: auto;"
                             >
                    @endif
                @else
                    <h3>
                        {{$company->name}}
                    </h3>
                @endif

            </td>

            <td
                colspan="4"
                align="left"
                style="max-width: 300px; height: auto;"
            >
                <table style="border:2px solid black; max-width: 150px;" >
                    <tr>
                        <td
                            align="center"
                        >
                            <h3 class="font-bold">{{ 'R.U.C. '.$company->number }}</h3>
                            <h3 class="text-center font-bold">{{ $document_type }}</h3>
                            <h3 class="text-center font-bold">{{ $serie }} - {{ $number }}</h3>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<div>
    <table  class="no-border">
        <tr>
            <td>
                <table  class="no-border">
                    <tr>
                        <td
                            {{-- colspan="2" --}}
                        >
                            <strong>ALMACÉN INICIAL</strong>
                        </td>
                        <td
                            {{-- colspan="2" --}}
                        >
                            {{ $warehouse_from->getDescription() }}
                        </td>
                        <td>
                            <strong>FECHA DOCUMENTO:</strong>
                        </td>
                        <td>
                            {{$created_at->format('d/m/Y')}}
                        </td>
                    </tr>
                    <tr>
                        <td
                            {{-- colspan="2" --}}
                        >
                            <strong>ALMACÉN DESTINO</strong>
                        </td>
                        <td
                            {{-- colspan="2" --}}
                        >
                            {{ $warehouse_to->getDescription() }}
                        </td>
                        <td>
                            <strong>RESPONSABLE:</strong>
                        </td>
                        <td>
                            {{ $user->getName() }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            {{-- colspan="2" --}}
                        >
                            <strong>MOTIVO</strong>
                        </td>
                        <td
                            {{-- colspan="2" --}}
                        >
                            {{ $motivo }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
