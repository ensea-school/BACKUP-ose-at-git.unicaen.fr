function serviceShowHideDetails(serviceA){

    var serviceId = serviceA.dataset.serviceId;

    var tr = $('#service-' + serviceId + '-details');

    if ('none' == tr.css('display')){
        $(serviceA).html('<span class="glyphicon glyphicon-chevron-up"></span>');
        tr.show(200);
    }else{
        $(serviceA).html('<span class="glyphicon glyphicon-chevron-down"></span>');
        tr.hide(200);
    }
}