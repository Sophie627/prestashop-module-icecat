var brand_data = [];
var category_data = [];
var model_data = [];
var selectedSupplier = null;
var selectedBrand = null;
var selectedCategory = null;
var selectedModel = null;
var selectedSupplierList = null;
var selectedBrandList = null;
var selectedCategoryList = null;
var selectedModelList = null;

$(document).ready(function(){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: mapping_ajax_link,
        data: {
            ajax: true,
            controller: 'PriceMappingAdmin',
            action: 'getAllSupplierData',
        },
        success: function (data) {
            console.log(data);
            if(typeof data.status !== "undefined"){
                Object.entries(data.supplier_brands).forEach(entry => {
                    const [key, value] = entry;
                    brand_data.push('<div class="list-group-item" data-id=key value="' + value + '" draggable="true" onclick="on_brand_click(event);" >' + value + '</div>');
                });
                var clusterize = new Clusterize({
                    rows: brand_data,
                    scrollId: 'scrollBrandArea',
                    contentId: 'contentBrandArea'
                });
                if(data.supplier_categories != null) {
                    Object.entries(data.supplier_categories).forEach(entry => {
                        const [key, value] = entry;
                        category_data.push('<div class="list-group-item" data-id=key value="' + value + '" draggable="true" onclick="on_category_click(event);">' + value + '</div>');
                    });
                    var clusterize = new Clusterize({
                        rows: category_data,
                        scrollId: 'scrollCategoryArea',
                        contentId: 'contentCategoryArea'
                    });
                } else {
                    category_data = ['<div></div>'];
                    var clusterize = new Clusterize({
                        rows: category_data,
                        scrollId: 'scrollCategoryArea',
                        contentId: 'contentCategoryArea'
                    });
                }
                Object.entries(data.supplier_models).forEach(entry => {
                    const [key, value] = entry;
                    model_data.push('<div class="list-group-item" data-id=key value="' + value + '" draggable="true" onclick="on_model_click(event);">' + value + '</div>');
                });
                var clusterize = new Clusterize({
                    rows: model_data,
                    scrollId: 'scrollModelArea',
                    contentId: 'contentModelArea'
                });
            }
        },
    });
    $("#suppliers").change(function(){
        selectedSupplier = this.value;
        // document.getElementById("contentFeedArea").innerHTML = '<div class="clusterize-no-data">Loading data…</div>';
        ajaxFilter(selectedSupplier, selectedBrand, selectedCategory, selectedModel);
    });
});

function on_brand_click(event) {
    selectedBrand = event.target.attributes.value.nodeValue;
    if (selectedBrandList != null) selectedBrandList.classList.remove('selected-list');
    selectedBrandList = event.target;
    selectedBrandList.classList.add('selected-list');
    ajaxFilter(selectedSupplier, selectedBrand, selectedCategory, selectedModel);
}

function on_category_click(event) {
    selectedCategory = event.target.attributes.value.nodeValue;
    if (selectedCategoryList != null) selectedCategoryList.classList.remove('selected-list');
    selectedCategoryList = event.target;
    selectedCategoryList.classList.add('selected-list');
    ajaxFilter(selectedSupplier, selectedBrand, selectedCategory, selectedModel);
}

function on_model_click(event) {
    selectedModel = event.target.attributes.value.nodeValue;
    if (selectedModelList != null) selectedModelList.classList.remove('selected-list');
    selectedModelList = event.target;
    selectedModelList.classList.add('selected-list');
    ajaxFilter(selectedSupplier, selectedBrand, selectedCategory, selectedModel);
}

function ajaxFilter(supplier, brand, category, model) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: mapping_ajax_link,
        data: {
            ajax: true,
            controller: 'PriceMappingAdmin',
            action: 'filterProductForPrice',
            id_supplier: supplier,
            brand: brand,
            category: category,
            model: model,
        },
        success: function (data) {
            console.log(data);
        //     if(typeof data.status !== "undefined"){
        //         var feed_brand_data = [];
        //         Object.entries(data.supplier_brands).forEach(entry => {
        //             const [key, value] = entry;
        //             feed_brand_data.push('<div class="list-group-item" data-id=key data-label=value.manufacturer draggable="true">' + value.manufacturer + '</div>');
        //         });
        //         var clusterize = new Clusterize({
        //             rows: feed_brand_data,
        //             scrollId: 'scrollFeedArea',
        //             contentId: 'contentFeedArea'
        //         });
        //     }
        },
    });
}