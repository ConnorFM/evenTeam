$(document).ready(function(){
    $("#modalEditUser").on("show.bs.modal", function(event){
        // Get the button that triggered the modal
        var button = $(event.relatedTarget);

        // Extract value from the custom data-* attribute
        var titleData = button.data("title");
        $(this).find(".modal-title").text(titleData);
    });
});

function searchUser() {
	search_div = document.getElementById('search_div');
	search_div.innerHTML = "";
	var j = 0;
	for (var i = 0; i < tab.length; i++) {
	if (j > 15)
	break;
	if (tab[i].firstname.includes(document.getElementById('search').value)) {
	console.log(tab[i].firstname);
	// Création de rowDiv
	myrowDiv = document.createElement('div');
	myrowDiv.className = "row";
	myrowDiv.style.width = "21.68rem";
	// Création des colDiv
	mycolDivfirst = document.createElement('div');
	mycolDivfirst.className = "col-6";
	mycolDivsecond = document.createElement('div');
	mycolDivsecond.className = "col-2";
	mycolDivthird = document.createElement('div');
	mycolDivthird.className = "col-2";
	// Creation 
	// Création d'une balise a qui se nomme myAfirst      
	myAfirst = document.createElement('a');
	myAfirst.className = "dropdown-item";
	myAfirst.textContent = tab[i].firstname; 
	myAfirst.href = "https://google.fr";
	// Création d'une balise a qui se nomme myAsecond
	myAsecond = document.createElement('a');
	myAsecond.href ="#modalEditUser" + tab[i].id;
	myAsecond.id = tab[i].id;
	myAsecond.setAttribute("data-toggle", "modal");
	// Création d'une balise a qui se nomme myAthird
	myAthird = document.createElement('a');
	myAthird.href = "/user/delete/" + tab[i].id;
	// Création des balises I (font awesome)
	myIfirst = document.createElement('i');
	myIfirst.className = "fas fa-pen";
	myIsecond = document.createElement('i');
	myIsecond.className = "fas fa-trash-alt";
	// myAsecond est le papa de myIfirst
	mycolDivfirst.appendChild(myAfirst);
	mycolDivsecond.appendChild(myAsecond);
	mycolDivthird.appendChild(myAthird);
	myAsecond.appendChild(myIfirst);
	myAthird.appendChild(myIsecond);
	myrowDiv.appendChild(mycolDivfirst);
	myrowDiv.appendChild(mycolDivsecond);
	myrowDiv.appendChild(mycolDivthird);
	search_div.appendChild(myrowDiv)  
	j++;
	} 
	}  
	}