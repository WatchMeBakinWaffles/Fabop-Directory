<script type="text/javascript">
let id = 1;
let name;
let role;

async function fetchAsync () {
  // await response of fetch call
  let response = await fetch('http://172.18.0.1/api/entity_institutions/'+id);
  // only proceed once promise is resolved
  let data = await response.json();
  // only proceed once second promise is resolved
  return data;
}
fetchAsync()
  .then(data => createCard(data))
  .catch(reason => console.log(reason.message))

function createCard(data){
	name = data["name"];
	role = data["role"];
}
</script>

<article class="contact-card">
	<h2>
		<slot name="name">{name}</slot>
	</h2>

	<div class="role">
		<i class="fas fa-user-tag"></i><slot name="role">&nbsp;{role}</slot>
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
    i {
		color: #951b81;
	}

	h2 {
		padding: 0 0 0.2em 0;
		margin: 0 0 1em 0;
		border-bottom: 1px solid #951b81
	}

	.role {
		padding: 0 0 0 1.5em;
		background:  0 0 no-repeat;
		background-size: 20px 20px;
		margin: 0 0 0.5em 0;
		line-height: 1.2;
	}
</style>