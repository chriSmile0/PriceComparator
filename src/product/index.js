function addLabelInputField(id,nb) {
  let div_lab_m = document.createElement("div");
  div_lab_m.classList.add("lab_m");
  let label = document.createElement("label");
  label.setAttribute("for","city_"+id+nb);
  label.innerHTML = "Zone de recherche";
  let input = document.createElement("input");
  input.setAttribute("class","cities");
  input.addEventListener('input', city_display.bind(nb),true);
  input.setAttribute("type","select");
  input.setAttribute("name","city_"+id+nb);
  input.setAttribute("placeholder","Ville,Arrondissement");
  let datalist = document.createElement("datalist");
  input.setAttribute("list","citiesList"+id+nb);
  datalist.setAttribute("id","citiesList"+id+nb);
  datalist.setAttribute("class","citiesList");
  input.appendChild(datalist);
  div_lab_m.appendChild(label);
  div_lab_m.appendChild(input); 
  return div_lab_m;
}

function addFields(e) {
  e.preventDefault();
  e.stopPropagation();
  var elem_true = ((e.currentTarget.parentNode).parentNode).childNodes[3];
  var elem = document.getElementsByClassName("add_spe")[this];
  elem = elem_true; // if it's necessary 
  
  if(elem.childElementCount < 5)
    (elem.appendChild(addLabelInputField(e.currentTarget.id.substr(9,10),elem.childElementCount+1)));
}

function delFields(e) {
  e.preventDefault();
  e.stopPropagation();
  var elem_true = ((e.currentTarget.parentNode).parentNode).childNodes[3];
  var elem = (document.getElementsByClassName("add_spe")[this]);
  elem = elem_true; // if it's necessary 
  elem.removeChild(elem.lastChild);
}

var elements_adds_town = document.getElementsByClassName("add_town");
var elements_dels_town = document.getElementsByClassName("del_town");
var elements_cities = document.getElementsByClassName("cities");
for(var i = 0; i < elements_cities.length; i++) {
  elements_cities[i].addEventListener('input', city_display.bind(i),true);
}

for(var i = 0; i < elements_adds_town.length; i++) {
    elements_adds_town[i].addEventListener('click', addFields.bind(i),true);
    elements_dels_town[i].addEventListener('click', delFields.bind(i),true);
}

function city_display(e) {
  e.preventDefault();
  e.stopPropagation();
  var val = e.currentTarget.value;
  var div_parent_spe = ((e.currentTarget.parentNode).parentNode);//.childNodes[1].value;
  var ens = "";
  var add = "";
  if((div_parent_spe.classList.length == 1) && (div_parent_spe.classList.item(0) == "spe"))
    ens = div_parent_spe.children[0].children[1].value;
  else {
    ens = (div_parent_spe.parentNode).children[0].children[0].children[1].value;
    add = this;
  }

  var cities = document.getElementById("citiesList"+ens[0].toLowerCase()+add);
  if(val.length > 0) {
    let xmlhttp=new XMLHttpRequest()
    xmlhttp.onreadystatechange = function() {
      if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
        const reponse = JSON.parse(this.responseText);
        reponse.forEach(element => {
          let each_here = false;
          var i = 0;
          while(each_here === false && i < cities.children.length) {
            each_here = (cities.children.item(i).innerText == element);
            i++;
          }
          if(each_here == false) {
            var opt = document.createElement("option");
            opt.value = element;
            opt.innerHTML = element;
            cities.appendChild(opt);
          }
        });
      }
    }
    xmlhttp.open("POST","cities.php");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("listings="+val+"&ens="+ens);
  }
  else {
    cities.innerHTML = "";
  }
}

function global_waiting_update() {
  var elements_cities = document.getElementsByClassName("cities");
  var waiting_list = {"Carrefour":14,"Leclerc":-1,"Monoprix":5,"Auchan":40,"Intermarche":-1,"SystemeU":-1};
  var gb_wait = 0;
  if(document.getElementById('c3').checked == true)
    gb_wait = 5;

  for(var i = 0; i < elements_cities.length; i++) {
    var it = elements_cities.item(i);
    var div_parent_spe = ((it.parentNode).parentNode);
    var ens = "";
    if((div_parent_spe.classList.length == 1) && (div_parent_spe.classList.item(0) == "spe"))
      ens = div_parent_spe.children[0].children[1].value;
    else 
      ens = (div_parent_spe.parentNode).children[0].children[0].children[1].value;
    
    if(it.value.length > 3) 
      gb_wait += waiting_list[ens];
    
  }
  if(gb_wait > 0) {
    var wait = document.createElement("span");
    wait.setAttribute("id","wait");
    wait.innerHTML = "<br>Attente: "+gb_wait+ " secondes";
    this.style.height = "50px";
    this.style.width = "20%";
    this.appendChild(wait);
  }
  else {
    if(this.childElementCount == 1) {
      this.removeChild(this.lastChild);
      this.style.height = "42px";
      this.style.width = "17%";
    }
  }
}

document.getElementById('btnsubmit').addEventListener('mouseover',global_waiting_update,true);
document.getElementById('btnsubmit').addEventListener('focus',global_waiting_update,true);