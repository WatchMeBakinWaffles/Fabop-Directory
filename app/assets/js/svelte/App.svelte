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
<div style="text-align:center;">
	<div class="btn-group btn-group-lg" id="buttons_svelte" style="width:80%;">
		<!-- TODO::Il faut mieux gérer les droits pour tout afficher,
		un user non admin ne peut pas voir institutions par exemple,certains buttons ne doivent pas s'afficher selon role -->
		<button class="btn btn-primary col-4" on:click="{personnes}"><i class="fas fa-users"></i> Personnes </button>
		<!--<button class="myButton" on:click="{institutions}"><i class="fas fa-university"></i> Institutions </button>-->
		<button class="btn btn-primary col-4" on:click="{spectacles}"><i class="fas fa-theater-masks"></i> Spectacles </button>
		<button class="btn btn-primary col-4" on:click="{tags}"><i class="fas fa-tags"></i> Tags </button>
	</div>
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


	hr{
		border: 1px solid #951b81;
	}
</style>
