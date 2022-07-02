import state from './state'

function readStorageData(variable, json = false, defaultv = undefined) {
    let w = localStorage.getItem(variable)
    if (w === 'undefined' && defaultv != undefined) {
        w =  defaultv
    }
    if (w === 'undefined') {
        w = null;
    }
    if (json === true) {
        w = JSON.parse(w)
    }
    return w;
}

export default {
    loadConfiguration(store) {
        state.config = readStorageData('config', true,state.config);
        state.customers = readStorageData('customers', true,state.customers);
        state.userType = readStorageData('userType', false,state.userType);
        state.company = readStorageData('company', true,state.company);
        state.establishment = readStorageData('establishment', true,state.establishment);
        if (state.deb === undefined) state.deb = {};
        if (state.colors === undefined) state.colors = [];
        if (state.CatItemSize === undefined) state.CatItemSize = [];
        if (state.CatItemMoldProperty === undefined) state.CatItemMoldProperty = [];
        if (state.CatItemUnitBusiness === undefined) state.CatItemUnitBusiness = [];
        if (state.CatItemStatus === undefined) state.CatItemStatus = [];
        if (state.CatItemProductFamily === undefined) state.CatItemProductFamily = [];
        if (state.CatItemPackageMeasurement === undefined) state.CatItemPackageMeasurement = [];
        if (state.CatItemUnitsPerPackage === undefined) state.CatItemUnitsPerPackage = [];
        if (state.CatItemMoldCavity === undefined) state.CatItemMoldCavity = [];
        if (state.extra_colors === undefined) state.extra_colors = [];
        if (state.extra_CatItemSize === undefined) state.extra_CatItemSize = [];
        if (state.extra_CatItemUnitsPerPackage === undefined) state.extra_CatItemUnitsPerPackage = [];
        if (state.extra_CatItemMoldProperty === undefined) state.extra_CatItemMoldProperty = [];
        if (state.extra_CatItemUnitBusiness === undefined) state.extra_CatItemUnitBusiness = [];
        if (state.extra_CatItemStatus === undefined) state.extra_CatItemStatus = [];
        if (state.extra_CatItemPackageMeasurement === undefined) state.extra_CatItemPackageMeasurement = [];
        if (state.extra_CatItemMoldCavity === undefined) state.extra_CatItemMoldCavity = [];
        if (state.extra_CatItemProductFamily === undefined) state.extra_CatItemProductFamily = [];
        if (state.loading_submit === undefined) state.loading_submit = false;
        if (state.payment_method_types === undefined) state.payment_method_types = [];
        if (state.form_pos === undefined) state.form_pos = {};
        if (state.currency_types === undefined) state.currency_types = [];
        if (state.items === undefined) state.items = [];
        if (state.exchange_rate_sale === undefined) state.exchange_rate_sale = 1;
        if (state.exchange_rate === undefined) state.exchange_rate = 1;
        if (state.item === undefined) state.item = {};
        if (state.document_types_guide === undefined) state.document_types_guide = {};
        if (state.form_data === undefined) state.form_data = {};
        if (state.resource === undefined) state.resource = '';
        if (state.periods === undefined) state.periods = [];
        if (state.affectation_igv_types === undefined) state.affectation_igv_types = [];
        if (state.table_data === undefined) state.table_data = [];

        if (state.unit_types === undefined) state.unit_types = [];
        if (state.item_search_extra_parameters === undefined) state.item_search_extra_parameters = {};
        if (state.person === undefined) state.person = {};
        if (state.customers === undefined) state.customers = [];

        if (state.countries === undefined) state.countries = [];
        if (state.all_departments === undefined) state.all_departments = [];
        if (state.all_provinces === undefined) state.all_provinces = [];
        if (state.all_districts === undefined) state.all_districts = [];
        if (state.identity_document_types === undefined) state.identity_document_types = [];
        if (state.locations === undefined) state.locations = [];
        if (state.person_types === undefined) state.person_types = [];
        if (state.all_series === undefined) state.all_series = [];
        if (state.series === undefined) state.series = [];
        if (state.payment_destinations === undefined) state.payment_destinations = [];
        if (state.statusDocumentary === undefined) state.statusDocumentary = [];


        if (state.parent_customer === undefined) state.parent_customer = {};
        if (state.children_customer === undefined) state.children_customer = {};

        if (state.customer_addresses === undefined) state.customer_addresses = [];
        if (state.parentPerson === undefined) state.parentPerson = {};

        if (state.mi_tienda_pe === undefined) {
            state.mi_tienda_pe = {
                establishment_id: null,
                series_order_note_id: null,
                autogenerate: false,
                series_document_id: null,
                user_id: null,
                payment_destination_id: null,
                currency_type_id: 'PEN',
            };
        }


        // Previenete limite de almacen exedido
        /*
        5MB per app per browser. According to the HTML5 spec, this limit can be increased by the user when needed;
         however, only a few browsers support this
         */
        // alternativa posible sessionStorage
        /*
        localStorage.removeItem('customers');
        localStorage.removeItem('offices');
        localStorage.removeItem('files');
        localStorage.removeItem('processes');
        localStorage.removeItem('actions');
        localStorage.removeItem('workers');
        localStorage.removeItem('warehouses');
        localStorage.removeItem('all_items');
        */
    },
    EmitEvent(event, payload) {
        //  this.$eventHub.$emit(event,payload)

    },
    clearFormData() {
        state.form_data = {};
    },
    clearExtraInfoItem() {
        state.extra_colors = [];
        state.extra_CatItemSize = [];
        state.extra_CatItemUnitsPerPackage = [];
        state.extra_CatItemMoldProperty = [];
        state.extra_CatItemUnitBusiness = [];
        state.extra_CatItemStatus = [];
        state.extra_CatItemPackageMeasurement = [];
        state.extra_CatItemMoldCavity = [];
        state.extra_CatItemProductFamily = [];
    },
    loadWarehouses(store) {
        if (state.warehouses === undefined) state.warehouses = [];
        // state.warehouses = readStorageData('warehouses', true)
    },
    loadOffices(store) {
        if (state.offices === undefined) state.offices = [];
    },
    loadStatusDocumentary(store) {
        if (state.statusDocumentary === undefined) state.statusDocumentary = [];
    },
    loadCustomers(store) {
        if (state.customers === undefined) state.customers = [];
        // state.customers = readStorageData('customers', true)
    },
    loadCurrencys(store) {
        if (state.currencys === undefined) state.currencys = [];
        // state.customers = readStorageData('customers', true)
    },
    loadActions(store) {
        if (state.actions === undefined) state.actions = [];
        // state.actions = readStorageData('actions', true)
    },
    loadProcesses(store) {
        if (state.processes === undefined) state.processes = [];
        // state.processes = readStorageData('processes', true)
    },
    loadFiles(store) {
        if (state.files === undefined) state.files = [];
        // state.files = readStorageData('files', true)
    },
    loadCurrencyTypes(store) {
        if (state.currency_types === undefined) state.currency_types = [];
        // state.files = readStorageData('files', true)
    },
    loadDocumentTypes(store) {
        state.documentTypes = readStorageData('documentTypes', true)
    },
    loadWorkers(store) {
        if (state.workers === undefined) state.workers = [];
        // state.workers = readStorageData('workers', true)
    },
    loadPos(store) {
        state.form_pos = readStorageData('form_pos', true);
        if (state.form_pos === undefined) state.form_pos = {};
    },
    loadAllItems(store) {
        if (state.all_items === undefined) state.all_items = [];
        // state.all_items = getUniqueArray(readStorageData('all_items', true), ['id'])
    },
    loadItem(store) {
        if (state.item === undefined) state.item = {};

    },
    loadItems(store) {
        if (state.items === undefined) state.items = [];
        // state.items = getUniqueArray(readStorageData('items', true), ['id'])
    },
    loadExchangeRate(store) {
        if (state.exchange_rate === undefined) state.exchange_rate = 1;
        // state.all_items = getUniqueArray(readStorageData('all_items', true), ['id'])
    },
    loadDocumentTypesGuide(store) {
        if (state.document_types_guide === undefined) state.document_types_guide = [];
    },
    loadExchangeRateSale(store) {
        if (state.exchange_rate_sale === undefined) state.exchange_rate_sale = 1;
    },
    loadHasGlobalIgv(store) {
        if (state.hasGlobalIgv === undefined) state.hasGlobalIgv = false;
    },
    loadCompany(store) {
        let t = readStorageData('company', true)
        if (t !== null) {
            state.company = t
        } else {
            state.company = {
                logo: null,
                name: '',
            };
        }
    },
    loadEstablishment(store) {
        let t = readStorageData('establishment', true)
        if (t !== null) {
            state.establishment = t
        } else {
            state.establishment = {
                address: '-',
                district: {description: ''},
                province: {description: ''},
                department: {description: ''},
                country: {description: ''},
                telephone: '-',
                email: null,
            };
        }
    },
}
