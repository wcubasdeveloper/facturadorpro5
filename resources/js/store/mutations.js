function writeLocal(variable, data) {
    if (data === undefined || data === 'undefined') {
        localStorage.removeItem(variable)
    } else {
        localStorage.setItem(variable, data)
    }
}

export default {
    setConfiguration(state, config) {
        if (config !== undefined && config.company !== undefined) {
            state.company = config.company
            writeLocal('company', JSON.stringify(config.company))
        }
        if (config !== undefined && config.establishment !== undefined) {
            state.establishment = config.establishment
            writeLocal('establishment', JSON.stringify(config.establishment))
        }
        state.config = config
        if (config === undefined) {
            state.config = JSON.parse(localStorage.getItem('config'))
            config = state.config;
        }
        writeLocal('config', JSON.stringify(config))
    },
    setEstablishment(state, establishment) {
        writeLocal('establishment', JSON.stringify(establishment))
        return establishment;
    },
    setCompany(state, company) {
        writeLocal('company', JSON.stringify(company))
        return company;
    },
    setCustomers(state, customers) {
        state.customers = (customers === undefined) ?[]:customers
    },
    setPaymentDestinations(state, payment_destinations) {
        state.payment_destinations = (payment_destinations === undefined) ?[]:payment_destinations
    },
    setCustomer(state, customer) {
        state.customer = (customer === undefined) ? {} :customer
    },
    setParentCustomer(state, parent_customer) {
        state.parent_customer = (parent_customer === undefined) ? {} :parent_customer
    },
    setChildrenCustomer(state, children_customer) {
        state.children_customer = (children_customer === undefined) ? {} :children_customer
    },
    setAllCustomers(state, all_customers) {
        state.all_customers = (all_customers === undefined) ?[]:all_customers
    },
    setPlans(state, plans) {
        state.plans = (plans === undefined) ?[]:plans
    },
    setCustomersAddresses(state, customer_addresses) {
        state.customer_addresses = (customer_addresses === undefined) ?[]:customer_addresses
    },
    setExchangeRate(state, exchange_rate) {
        state.exchange_rate = exchange_rate
    },
    setExchangeRateSale(state, exchange_rate_sale) {
        state.exchange_rate_sale = exchange_rate_sale
    },
    setCurrencys(state, currencys) {
        state.currencys = currencys
    },
    setOffices(state, offices) {
        state.offices = offices
    },
    setDocumentTypes(state, documentTypes) {
        state.documentTypes = documentTypes
        writeLocal('documentTypes', JSON.stringify(documentTypes))
    },
    setFiles(state, files) {
        state.files = files
    },
    setProcesses(state, processes) {
        state.processes = processes
    },
    setActions(state, actions) {
        state.actions = actions
    },

    setFile(state, file) {
        state.file = file
    },

    canShowColorDialog(state, showColorDialog) {
        state.showColorDialog = showColorDialog
    },
    setColor(state, color) {
        state.color = color
    },
    setLoadingSubmit(state, loading_submit) {
        if (loading_submit === undefined) loading_submit = false;
        state.loading_submit = loading_submit
    },
    setWorkers(state, workers) {
        state.workers = workers
    },
    setOffice(state, office) {
        state.office = office
    },
    setColors(state, colors) {
        if (colors === undefined) colors = [];
        state.colors = colors
    },
    setCatItemStatus(state, CatItemStatus) {
        if (CatItemStatus === undefined) CatItemStatus = [];
        state.CatItemStatus = CatItemStatus
    },
    setCatItemUnitsPerPackage(state, CatItemUnitsPerPackage) {
        if (CatItemUnitsPerPackage === undefined) CatItemUnitsPerPackage = [];
        state.CatItemUnitsPerPackage = CatItemUnitsPerPackage
    },
    setCatItemMoldCavity(state, CatItemMoldCavity) {
        if (CatItemMoldCavity === undefined) CatItemMoldCavity = [];
        state.CatItemMoldCavity = CatItemMoldCavity
    },
    setCatItemSize(state, CatItemSize) {
        if (CatItemSize === undefined) CatItemSize = [];
        state.CatItemSize = CatItemSize
    },
    setCatItemMoldProperty(state, CatItemMoldProperty) {
        if (CatItemMoldProperty === undefined) CatItemMoldProperty = [];
        state.CatItemMoldProperty = CatItemMoldProperty
    },
    setCatItemUnitBusiness(state, CatItemUnitBusiness) {
        if (CatItemUnitBusiness === undefined) CatItemUnitBusiness = [];
        state.CatItemUnitBusiness = CatItemUnitBusiness
    },
    setCatItemPackageMeasurement(state, CatItemPackageMeasurement) {
        if (CatItemPackageMeasurement === undefined) CatItemPackageMeasurement = [];
        state.CatItemPackageMeasurement = CatItemPackageMeasurement
    },

    setCatItemProductFamily(state, CatItemProductFamily) {
        if (CatItemProductFamily === undefined) CatItemProductFamily = [];
        state.CatItemProductFamily = CatItemProductFamily
    },
    setDeb(state, debug) {
        if (debug === undefined) debug = {};
        state.deb = debug
    },
    setFormData(state, form_data) {
        if (form_data === undefined) form_data = {};
        state.form_data = form_data
    },
    setPeriods(state, periods) {
        if (periods === undefined) periods = [];
        state.periods = periods
    },
    setFilter(state, filter) {
        if (filter === undefined) filter = [];
        state.filter = filter
    },
    setFromPos(state, form_pos) {
        if (form_pos === undefined) form_pos = {};
        writeLocal('form_pos', JSON.stringify(form_pos))
        state.form_pos = form_pos
    },
    setPaymentMethodTypes(state, payment_method_types) {
        state.payment_method_types = (payment_method_types === undefined) ?[]:payment_method_types
    },
    setDocumentTypesGuide(state, document_types_guide) {
        if (document_types_guide === undefined) document_types_guide = {};
        state.document_types_guide = document_types_guide
    },

    setExtraColors(state, extra_colors) {
        if (extra_colors === undefined) extra_colors = [];
        state.extra_colors = extra_colors
    },
    setExtraCatItemUnitsPerPackage(state, extra_CatItemUnitsPerPackage) {
        if (extra_CatItemUnitsPerPackage === undefined) extra_CatItemUnitsPerPackage = [];
        state.extra_CatItemUnitsPerPackage = extra_CatItemUnitsPerPackage
    },
    setExtraCatItemSize(state, extra_CatItemSize) {
        if (extra_CatItemSize === undefined) extra_CatItemSize = [];
        state.extra_CatItemSize = extra_CatItemSize
    },
    setExtraCatItemMoldProperty(state, extra_CatItemMoldProperty) {
        if (extra_CatItemMoldProperty === undefined) extra_CatItemMoldProperty = [];
        state.extra_CatItemMoldProperty = extra_CatItemMoldProperty
    },
    setExtraCatItemUnitBusiness(state, extra_CatItemUnitBusiness) {
        if (extra_CatItemUnitBusiness === undefined) extra_CatItemUnitBusiness = [];
        state.extra_CatItemUnitBusiness = extra_CatItemUnitBusiness
    },
    setExtraCatItemStatus(state, extra_CatItemStatus) {
        if (extra_CatItemStatus === undefined) extra_CatItemStatus = [];
        state.extra_CatItemStatus = extra_CatItemStatus
    },
    setExtraCatItemPackageMeasurement(state, extra_CatItemPackageMeasurement) {
        if (extra_CatItemPackageMeasurement === undefined) extra_CatItemPackageMeasurement = [];
        state.extra_CatItemPackageMeasurement = extra_CatItemPackageMeasurement
    },
    setExtraCatItemMoldCavity(state, extra_CatItemMoldCavity) {
        if (extra_CatItemMoldCavity === undefined) extra_CatItemMoldCavity = [];
        state.extra_CatItemMoldCavity = extra_CatItemMoldCavity
    },
    setExtraCatItemProductFamily(state, extra_CatItemProductFamily) {
        if (extra_CatItemProductFamily === undefined) extra_CatItemProductFamily = [];
        state.extra_CatItemProductFamily = extra_CatItemProductFamily
    },


    setRecords(state, records) {
        if (records === undefined) records = [];
        state.records = records
    },

    setItems(state, items) {
        if (items === undefined) items = [];
        state.items = items
    },
    setItem(state, item) {
        state.item = (item === undefined)?{}:item;
    },
    setMiTiendaPe(state, mi_tienda_pe) {
        state.mi_tienda_pe = (mi_tienda_pe === undefined)?{ establishment_id:null,
            series_order_note_id:null,
            series_document_id:null,
            user_id:null,
            payment_destination_id:null,
            currency_type_id:null,}:mi_tienda_pe;
    },
    setTableData(state, table_data) {
        state.table_data = (table_data === undefined) ? [] : table_data;
    },
    setPagination(state, pagination) {
        if (pagination === undefined) pagination = {
            current_page: 1,
            total: 0,
            per_page: 25,
        };
        state.pagination = pagination
    },
    setTypeUser(state, userType) {
        state.userType = userType
        writeLocal('userType', userType)
    },
    setWarehouses(state, warehouses) {
        state.warehouses = warehouses
    },
    setResource(state, resource) {
        state.resource = resource
    },
    setItemSearchExtraParameters(state, item_search_extra_parameters) {
        if (item_search_extra_parameters === undefined) item_search_extra_parameters = {};
        state.item_search_extra_parameters = item_search_extra_parameters
    },
    setPerson(state, person) {
        if (person === undefined) person = {};
        state.person = person
    },
    setParentPerson(state, parentPerson) {
        if (parentPerson === undefined) parentPerson = {};
        state.person = parentPerson
    },
    sethasGlobalIgv(state, hasGlobalIgv) {
        if (hasGlobalIgv === undefined) hasGlobalIgv = false;
        state.hasGlobalIgv = hasGlobalIgv
    },
    setEnabledGlobalIgvToPurchase(state, enabled_global_igv_to_purchase) {
        if (enabled_global_igv_to_purchase === undefined) enabled_global_igv_to_purchase = false;
        state.config.enabled_global_igv_to_purchase = enabled_global_igv_to_purchase
    },
    setCurrencyTypes(state, currency_types) {
        if (currency_types === undefined) currency_types = [];
        state.currency_types = currency_types
    },
    setAffectationIgvTypes(state, affectation_igv_types) {
        if (affectation_igv_types === undefined) affectation_igv_types = [];
        state.affectation_igv_types = affectation_igv_types
    },
    setUnitTypes(state, unit_types) {
        state.unit_types = (unit_types === undefined) ?[]: unit_types
    },
    setCountries(state,countries){ state.countries = (countries === undefined) ?[]: countries },
    setAllDepartments(state,all_departments){ state.all_departments = (all_departments === undefined) ?[]: all_departments },
    setAllProvinces(state,all_provinces){ state.all_provinces = (all_provinces === undefined) ?[]: all_provinces },
    setAllDistricts(state,all_districts){ state.all_districts = (all_districts === undefined) ?[]: all_districts },
    setIdentityDocumentTypes(state,identity_document_types){ state.identity_document_types = (identity_document_types === undefined) ?[]: identity_document_types },
    setLocations(state,locations){ state.locations = (locations === undefined) ?[]: locations },
    setPersonTypes(state,person_types){ state.person_types = (person_types === undefined) ?[]: person_types },
    setSeries(state,series){ state.series = (series === undefined) ?[]: series },
    setAllSeries(state,all_series){ state.all_series = (all_series === undefined) ?[]: all_series },

    setSellers(state,sellers){ state.sellers = (sellers === undefined) ?[]: sellers },
    setStatusDocumentary(state,statusDocumentary){ state.statusDocumentary = (statusDocumentary === undefined) ?[]: statusDocumentary },




    setAllItems(state, all_items) {
        if (state.all_items !== undefined) {
            let temp_item = [state.all_items, ...all_items]
            temp_item = temp_item.filter((item, index, self) =>
                    index === self.findIndex((t) => (
                        t.id === item.id
                    ))
            )
            state.all_items = temp_item
        } else {
            state.all_items = all_items
        }
    },
}
