var filterObject = {
    vehicleStatus: {
        newVehicle: false,
        usedVehicle: false,
        cpoVehicle: false,
    },
    vehicleMake: {
        Ford: false,
        Hyundai: false,
        Infiniti: false,
    }
};

function Vehicle(json_data) {
    return json_data;
}

var ViewModel = function() {
    var self = this;
    this.list = ko.observableArray();

    this.addVehicle = function(x) {
        this.list.push(new Vehicle(x));
    };
};

$(document).ready(function() {
    var vm = new ViewModel();
    LoadAllData(vm);
    //LoadFilterData();
    ko.applyBindings(vm);

    //FLIP VEHICLE CARD ON BUTTON CLICK
    $(document).on('click', '.flip-card', function (e) {
       let cardID = this.getAttribute('id');
       //cardID = '#'+cardID;
        $(  '#'+cardID ).css( "transform", "rotateY(180deg)" );
        e.preventDefault();
    });

    //CLICK EVENT FOR N, U, CPO
    $('.status-checkbox').change(function() {
        var count = 0;
        if (this.checked) {
            $(this).attr("checked", true);
        } else {
            $(this).attr("checked", false);
        }
        $('.status-checkbox').each(function(){
            var status = $(this).is(':checked');
            filterObject.vehicleStatus[$(this).attr('id')] = status;
            if (status) {count++;}
        });
        //TODO Check to see if all filters are off. If so Load all data. NEED A FUNCTION FOR THIS!
        FilterBuilder(vm);
    });

    $('.model-icon').on('click', function() {
        ($(this).hasClass("selected") ? $(this).removeClass("selected") : $(this).addClass("selected"));

        $('.model-icon').each(function() {
            var status = $(this).hasClass('selected');
            filterObject.vehicleMake[$(this).attr('id')] = status;
        });
        FilterBuilder(vm);
    })
});

function LoadAllData(vm) {
    jQuery.ajax({
        url: '/get/all_inventory',
        type: 'GET',
        success: function (msg) {
            //console.log(JSON.parse(msg));
            //vm.addVehicle(msg);
            ParseData(vm, msg);
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}

function LoadFilterData() {
    let url = '/get/filter'+'?stock_no=B76104';
    jQuery.ajax({
        url: url,
        type: 'GET',
        success: function (msg) {

        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}

function ParseData(vm, msg) {
    vm.list([]);
    if (msg != null) {
        let data = JSON.parse(msg);
        for (let i = 0; i <= data.length-1; ++i) {
            vm.addVehicle(data[i]);
        }
    }
    else {
        console.log('No data returned from controller');
    }
}
/*
    Use this function to take the set filters, build a URL, send to LoadFilterData()
 */
function FilterBuilder(vm) {
    jQuery.ajax({
        url: '/get/filter',
        type: 'POST',
        data: filterObject,
        success: function (msg) {
            if (msg === 'NID') {
                vm.list([]);
                return;
            }
            console.log(msg);
            ParseData(vm, msg);
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}
