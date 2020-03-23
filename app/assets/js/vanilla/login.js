const inputs = document.querySelectorAll(".input-login");


function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}

inputs.forEach(input => {
	if (input.value!=""){
		input.parentNode.parentNode.classList.add("focus");
	}
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});
