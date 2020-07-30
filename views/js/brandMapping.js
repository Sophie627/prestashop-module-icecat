$(document).ready(function(){
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
                    document.getElementById("list-right").innerHTML+='<div class="list-group-item" title="'+element.manufacturer+'">'+element.manufacturer+'</div>';
                  });
              }
            },
        });
    });
});
var selected_brand;
function on_drop(event){
  console.log(event.target);
  event.target.classList.remove('highlight');
  var targetContent = event.target.textContent;
  if(targetContent.indexOf("->") == -1 || selected_brand != targetContent.substr(targetContent.indexOf("->")+3)){
    event.target.textContent = event.target.title + " -> " + selected_brand;
    supplier_brand_mapping.push([event.target.title, selected_brand]);
    is_changed = true;
  }
}

function on_mousedown(event){
  selected_brand = event.target.textContent;
  console.log(event.target);
}

function on_dragover(event){
  event.target.classList.add('highlight');
  event.preventDefault();
}

function on_dragleave(event){
  event.target.classList.remove('highlight');
}

function allowDrop(event) {
  event.preventDefault();
}