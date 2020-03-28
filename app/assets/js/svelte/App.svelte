<script>
	import Tableau from './tableau/Tableau.svelte';
	let tab = "none";

	function personnes() {
		tab = "peoples";
	}
	function institutions() {
		tab = "institutions";
	}
	function spectacles() {
		tab = "shows";
	}
	function tags() {
		tab = "tags";
	}
</script>
<h2><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>
<div class="container" id="buttons_svelte">
	<button class="myButton" on:click="{personnes}"><i class="fas fa-users"></i> Personnes </button>
	<button class="myButton" on:click="{institutions}"><i class="fas fa-university"></i> Institutions </button>
	<button class="myButton" on:click="{spectacles}"><i class="fas fa-theater-masks"></i> Spectacles </button>
	<button class="myButton" on:click="{tags}"><i class="fas fa-tags"></i> Tags </button>
</div>
<hr>
{#if tab == "peoples"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-users"></i> Liste des personnes</h2>
	</Tableau>
{/if}

{#if tab == "institutions"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-university"></i> Liste des institutions</h2>
	</Tableau>
{/if}

{#if tab == "shows"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-theater-masks"></i> Liste des spectacles</h2>
	</Tableau>
{/if}

{#if tab == "tags"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-tags"></i> Liste des tags</h2>
	</Tableau>
{/if}
<style>
	.container{
		display: flex;
		justify-content: space-evenly;
	}

	.myButton {
		box-shadow:inset 0px 1px 0px 0px #951b81;
		background:linear-gradient(to bottom, #951b81 5%, #951b81 100%);
		background-color:#951b81;
		border-radius:6px;
		border:1px solid #951b81;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:22px;
		padding: 5px;
		font-weight:bold;
		text-decoration:none;
		text-shadow:0px 1px 0px #9b14b3;
	}
	.myButton:hover {
		background:linear-gradient(to bottom, #951b81 5%, #951b81 100%);
		background-color:#951b81;
	}
	.myButton:active {
		position:relative;
		top:1px;
	}

	hr{
		border: 1px solid #951b81;
	}
</style>
