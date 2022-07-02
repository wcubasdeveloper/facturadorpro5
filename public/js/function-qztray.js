function startConnection(config) {
    if (!qz.websocket.isActive()) {
        console.log('Waiting, default');

        qz.websocket.connect(config).then(function() {
            console.log('Active, success');
            findVersion();
            findDefaultPrinter(true);
        }).catch(handleConnectionError);
    } else {
        displayError('An active connection with QZ already exists.', 'alert-warning');
    }
}

var qzVersion = 0;
function findVersion() {
    qz.api.getVersion().then(function(data) {
        qzVersion = data;
    }).catch(displayError);
}

function handleConnectionError(err) {
    console.log('Error, danger');

    if (err.target != undefined) {
        if (err.target.readyState >= 2) { //if CLOSING or CLOSED
            displayError("Connection to QZ Tray was closed");
        } else {
            displayError("A connection error occurred, check log for details");
            console.error(err);
        }
    } else {
        displayError(err);
    }
}
/// QZ Config ///
var cfg = null;
function getUpdatedConfig() {
    if (cfg == null) {
        cfg = qz.configs.create(null);
    }

    setConfigW80();
    return cfg
}

function setConfigW80(){
    updateConfig(80, 100, 0, 0, 0, 0, 1, 'Default', 0, 'mm');
}

function updateConfig(pxlW, pxlH, top, right, bottom, left, copies, orientation, rotation, units) {
    var pxlSize = null;
    if(pxlH>0){
        pxlSize = {
            width:  pxlW
        };
    }

    var pxlMargins = 0;
    if(top>0){
        pxlMargins = {
            top: top,
            right: right,
            bottom: bottom,
            left: left
        };
    }


    var jobName = null;

    cfg.reconfigure({
        altPrinting: 0,
        encoding: '',
        endOfDoc: '',
        perSpool: 1,

        colorType: 'Color',
        copies: copies,
        density: 0,
        duplex: 0,
        interpolation: 'Default',
        jobName: jobName,
        margins: pxlMargins,
        orientation: orientation,
        paperThickness: '',
        printerTray: '',
        rasterize: 1,
        rotation: rotation,
        scaleContent: 1,
        size: pxlSize,
        units: units
    });
}

function displayError(err) {
    //console.log(err);
    displayMessage(err, 'alert-danger');
}

function displayMessage(msg, css) {
    if (css == undefined) { css = 'alert-info'; }

    var timeout = setTimeout(function() { $('#' + timeout).alert('close'); }, 5000);

    var alert = $("<div/>").addClass('alert alert-dismissible fade in ' + css)
            .css('max-height', '20em').css('overflow', 'auto')
            .attr('id', timeout).attr('role', 'alert');
    alert.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + msg);

    $("#myalert").append(alert);
}

function findDefaultPrinter(set) {
    qz.printers.getDefault().then(function(data) {
        if (set) { setPrinter(data); }
    }).catch(displayError);
}

function setPrinter(printer) {
    var cf = getUpdatedConfig();
    cf.setPrinter(printer);

    if (typeof printer === 'object' && printer.name == undefined) {
        var shown;
        if (printer.file != undefined) {
            shown = "<em>FILE:</em> " + printer.file;
        }
        if (printer.host != undefined) {
            shown = "<em>HOST:</em> " + printer.host + ":" + printer.port;
        }

        //$("#configPrinter").html(shown);
        console.log(shown);
    } else {
        if (printer.name != undefined) {
            printer = printer.name;
        }

        if (printer == undefined) {
            printer = 'NONE';
        }
        //$("#configPrinter").html(printer);
        console.log(printer);
    }
}
