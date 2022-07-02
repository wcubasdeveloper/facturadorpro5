<?php

return [
    'number_items_in_search' =>env('NUMBER_SEARCH_ITEMS', 250),
    'number_items_at_start' =>env('NUMBER_ITEMS', 20),
    'extra_log' =>env('EXTRA_LOG', false),
    'suscription_facturalo' =>env('FACTURALO_SUSCRIPTION', false),
    'apk_url' =>env('APK_URL', 'https://facturaloperu.com/apk/app-debug.apk'),
    'wiki_pharmacy' =>env('WIKI_PHARMACY', 'https://gitlab.com/carlomagno83/facturadorpro4/-/wikis/Modulo-Farmacia'),
    'wiki_production' =>env('WIKI_PRODUCTION', 'https://gitlab.com/carlomagno83/facturadorpro4/-/wikis/App-Produccion'),
    'AllowClientUseOwnApiperuToken' =>env('ALLOW_CLIENT_USE_OWN_APIPERU_TOKEN', false),
];
