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
                var brand_data = [];
                Object.entries(data.brands).forEach(entry => {
                    const [key, value] = entry;
                    brand_data.push('<div class="list-group-item" id="icecat" data-id=key data-label=value draggable="true">' + value + '</div>');
                });
                var clusterize = new Clusterize({
                    rows: brand_data,
                    scrollId: 'scrollArea',
                    contentId: 'contentArea'
                });
            }
        },
    });
    $("#suppliers").change(function(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: mapping_ajax_link,
            data: {
                ajax: true,
                controller: 'BrandMappingAdmin',
                action: 'getSupplier',
                id_supplier: this.value,
            },
            success: function (data) {
                if(typeof data.status !== "undefined"){
                    console.log(data.headers);
                    document.getElementById("list-right").innerHTML = "";
                    data.supplier_brands.forEach(element => {
                        console.log(element.manufacturer);
                        document.getElementById("list-right").innerHTML+='<div class="list-group-item" id="feed" title="'+element.manufacturer+'" draggable="true">'+element.manufacturer+'</div>';
                    });
                }
            },
        });
    });
});

//icecat -> feed
var selected_icecat_brand;
function on_feed_drop(event){
    console.log(event.target.attributes.id.nodeValue);
    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if(event.target.attributes.id.nodeValue == "feed") {
        if(targetContent.indexOf("->") == -1 || selected_icecat_brand != targetContent.substr(targetContent.indexOf("->")+3)){
            event.target.textContent = event.target.title + " -> " + selected_icecat_brand;
            supplier_brand_mapping.push([event.target.title, selected_icecat_brand]);
            is_changed = true;
        }
    }
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
    console.log(event.target);
    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if(targetContent.indexOf("->") == -1 || selected_feed_brand != targetContent.substr(targetContent.indexOf("->")+3)){
        event.target.textContent = event.target.title + " -> " + selected_feed_brand;
        supplier_brand_mapping.push([event.target.title, selected_feed_brand]);
        is_changed = true;
    }
}

function on_feed_mousedown(event){
    selected_feed_brand = event.target.textContent;
    console.log(event.target);
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