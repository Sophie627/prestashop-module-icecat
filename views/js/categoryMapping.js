var icecat_category_data = [];
$(document).ready(function(){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: mapping_ajax_link,
        data: {
            ajax: true,
            controller: 'CategoryMappingAdmin',
            action: 'getCategory',
        },
        success: function (data) {

            var buildTree = (treeData, parentLayer, parentNode) => {
                if(typeof treeData.subs !== "undefined") {
                    var i = 0;
                    Object.entries(treeData.subs).forEach(entry => {
                        const [key, value] = entry;
                        var div = document.createElement('div');
                        div.innerHTML = value.title;
                        var decoded = div.firstChild.nodeValue;
                        icecat_category_data.push('<div class="list-group-item" parentlayer="' + parentLayer + '" parentnode="' + parentNode + '" node="' + i + '" draggable="true" style="margin-left: ' + (parentLayer * 10).toString() + 'px;">' + "+".repeat(parentLayer + 1) + " " + decoded + '</div>');
                        buildTree(value, parentLayer + 1, i);
                        i++;
                    });
                }
            };
            if(typeof data.status !== "undefined"){
                buildTree(data.categories, 0, 0);
                var clusterize = new Clusterize({
                    rows: icecat_category_data,
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
                controller: 'CategoryMappingAdmin',
                action: 'getSupplier',
                id_supplier: this.value,
            },
            success: function (data) {
                if(typeof data.status !== "undefined"){
                    var feed_category_data = [];
                    Object.entries(data.supplier_categories).forEach(entry => {
                        const [key, value] = entry;
                        feed_category_data.push('<div class="list-group-item" data-id=key data-label=value.category draggable="true">' + value.category + '</div>');
                    });
                    var clusterize = new Clusterize({
                        rows: feed_category_data,
                        scrollId: 'scrollFeedArea',
                        contentId: 'contentFeedArea'
                    });
                }
            },
        });
    });
});

//icecat -> feed
var selected_icecat_category;
function on_feed_drop(event){
    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if(selected_icecat_category != null) {
        selected_icecat_category = selected_icecat_category.split("+").join("");
        if(targetContent.indexOf("->") < 0){
            event.target.textContent = event.target.textContent + " -> " + selected_icecat_category;
            // supplier_brand_mapping.push([event.target.textContent, selected_icecat_category]);
            is_changed = true;
        } else {
            event.target.textContent = event.target.textContent.slice(0, targetContent.indexOf("->")) + " -> " + selected_icecat_category;
            // supplier_brand_mapping.push([event.target.textContent, selected_icecat_category]);
            is_changed = true;
        }
    }
    selected_icecat_category = null;
    selected_feed_category = null;
}

function on_icecat_mousedown(event){
    selected_icecat_category = event.target.textContent;
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
var selected_feed_category;
function on_icecat_drop(event){

    function htmlDecode(input){
          var e = document.createElement('textarea');
          e.innerHTML = input;
          // handle case of empty input
          return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
    }

    event.target.classList.remove('highlight');
    var targetContent = event.target.textContent;
    if (selected_feed_category != null) {
        var index = icecat_category_data.indexOf(htmlDecode(event.target.outerHTML));
        console.log(index);
        icecat_category_data.splice(index, 0, '<div class="list-group-item" parentlayer="' + event.target.attributes.parentlayer.nodeValue + '" parentnode="' + event.target.attributes.parentnode.nodeValue + '" data-id=key data-label=value.title draggable="true" style="margin-left: ' + (event.target.attributes.parentlayer.nodeValue * 10).toString() + 'px;">' + "+".repeat(Number(event.target.attributes.parentlayer.nodeValue) + 1) + " " + selected_feed_category + '</div>');
        var clusterize = new Clusterize({
            rows: icecat_category_data,
            scrollId: 'scrollIcecatArea',
            contentId: 'contentIcecatArea'
        });
        // supplier_brand_mapping.push([event.target.title, selected_feed_category]);
        is_changed = true;
    }
    selected_icecat_category = null;
    selected_feed_category = null;
}

function on_feed_mousedown(event){
    if(event.target.textContent.indexOf('->') < 0) {
        selected_feed_category = event.target.textContent;
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