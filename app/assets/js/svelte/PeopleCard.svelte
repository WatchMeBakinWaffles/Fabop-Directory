<script type="text/javascript">
let id = 1;
let name;
let email;
let institutionName;

console.log( name, email, institutionName);

async function fetchAsync () {
  // await response of fetch call
  let response = await fetch('http://172.18.0.1/api/entity_peoples/'+id);
  // only proceed once promise is resolved
  let data = await response.json();
  // only proceed once second promise is resolved
  return data;
}
fetchAsync()
  .then(data => createCard(data))
  .catch(reason => console.log(reason.message))

function createCard(data){
	name = data["name"] + " " + data["firstname"];
	email = data["adresseMailing"];
	
	async function fetchAsync () {
	// await response of fetch call
	let response = await fetch(data["institution"]);
	// only proceed once promise is resolved
	let institution = await response.json();
	// only proceed once second promise is resolved
	return institution;
	}
	fetchAsync()
	.then(institution => getInstitution(institution))
	.catch(reason => console.log(reason.message))

	function getInstitution(institution){
		institutionName = institution["name"] + ", " + institution["role"]
		console.log( name, email, institutionName);
	}

}
</script>

<article class="contact-card">
	<h2>
		<slot name="name">{name}</slot>
	</h2>

	<div class="institution">
		<i class="fas fa-university"></i><slot name="institution">&nbsp;{institutionName}</slot>
	</div>

	<div class="email">
		<i class="fas fa-at"></i><slot name="email">&nbsp;{email}</slot>
	</div>
</article>

<style>
	.contact-card {
		width: 300px;
		border: 1px solid #aaa;
		border-radius: 2px;
		box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
		padding: 1em;
	}

	h2 {
		padding: 0 0 0.2em 0;
		margin: 0 0 1em 0;
		border-bottom: 1px solid #951b81
	}
	i {
		color: #951b81;
	}

	.institution, .email {
		padding: 0 0 0 1.5em;
		background:  0 0 no-repeat;
		background-size: 20px 20px;
		margin: 0 0 0.5em 0;
		line-height: 1.2;
	}

	.institution { background-image: url(tutorial/icons/map-marker.svg) }
	.email   { background-image: url(tutorial/icons/email.svg) }
</style>