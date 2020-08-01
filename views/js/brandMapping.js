var icecat_brand_data = [];
$(document).ready(function(){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: mapping_ajax_link,
        data: {
            ajax: true,
            controller: 'BrandMappingAdmin',
            action: 'getBrand',
        },
        success: function (data) {
            if(typeof data.status !== "undefined"){
                Object.entries(data.brands).forEach(entry => {
                    const [key, value] = entry;
                    icecat_brand_data.push('<div class="list-group-item" data-id=key data-label=value draggable="true">' + value + '</div>');
                });
                var clusterize = new Clusterize({
                    rows: icecat_brand_data,
                    scrollId: 'scrollIcecatArea',
                    contentId: 'contentIcecatArea'
                });
            }
        },
    });
    $("#suppliers").change(function(){
        document.getElementById("contentFeedArea").innerHTML = '<div class="clusterize-no-data">Loading data…</div>';
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: mapping_ajax_link,
            data: {
                ajax: true,
                controller: 'BrandMappingAdmin',
                action: 'getSupplierBrand',
                id_supplier: this.value,
            },
            success: function (data) {
                if(typeof data.status !== "undefined"){
                    var feed_brand_data = [];
                    Object.entries(data.supplier_brands).forEach(entry => {
                        const [key, value] = entry;
                        feed_brand_data.push('<div class="list-group-item" data-id=key data-label=value.manufacturer draggable="true">' + value.manufacturer + '</div>');
                    });
                    var clusterize = new Clusterize({
                        rows: feed_brand_data,
                        scrollId: 'scrollFeedArea',
                        contentId: 'contentFeedArea'
                    });
                }
            },
        });
    });
});

//icecat -> feed
var selected_icecat_brand;
function on_feed_drop(event){
    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if(selected_icecat_brand != null) {
        if(targetContent.indexOf("->") < 0){
            event.target.textContent = event.target.textContent + " -> " + selected_icecat_brand;
            supplier_brand_mapping.push([event.target.textContent, selected_icecat_brand]);
            is_changed = true;
        } else {
            event.target.textContent = event.target.textContent.slice(0, targetContent.indexOf("->")) + " -> " + selected_icecat_brand;
            supplier_brand_mapping.push([event.target.textContent, selected_icecat_brand]);
            is_changed = true;
        }
    }
    selected_icecat_brand = null;
    selected_feed_brand = null;
}

function on_icecat_mousedown(event){
    selected_icecat_brand = event.target.textContent;
    console.log(event.target);
}

function on_feed_dragover(event){
    event.target.classList.add('highlight');
    event.preventDefault();
}

function on_feed_dragleave(event){
    event.target.classList.remove('highlight');
}

//feed -> icecat
var selected_feed_brand;
function on_icecat_drop(event){
    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if (selected_feed_brand != null) {
        icecat_brand_data.unshift('<div class="list-group-item" data-label=selected_feed_brand draggable="true">' + selected_feed_brand + '</div>');
        var clusterize = new Clusterize({
            rows: icecat_brand_data,
            scrollId: 'scrollIcecatArea',
            contentId: 'contentIcecatArea'
        });
        supplier_brand_mapping.push([event.target.title, selected_feed_brand]);
        is_changed = true;
    }
    selected_icecat_brand = null;
    selected_feed_brand = null;
}

function on_feed_mousedown(event){
    if(event.target.textContent.indexOf('->') < 0) {
        selected_feed_brand = event.target.textContent;
        console.log(event.target);
    }
}

function on_icecat_dragover(event){
    event.target.classList.add('highlight');
    event.preventDefault();
}

function on_icecat_dragleave(event){
    event.target.classList.remove('highlight');
}

function allowDrop(event) {
    event.preventDefault();
}