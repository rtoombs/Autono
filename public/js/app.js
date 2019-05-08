var vm;
var filterObject = {
    vehicleStatus: {
        newVehicle: false,
        usedVehicle: false,
        cpoVehicle: false,
    },
    vehicleMake: {
        Count: 0,
        Ford: false,
        Hyundai: false,
        Infiniti: false,
    },
    priceFilter: {
        lowPrice: 0,
        highPrice: 0,
        maxPrice: 0,
    },
    yearFilter: {
        minYear: 0,
        lowYear: 0,
        highYear: 0,
        maxYear: 0,
    },
    //Fill out bodyFilter object dynamically based on data. Should do for VehMake eventually.
    bodyFilter: {
        Count: 0,
    },
};

function Vehicle(json_data) {
    return json_data;
}

var ViewModel = function() {
    var self = this;
    this.list = ko.observableArray();
    this.body = ko.observableArray();

    this.addVehicle = function(x) {
        this.list.push(new Vehicle(x));
    };

    this.addBody = function(x) {
        let id = x;
        filterObject.bodyFilter[id] = false;
        this.body.push({name: x, id: id});
    };

    this.updateBody = function(x) {
        this.body([]);
        for (let i = 0; i < x.length; ++i) {
            let name = x[i]['veh_style'];
            this.body.push({name: name, id: name});
            if (filterObject.bodyFilter[name] === true) {
                let id = '#' + name;
                jQuery(".body-list " + id).addClass("selected");
            }
        }
    }
};

$(document).ready(function() {
    vm = new ViewModel();
    LoadAllData(vm);
    //LoadFilterData();
    ko.applyBindings(vm);
    GetPriceSliderValue();
    GetYearSliderValue();
    InitializeBodyTypes();

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
        FilterBuilder(vm);
    });

    $('.model-icon').on('click', function() {
        let currentID = $(this).attr('id');
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            filterObject.vehicleMake[currentID] = false;
            --filterObject.vehicleMake['Count'];
        } else {
            $(this).addClass("selected");
            filterObject.vehicleMake[currentID] = true;
            ++filterObject.vehicleMake['Count'];
        }
        FilterBuilder(vm);
    })

    $(document).on('click', '.body-filter-item', function (e) {
        let currentID = $(this).attr('id');
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            filterObject.bodyFilter[currentID] = false;
            --filterObject.bodyFilter['Count'];
        } else {
            $(this).addClass("selected");
            filterObject.bodyFilter[currentID] = true;
            ++filterObject.bodyFilter['Count'];
        }
        FilterBuilder(vm);
        e.preventDefault();
    });

    $('#reset-button').on('click', function() {
        console.log('Whoa there partner');
        ResetFilters();
    });

    $("#price-button").click(function(){
        if ($('#price-filter-options').is(":visible")) {
            $("#price-filter-options").slideUp();
            $('.filter-overlay').hide();
        } else {
            $("#price-filter-options").slideDown();
            $('.filter-overlay').show();
        }
    });

    $("#year-button").click(function(){
        if ($('#year-filter-options').is(":visible")) {
            $("#year-filter-options").slideUp();
            $('.filter-overlay').hide();
        } else {
            $("#year-filter-options").slideDown();
            $('.filter-overlay').show();
        }
    });

    $("#body-button").click(function() {
        if ($('#body-filter-options').is(":visible")) {
            $("#body-filter-options").slideUp();
            $('.filter-overlay').hide();
        } else {
            $("#body-filter-options").slideDown();
            $('.filter-overlay').show();
        }
    });

    $(".filter-overlay").click(function() {
        if ($('#price-filter-options').is(":visible")) {
            $("#price-filter-options").slideUp();
            $('.filter-overlay').hide();
        } else if ($('#year-filter-options').is(":visible")) {
            $("#year-filter-options").slideUp();
            $('.filter-overlay').hide();
        } else if ($('#body-filter-options').is(":visible")) {
            $("#body-filter-options").slideUp();
            $('.filter-overlay').hide();
        }
    });

    $('#price-reset').click(function() {
        filterObject.priceFilter.lowPrice  = 0;
        InitializePriceSlider(filterObject.priceFilter.maxPrice);
        FilterBuilder(vm);
    });

    $('#year-reset').click(function() {
        filterObject.yearFilter.lowYear = filterObject.yearFilter.minYear;
        filterObject.yearFilter.highYear = filterObject.yearFilter.maxYear;
        InitializeYearSlider(filterObject.yearFilter.minYear, filterObject.yearFilter.maxYear);
        FilterBuilder(vm);
    });
});

