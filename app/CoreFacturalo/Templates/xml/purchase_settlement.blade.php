@php
    $establishment = $document->establishment;
    $supplier = $document->supplier;
    $operation_data = $document->operation_data;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!} 
<SelfBilledInvoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:SelfBilledInvoice-2"
	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
	xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
	<ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
	<cbc:UBLVersionID>2.1</cbc:UBLVersionID>
	<cbc:CustomizationID>2.0</cbc:CustomizationID>
	<cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    <cbc:InvoiceTypeCode listID="{{ $document->operation_type_id }}">{{ $document->document_type_id }}</cbc:InvoiceTypeCode>
    @foreach($document->legends as $leg)
    <cbc:Note languageLocaleID="{{ $leg->code }}"><![CDATA[{{ $leg->value }}]]></cbc:Note>
    @endforeach
	<cbc:DocumentCurrencyCode>{{ $document->currency_type_id }}</cbc:DocumentCurrencyCode>
	<cac:Signature>
        <cbc:ID>{{ config('configuration.signature_uri') }}</cbc:ID>
        <cbc:Note>{{ config('configuration.signature_note') }}</cbc:Note>
		<cac:SignatoryParty>
			<cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
			</cac:PartyIdentification>
			<cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
			</cac:PartyName>
		</cac:SignatoryParty>
		<cac:DigitalSignatureAttachment>
			<cac:ExternalReference>
                <cbc:URI>#{{ config('configuration.signature_uri') }}</cbc:URI>
			</cac:ExternalReference>
		</cac:DigitalSignatureAttachment>
	</cac:Signature>
	<cac:AccountingCustomerParty>
		<cac:Party>
			<cac:PartyIdentification>
				<cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
			</cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName> 
			<cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
				<cac:RegistrationAddress>
                    <cbc:ID>{{ $establishment->district_id }}</cbc:ID>
                    @if($establishment->urbanization)
                        <cbc:CitySubdivisionName>{{ $establishment->urbanization }}</cbc:CitySubdivisionName>
                    @endif
                    <cbc:CityName>{{ $establishment->province->description }}</cbc:CityName>
                    <cbc:CountrySubentity>{{ $establishment->department->description }}</cbc:CountrySubentity>
                    <cbc:District>{{ $establishment->district->description }}</cbc:District>
					<cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $establishment->address }}]]></cbc:Line>
					</cac:AddressLine>
					<cac:Country>
                        <cbc:IdentificationCode>{{ $establishment->country_id }}</cbc:IdentificationCode>
					</cac:Country>
				</cac:RegistrationAddress>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:AccountingCustomerParty>
	<cac:AccountingSupplierParty>
		<cac:Party>
			<cac:PartyIdentification>
                <cbc:ID schemeID="{{ $supplier->identity_document_type_id }}">{{ $supplier->number }}</cbc:ID>
			</cac:PartyIdentification>
			<cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $supplier->name }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:ID>{{ $supplier->district_id }}</cbc:ID>
                    <cbc:AddressTypeCode>{{ $supplier->address_type_id }}</cbc:AddressTypeCode>
                    <cbc:CityName>{{ $supplier->province->description }}</cbc:CityName>
                    <cbc:CountrySubentity>{{ $supplier->department->description }}</cbc:CountrySubentity>
                    <cbc:District>{{ $supplier->district->description }}</cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $supplier->address }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{ $supplier->country_id }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
			</cac:PartyLegalEntity>
		</cac:Party>
    </cac:AccountingSupplierParty>
    <cac:DeliveryTerms>
		<cac:DeliveryLocation>
            <cbc:LocationTypeCode>{{ $operation_data->address_type_id }}</cbc:LocationTypeCode>
			<cac:Address>
                <cbc:ID>{{ $operation_data->district_id }}</cbc:ID>
                <cbc:CityName>{{ $operation_data->province->description }}</cbc:CityName>
                <cbc:CountrySubentity>{{ $operation_data->department->description }}</cbc:CountrySubentity>
                <cbc:District>{{ $operation_data->district->description }}</cbc:District>
				<cac:AddressLine>
                    <cbc:Line><![CDATA[{{ $operation_data->address }}]]></cbc:Line>
				</cac:AddressLine>
				<cac:Country>
                    <cbc:IdentificationCode>{{ $operation_data->country_id }}</cbc:IdentificationCode>
				</cac:Country>
			</cac:Address>
		</cac:DeliveryLocation>
    </cac:DeliveryTerms>
	<cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_taxes }}</cbc:TaxAmount>
        @if($document->total_taxed > 0)
		<cac:TaxSubtotal>
			<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_taxed }}</cbc:TaxableAmount>
			<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_igv }}</cbc:TaxAmount>
			<cac:TaxCategory>
				<cac:TaxScheme>
					<cbc:ID>1000</cbc:ID>
					<cbc:Name>IGV</cbc:Name>
					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_exonerated > 0)
		<cac:TaxSubtotal>
			<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_exonerated }}</cbc:TaxableAmount>
			<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">0</cbc:TaxAmount>
			<cac:TaxCategory>
				<cac:TaxScheme>
					<cbc:ID>9997</cbc:ID>
					<cbc:Name>EXO</cbc:Name>
					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_unaffected > 0)
		<cac:TaxSubtotal>
			<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_unaffected }}</cbc:TaxableAmount>
			<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">0</cbc:TaxAmount>
			<cac:TaxCategory>
				<cac:TaxScheme>
					<cbc:ID>9998</cbc:ID>
					<cbc:Name>INA</cbc:Name>
					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
    </cac:TaxTotal>
	<cac:LegalMonetaryTotal>
		<cbc:LineExtensionAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_value }}</cbc:LineExtensionAmount>
		<cbc:TaxInclusiveAmount currencyID="{{ $document->currency_type_id }}">{{ $document->subtotal }}</cbc:TaxInclusiveAmount>
		<cbc:PayableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total }}</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    @foreach($document->items as $row)
        <cac:InvoiceLine>
            <cbc:ID>{{ $loop->iteration }}</cbc:ID>
            <cbc:InvoicedQuantity unitCode="{{ $row->item->unit_type_id }}">{{ $row->quantity }}</cbc:InvoicedQuantity>
            <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_id }}">{{ $row->total_value }}</cbc:LineExtensionAmount>
            <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                    <cbc:PriceAmount currencyID="{{ $document->currency_type_id }}">{{ $row->unit_price }}</cbc:PriceAmount>
                    <cbc:PriceTypeCode>{{ $row->price_type_id }}</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
            </cac:PricingReference>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $row->total_taxes }}</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $row->total_base_igv }}</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $row->total_igv }}</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:Percent>{{ $row->percentage_igv }}</cbc:Percent>
                        <cbc:TaxExemptionReasonCode>{{ $row->affectation_igv_type_id }}</cbc:TaxExemptionReasonCode>
                        @php($affectation = \App\CoreFacturalo\Templates\FunctionTribute::getByAffectation($row->affectation_igv_type_id))
                        <cac:TaxScheme>
                            <cbc:ID>{{ $affectation['id'] }}</cbc:ID>
                            <cbc:Name>{{ $affectation['name'] }}</cbc:Name>
                            <cbc:TaxTypeCode>{{ $affectation['code'] }}</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </cac:TaxTotal>
            <cac:Item>
                <cbc:Description><![CDATA[{{ $row->item->description }}]]></cbc:Description>
                @if($row->item->internal_id)
                    <cac:SellersItemIdentification>
                        <cbc:ID>{{ $row->item->internal_id }}</cbc:ID>
                    </cac:SellersItemIdentification>
                @endif 
                @if($row->item->item_code)
                    <cac:CommodityClassification>
                        <cbc:ItemClassificationCode>{{ $row->item->item_code }}</cbc:ItemClassificationCode>
                    </cac:CommodityClassification>
                @endif 
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_id }}">{{ $row->unit_value }}</cbc:PriceAmount>
            </cac:Price>
        </cac:InvoiceLine>
    @endforeach
</SelfBilledInvoice>