function LoadAllData(vm) {
    jQuery.ajax({
        url: '/get/all_inventory',
        type: 'GET',
        success: function (msg) {
            //console.log(JSON.parse(msg));
            //vm.addVehicle(msg);
            ParseData(vm, msg, true);
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}

//Abandoned Function
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

//TODO A bit redundant. Room for optimization.
function ParseData(vm, msg, flag) {
    vm.list([]);
    if (msg != null) {
        if (flag) {
            let data = JSON.parse(msg);
            for (let i = 0; i <= data.length - 1; ++i) {
                vm.addVehicle(data[i]);
            }
        } else {
            let data = JSON.parse(msg);
            for (let i = 0; i <= data.vehicleList.length - 1; ++i) {
                vm.addVehicle(data.vehicleList[i]);
            }
            vm.updateBody(data.bodyUpdate);
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
            //console.log(msg);
            ParseData(vm, msg, false);
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}

function GetPriceSliderValue() {
    let max;
    jQuery.ajax({
        url: '/get/max_price',
        type: 'GET',
        success: function (msg) {
            let parse = JSON.parse(msg);
            max = parseInt(parse[0]["max(msrp_price)"]);
            if (Number.isInteger(max)) { InitializePriceSlider(max); }
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
        }
    });
}

function InitializePriceSlider(max) {
    filterObject.priceFilter.highPrice = max;
    filterObject.priceFilter.maxPrice = max;
    jQuery( function() {
        jQuery( "#slider-price" ).slider({
            range: true,
            min: 0,
            max: max,
            values: [ 0, max ],
            slide: function( event, ui ) {
                jQuery( "#price-range" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            }
        });
        jQuery( "#price-range" ).val( "$" + jQuery( "#slider-price" ).slider( "values", 0 ) +
            " - $" + jQuery( "#slider-price" ).slider( "values", 1 ) );

        jQuery("#slider-price").on("slidestop", function (event, ui) {
            filterObject.priceFilter.lowPrice = jQuery( "#slider-price" ).slider( "values", 0 );
            filterObject.priceFilter.highPrice = jQuery( "#slider-price" ).slider( "values", 1 );
            FilterBuilder(vm);
        })
    } );

}

function GetYearSliderValue() {
    let max;
    let min;
    jQuery.ajax({
        url: '/get/max_year',
        type: 'GET',
        success: function (msg) {
            let parse = JSON.parse(msg);
            max = parseInt(parse[0].max);
            min = parseInt(parse[0].min);
            filterObject.yearFilter.minYear = min;
            filterObject.yearFilter.maxYear = max;
            InitializeYearSlider(min, max);
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}

function InitializeYearSlider(min, max) {
    filterObject.yearFilter.lowYear = min;
    filterObject.yearFilter.highYear = max;
    jQuery( function() {
        jQuery( "#slider-year" ).slider({
            range: true,
            min: min,
            max: max,
            values: [ min, max ],
            slide: function( event, ui ) {
                jQuery( "#year-range" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
            }
        });
        jQuery( "#year-range" ).val(jQuery( "#slider-year" ).slider( "values", 0 ) +
            " - " + jQuery( "#slider-year" ).slider( "values", 1 ) );
        jQuery("#slider-year").on("slidestop", function (event, ui) {
            filterObject.yearFilter.lowYear = jQuery( "#slider-year" ).slider( "values", 0 );
            filterObject.yearFilter.highYear = jQuery( "#slider-year" ).slider( "values", 1 );
            console.log(filterObject);
            FilterBuilder(vm);
        })
    });
}
/*
    A Development function to explore logistics of dynamically and efficiently listing body types
 */
function InitializeBodyTypes() {
    jQuery.ajax({
        url: '/get/all_body_types',
        type: 'GET',
        success: function (msg) {
            let parse = JSON.parse(msg);
            for (let i = 0; i < parse.length; ++i) {
                vm.addBody(parse[i]['veh_style'])
            }
        },
        error: function (xhr, errormsg) {
            console.log(xhr);
            //window.location = '/';
        }
    });
}
/*
    TODO Add button to reset filters and develop function to reset the filterObject
 */
function ResetFilters() {
    //RESET MODEL ICONS
    var modelIconElements = document.getElementsByClassName('model-icon');
    for (var i = 0; i < modelIconElements.length; ++i) {
        if (modelIconElements[i].classList.contains('selected')) {
            modelIconElements[i].classList.remove('selected');
            var modelElementID = modelIconElements[i].id;
            filterObject.vehicleMake[modelElementID] = false;
            filterObject.vehicleMake.Count = 0;
        }
    }
    //RESET VEHICLE STATUS
    var statusElements = document.getElementsByClassName('status-checkbox');
    for (var j = 0; j < statusElements.length; ++j) {
        if (statusElements[j].hasAttribute('checked')) {
            statusElements[j].removeAttribute('checked');
            statusElements[j].checked = false;
            var statusElementID = statusElements[j].id;
            filterObject.vehicleStatus[statusElementID] = false;
        }
    }
    //RESET BODY STATUS
    var bodyElements = document.getElementsByClassName('body-filter-item');
    for (var k = 0; k < bodyElements.length; ++k) {
        if (bodyElements[k].classList.contains('selected')) {
            bodyElements[k].classList.remove('selected');
            var bodyElementID = bodyElements[k].id;
            filterObject.bodyFilter[bodyElementID] = false;
            filterObject.bodyFilter.Count = 0;
        }
    }
    //RESET PRICE SLIDER AND STATUS
    filterObject.yearFilter.lowYear = filterObject.yearFilter.minYear;
    filterObject.yearFilter.highYear = filterObject.yearFilter.maxYear;
    InitializeYearSlider(filterObject.yearFilter.minYear, filterObject.yearFilter.maxYear)
    //RESET YEAR SLIDER AND STATUS
    filterObject.priceFilter.lowPrice  = 0;
    InitializePriceSlider(filterObject.priceFilter.maxPrice);
    FilterBuilder(vm);
    //console.log(filterObject);
}

function UpdateFilters(data) {
    /*vm.body([]);
    for (let i = 0; i < data.length; ++i) {
        vm.updateBody(data[i]['veh_style']);
    }*/


}

//TODO: Fix bug where selecting body with no other filters selected will remove other options. Only remove other options when a body filter is selected while another filter is active.